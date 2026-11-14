<?php
namespace  App\Controllers\Customer;

use App\Core\Controller;
use App\Models\CustomerOrder;
use DomainException;
use Stringable;

class OrderController extends Controller {
    private const TYPES = [
        'dine-in'   => 'dine_in',
        'take-away' => 'take_away',
    ];

    public function store(): void {
        csrf_verify_header();
        $in = request_json();

        $key =  $in['key'] ?? '';
        $name = trim((string) ($in['customer_name'] ?? ''));
        $type = self::TYPES[$in['order_type'] ?? ''] ?? null;
        $items = $in['items'] ?? [];

        if (!$this->isUuid($key)) {
            json_response(['ok' => false, 'message' => 'Request invalid.'], 422);
        }
        if ($name === '' || mb_strlen($name) > 100) {
            json_response(['ok' => false, 'message' => 'Customer name is required (Max. 100 characters).'], 422);
        }
        if ($type == null) {
            json_response(['ok' => false, 'message' => 'Choose dine in or take away.'], 422);
        }
        if (!is_array($items) || !$items || count($items) > 50 ) {
            json_response(['ok' => false, 'message' => 'The cart is empty.'], 422);
        }

        try {
            $order = (new CustomerOrder())->create($key, $name, $type, $items);
        } catch (DomainException $e) {
            json_response(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        json_response(['ok' => true, 'order' => $order]);
    }

    public function pay(): void {
        csrf_verify_header();
        $in = request_json();

        $id = $in['id_order'] ?? '';
        $method = (string) ($in['method'] ?? '');

        if (!$this->isUuid($id)) {
            json_response(['ok' => false, 'message' => 'Order invalid.'], 422);
        }
        
        try {
            $result = (new CustomerOrder())->pay($id, $method);
        } catch (DomainException $e) {
            json_response(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        if ($result === null) {
            json_response(['ok' => false, 'message' => 'Order not found.'], 404);
        }
        if ($result['canceled']) {
            json_response(['ok' => false, 'message' => 'This order has been canceled.'], 409);
        }
        json_response([
            'ok'          => true,
            'state'       => $result['paid'] ? 'paid' : 'pending',
            'paid_amount' => $result['amount'],
        ]);
    }

    public function status(): void {
        $id = $_GET['id'] ?? '';

        if (!$this->isUuid($id)) {
            json_response(['ok' => false, 'message' => 'Order invalid.'], 422);
        }

        $s = (new CustomerOrder())->paymentState($id);
        if ($s === null) {
            json_response(['ok' => false, 'message' => 'Order not found.'], 404);
        }

        json_response([
            'ok'          => true,
            'state'       => $s['canceled'] ? 'canceled' : ($s['paid'] ? 'paid' : 'pending'),
            'paid_amount' => $s['amount'],
        ]);
    }

    private function isUuid(mixed $v): bool {
    return is_string($v) && (bool) preg_match(
        '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $v
    );
}
}