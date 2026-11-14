<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Order {
    public const PENDING = 0;
    public const PROCESS = 1;
    public const COMPLETED = 2;
    public const CANCELED = 3;

    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function all(?int $status = null): array {
        $sql = "SELECT o.*, GROUP_CONCAT(CONCAT(d.quantity, 'x ', d.menu_name)
                        ORDER BY d.created_date SEPARATOR ', ') AS items,
                        (SELECT p.payment_status FROM payments p
                        WHERE p.id_order = o.id_order
                        ORDER BY p.created_date DESC LIMIT 1) AS payment_status,
                        (SELECT p.payment_method FROM payments p
                        WHERE p.id_order = o.id_order
                        ORDER BY p.created_date DESC LIMIT 1) AS payment_method
                FROM orders o
                LEFT JOIN order_details d ON d.id_order = o.id_order AND d.status = 1
                WHERE o.is_deleted = 0"
                . ($status !== null ? " AND o.status = :status" : "") . "
                GROUP BY o.id_order
                ORDER BY o.order_date DESC"; 
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($status !== null ? [':status' => $status] : []);
        return $stmt->fetchAll();
    }

    public function todayStats(): array {
        $paid = "EXISTS (SELECT 1 FROM payments p
                        WHERE p.id_order = o.id_order AND p.payment_status = 'paid')";
        
        $row = $this->db->query(
            "SELECT COUNT(*) AS orders_today,
                    COALESCE(SUM(CASE WHEN $paid THEN o.total_payment ELSE 0 END), 0) AS sales
            FROM orders o
            WHERE o.is_deleted = 0 AND o.status <> 3
                AND DATE(o.order_date) = CURDATE()"
        )->fetch();

        $items = $this->db->query(
            "SELECT COALESCE(SUM(d.quantity), 0)
            FROM order_details d
            JOIN orders o ON o.id_order = d.id_order
            WHERE d.status = 1 AND o.is_deleted = 0 AND o.status <> 3 
                AND DATE(o.order_date) = CURDATE() AND $paid"
        )->fetchColumn();

        return [
            'orders' => (int) $row['orders_today'],
            'items' => (int) $items,
            'sales' => (float) $row['sales'],
        ];
    }

    public function recent(int $limit = 5): array {
        $limit = max(1, $limit);
        return $this->db->query(
            "SELECT customer_name, order_date, status
            FROM orders WHERE is_deleted = 0
            ORDER BY order_date DESC LIMIT $limit"
        )->fetchAll();
    }

    public function countByStatus(): array {
        $rows = $this->db->query(
            "SELECT status, COUNT(*) AS n FROM orders WHERE is_deleted = 0 GROUP BY status")->fetchAll();

        $counts = [
            self::PENDING => 0,
            self::PROCESS => 0,
            self::COMPLETED => 0,
            self::CANCELED => 0,
        ];
        foreach ($rows as $r) {
            $counts[(int) $r['status']] = (int) $r['n'];
        }
        return $counts;
    }

    private function isPaid($id): bool {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM payments WHERE id_order = ? AND payment_status = 'paid' LIMIT 1"
        );
        $stmt->execute([$id]);
        return(bool) $stmt->fetchColumn();
    }

    /** @return string 'ok' | 'unpaid' | 'unchanged' */
    public function updateStatus($id, $status, $by): string {
        if(in_array($status, [self::PROCESS, self::COMPLETED], true) && !$this->isPaid($id)) {
            return 'unpaid';
        }


        $stmt = $this->db->prepare(
            "UPDATE orders SET status = ?, last_updated_by = ? WHERE id_order = ? AND is_deleted = 0"
        );
        $stmt->execute([$status, $by, $id]);
        return $stmt->rowCount() > 0 ? 'ok' : 'unchanged';
    }

    public function markPaid(string $id, string $by): string {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "SELECT status FROM orders WHERE id_order = ? AND is_deleted = 0"
            );
            $stmt->execute([$id]);
            $order = $stmt->fetch();

            if (!$order) {
                $this->db->rollBack();
                return 'notfound';
            }
            if ((int) $order['status'] === self::CANCELED) {
                $this->db->rollBack();
                return 'canceled';
            }

            $stmt = $this->db->prepare(
                "SELECT id_payment, payment_status FROM payments
                WHERE id_order = ? ORDER BY created_date DESC LIMIT 1"
            );
            $stmt->execute([$id]);
            $pay = $stmt->fetch();

            if (!$pay) {
                $this->db->rollBack();
                return 'notfound';
            }
            if ($pay['payment_status'] === 'paid') {
                $this->db->rollBack();
                return 'already';            
            }

            $stmt = $this->db->prepare(
                "UPDATE payments
                SET payment_status = 'paid',
                    amount_paid = (SELECT total_payment FROM orders WHERE id_order = payments.id_order),
                    payment_Date = CURRENT_TIMESTAMP,
                    payment_method = CASE WHEN payment_method = 'unselected' THEN 'cash' ELSE payment_method END,
                    last_updated_by = ?
                WHERE id_payment = ? AND payment_status <> 'paid'"
            );
            $stmt->execute([$by, $pay['id_payment']]);
            $changed = $stmt->rowCount() > 0;

            $this->db->commit();
            return $changed ? 'ok' : 'already';
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function softDelete($id, $by): bool {
        $stmt = $this->db->prepare(
            "UPDATE orders SET is_deleted = 1, deleted_At = NOW(), last_updated_by = ? WHERE id_order = ? AND is_deleted = 0"
        );
        $stmt->execute([$by, $id]);
        return $stmt->rowCount() > 0;
    }
}
