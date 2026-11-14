<?php
namespace App\Models;

use App\Core\Database;
use DomainException;
use PDO;
use PDOException;

class CustomerOrder {
    public const TAX_RATE = 0.11;
    public const MAX_QTY = 99;

    public const  METHODS = [
        'bca-va' => 'BCA Virtual Account',
        'mandiri-va' => 'Mandiri Virtual Account',
        'shopeepay' => 'ShopeePay',
        'gopay' => 'Gopay',
        'dana' => 'Dana',
        'qris' => 'Qris',
        'cash' => 'Cash',
    ];

    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

        public function create(string $key, string $name, string $type, array $items): array {
        if ($existing = $this->findByKey($key)) {
            return $existing;
        }

        $qtyById = [];
        $notesById = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                throw new DomainException('Item invalid.');
            }
            $id = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
            $qty = filter_var($item['qty'] ?? null, FILTER_VALIDATE_INT);
            if ($id === false || $id < 1 || $qty === false || $qty < 1) {
                throw new DomainException('Item invalid.');
            }
            $qtyById[$id] = ($qtyById[$id] ?? 0) + $qty;
            if ($qtyById[$id] > self::MAX_QTY) {
                throw new DomainException('Maximum quantity per menu item ' . self::MAX_QTY . '.');
            }
            $note = trim((string) ($item['notes'] ?? ''));
            if ($note !== '') {
                $notesById[$id] = mb_substr($note, 0, 255);
            }
        }
        if (!$qtyById) {
            throw new DomainException('The cart is still empty.');
        }

        $ids = array_keys($qtyById);
        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare(
            "SELECT id_menu, menu_name, price FROM menu
            WHERE id_menu IN ($in) AND is_deleted = 0 AND status = 1"
        );
        $stmt->execute($ids);
        $menus = [];
        foreach ($stmt->fetchAll() as $menu) {
            $menus[(int) $menu['id_menu']] = $menu;
        }
        if (count($menus) !== count($ids)) {
            throw new DomainException('Some menu items are no longer available. Please reload the page.');
        }

        $subtotal = 0;
        $lines = [];
        foreach ($qtyById as $id => $qty) {
            $price = (int) round((float) $menus[$id]['price']);
            $line = $price * $qty;
            $subtotal += $line;
            $lines[] = [$id, $menus[$id]['menu_name'], $qty, $price, $line, $notesById[$id] ?? null];
        }
        $tax = (int) round($subtotal * self::TAX_RATE);
        $discount = 0;
        $total = $subtotal + $tax - $discount;

        $this->db->beginTransaction();
        try {
            $idOrder = (string) $this->db->query('SELECT UUID()')->fetchColumn();

            $this->db->prepare(
                "INSERT INTO orders
                    (id_order, idempotency_key, customer_name, order_type, subtotal, tax, discount, total_payment, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)"
            )->execute([$idOrder, $key, $name, $type, $subtotal, $tax, $discount, $total]);

            $ins = $this->db->prepare(
                "INSERT INTO order_details
                    (id_order, id_menu, menu_name, quantity, unit_price, item_subtotal, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            foreach ($lines as [$idMenu, $menuName, $qty, $price, $line, $note]) {
                $ins->execute([$idOrder, $idMenu, $menuName, $qty, $price, $line, $note]);
            }

            $this->db->prepare(
                "INSERT INTO payments
                    (id_order, payment_method, amount_paid, payment_status)
                VALUES (?, 'unselected', 0, 'pending')"
            )->execute([$idOrder]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            if ($e->getCode() === '23000' && ($existing = $this->findByKey($key))) {
                return $existing;
            }
            throw $e;
        }

        return $this->findByKey($key);
    }

    public function pay(string $idOrder, string $methodSlug): ?array {
    if (!isset(self::METHODS[$methodSlug])) {
        throw new DomainException('Payment Method invalid.');
    }

    // hanya mencatat metode; status tetap 'pending' sampai kasir mengonfirmasi
    $stmt = $this->db->prepare(
        "UPDATE payments p
        JOIN orders o ON o.id_order = p.id_order
        SET p.payment_method = ?
        WHERE p.id_order = ? AND p.payment_status = 'pending'
        AND o.is_deleted = 0 AND o.status <> 3"
    );
    $stmt->execute([$methodSlug, $idOrder]);

    return $this->paymentState($idOrder);
}

public function paymentState(string $idOrder): ?array {
    $stmt = $this->db->prepare(
        "SELECT p.payment_status, o.total_payment, o.status
        FROM orders o JOIN payments p ON p.id_order = o.id_order
        WHERE o.id_order = ? AND o.is_deleted = 0
        ORDER BY p.created_date DESC LIMIT 1"
    );
    $stmt->execute([$idOrder]);
    $r = $stmt->fetch();

    if (!$r) {
        return null;
    }
    return [
        'paid'     => $r['payment_status'] === 'paid',
        'canceled' => (int) $r['status'] === 3,
        'amount'   => (int) round((float) $r['total_payment']),
    ];
}

    private function findByKey(string $key): ?array {
        $stmt = $this->db->prepare(
            "SELECT id_order, order_number, customer_name, order_type,
                DATE_FORMAT(order_date, '%a, %b %e %Y, %h:%i %p') AS order_date_fmt,
                subtotal, tax, discount, total_payment
            FROM orders WHERE idempotency_key = ? AND is_deleted = 0 LIMIT 1"
        );
        $stmt->execute([$key]);
        $o = $stmt->fetch();
        if (!$o) {
            return null;
        }
        return [
            'id'    => $o['id_order'],
            'number'    => (int) $o['order_number'],
            'code'  => '#' . str_pad((string) $o['order_number'], 4, '0', STR_PAD_LEFT),
            'customer'  => $o['customer_name'],
            'type'  => $o['order_type'],
            'date'  => $o['order_date_fmt'],
            'totals'    => [
                'subtotal'  => (int) round((float) $o['subtotal']),
                'tax'  => (int) round((float) $o['tax']),
                'discount'  => (int) round((float) $o['discount']),
                'total'  => (int) round((float) $o['total_payment']),
                ],
            ];
    }
}
