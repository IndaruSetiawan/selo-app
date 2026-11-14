<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Order;

class OrderController extends Controller {
    public function index(): void {
        $this->authorize('orders.read');

        $raw = $_GET['status'] ?? '';
        $filter = (is_string($raw) && ctype_digit($raw) && (int) $raw <= 3) ? (int) $raw : null;

        $order = new Order();

        $this -> render('admin/orders', [
            'title' => 'Orders',
            'styles' => ['orders'],
            'scripts' => [],
            'orders' => $order->all($filter),
            'counts' => $order->countByStatus(),
            'filter' => $filter,
        ], 'admin');
    }

    public function update(): void {
        $this->authorize('orders.update');
        csrf_verify();
        
        $id = $_POST['id_order'] ?? '';
        $status = (int) ($_POST['status'] ?? -1);

        $allowed = [Order::PENDING, Order::PROCESS, Order::COMPLETED, Order::CANCELED];

        if (!$this->isUuid($id) || !in_array($status, $allowed, true)) {
            $_SESSION['error'] = 'Invalid order data.';
        } else {
            $result = (new Order())->updateStatus($id, $status, (string) $_SESSION['staff_id']);

            if ($result === 'ok') {
                $_SESSION['success'] = 'Order status updated.';
            } elseif ($result === 'unpaid') {
                $_SESSION['error'] = 'This order has not been paid yet.';
            } else {
                $_SESSION['error'] = 'Order not founf or status unchanged';
            }
        }

        header('Location: /admin/orders');
        exit;
    }

    public function pay(): void {
        $this->authorize('orders.update');
        csrf_verify();

        $id = $_POST['id_order'] ?? '';

        if (!$this->isUuid($id)) {
            $_SESSION['error'] = 'Invalid order.';
        } else {
            $result = (new Order())->markPaid($id, (string) $_SESSION['staff_id']);

            if ($result === 'ok') {
                $_SESSION['success'] = 'Payment confirmed.';
            } elseif ($result === 'already') {
                $_SESSION['error'] = 'This order is alreade paid.';
            } elseif ($result === 'canceled') {
                $_SESSION['error'] = 'A canceled order cannot be paid.';
            } else {
                $_SESSION['error'] = 'Order not found.';
            }
        }

        header('Location: /admin/orders');
        exit;
    }

    public function delete(): void {
        $this->authorize('orders.delete');
        csrf_verify();

        $id = $_POST['id_order'] ?? '';

        if (!$this->isUuid($id)) {
            $_SESSION['error'] = 'Invalid order.';
        } elseif ((new Order())->softDelete($id, (string) $_SESSION['staff_id'])) {
            $_SESSION['success'] = 'Order deleted.';
        } else {
            $_SESSION['error'] = 'Order not found.';
        }

        header('Location: /admin/orders');
        exit;
    }

    private function isUuid(mixed $v): bool {
        return is_string($v) && (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $v
        );
    }
}