<?php
/** @var array $orders */
/** @var array $counts */
/** @var int|null $filter */

$statusMap = [
    0 => ['class' => 'pending',   'label' => 'Pending'],
    1 => ['class' => 'process',   'label' => 'Process'],
    2 => ['class' => 'completed', 'label' => 'Completed'],
    3 => ['class' => 'canceled',    'label' => 'Canceled'],
];

$payMap = [
    'pending'  => ['class' => 'text-warning',   'label' => 'Unpaid'],
    'paid'     => ['class' => 'text-success',   'label' => 'Paid'],
    'failed'   => ['class' => 'text-danger',    'label' => 'Failed'],
    'refunded' => ['class' => 'text-secondary', 'label' => 'Refunded'],
];

$typeMap = [
    'dine_in'   => 'Dine in',
    'take_away' => 'Take away',
];

$methodMap = [
    'bca-va' => 'BCA VA',
    'mandiri-va' => 'Mandiri VA',
    'shopeepay' => 'ShopeePay',
    'gopay' => 'Gopay',
    'dana' => 'Dana',
    'qris' => 'Qris',
    'cash' => 'Cash',
];

$filter    = $filter ?? null;
$canEdit   = can('orders.update');
$canDelete = can('orders.delete');
$totalAll  = array_sum($counts);
$cols      = 8 + ($canDelete ? 1 : 0);
?>

<div class="head-title">
    <div class="left">
        <h1>Orders</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class="bx bx-chevron-right"></i></li>
            <li><a class="active" href="#">Orders</a></li>
        </ul>
    </div>
</div>

<ul class="box-info">
    <li>
        <i class="bx bxs-bell-ring"></i>
        <span class="text">
            <h3><?= (int) ($counts[0] ?? 0) ?></h3>
            <p>New Order</p>
        </span>
    </li>
    <li>
        <i class="bx bxs-bowl-hot"></i>
        <span class="text">
            <h3><?= (int) ($counts[1] ?? 0) ?></h3>
            <p>Process</p>
        </span>
    </li>
    <li>
        <i class="bx bx-check-circle"></i>
        <span class="text">
            <h3><?= (int) ($counts[2] ?? 0) ?></h3>
            <p>Completed</p>
        </span>
    </li>
</ul>

<div class="box-filter d-flex flex-wrap gap-2" role="group" aria-label="Filter status">
    <a href="/admin/orders" class="btn btn-sm rounded-pill px-3 <?= $filter === null ? 'active' : '' ?>">
        All (<?= $totalAll ?>)
    </a>
    <?php foreach ($statusMap as $value => $s): ?>
        <a href="/admin/orders?status=<?= $value ?>"
            class="btn btn-sm rounded-pill px-3 <?= $filter === $value ? 'active' : '' ?>">
            <?= e($s['label']) ?> (<?= (int) ($counts[$value] ?? 0) ?>)
        </a>
    <?php endforeach; ?>
</div>

<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Orders</h3>
            <i class="bx bx-search"></i>
            <i class="bx bx-filter"></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order Id</th>
                    <th>Customer</th>
                    <th>Orders</th>
                    <th>Date Order</th>
                    <th>Time Order</th>
                    <th>Total</th>
                    <th>Order Type</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <?php if ($canDelete): ?><th>Action</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr class="order-list">
                        <td colspan="<?= $cols ?>" class="text-center py-4">No orders found.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($orders as $o):
                    $st  = $statusMap[(int) $o['status']] ?? $statusMap[0];
                    $pay = $payMap[$o['payment_status'] ?? 'pending'] ?? $payMap['pending'];
                    $t   = (string) ($o['order_type'] ?? '');
                ?>
                    <tr class="order-list">
                        <td>O<?= str_pad((string) $o['order_number'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><p><?= e($o['customer_name']) ?></p></td>
                        <td><p><?= e($o['items'] ?? '-') ?></p></td>
                        <td><?= e(date('d M Y', strtotime($o['order_date']))) ?></td>
                        <td><?= e(date('H:i', strtotime($o['order_date']))) ?></td>
                        <td><?= rupiah($o['total_payment']) ?></td>
                        <td><?= e($typeMap[$t] ?? ($t !== '' ? ucwords(str_replace('_', ' ', $t)) : '-')) ?></td>
                        <td>
                            <span class="<?= $pay['class'] ?> fw-semibold"><?= e($pay['label']) ?></span>
                            <?php if ($canEdit && ($o['payment_status'] ?? 'pending') === 'pending' && (int) $o['status'] !== 3): ?>
                                <form action="/admin/orders/pay" method="post" class="mt-1"
                                    onsubmit="return confirm('Confirm payment of <?= e(rupiah($o['total_payment'])) ?> has been received?')">
                                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id_order" value="<?= e($o['id_order']) ?>">
                                    <button type="submit" class="btn btn-sm btn-success py-0">Mark paid</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <?php if ($canEdit): ?>
                                <form action="/admin/orders/update" method="post">
                                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id_order" value="<?= e($o['id_order']) ?>">
                                    <select name="status" class="status-select <?= $st['class'] ?>"
                                            onchange="if (this.value === '3' && !confirm('Cancel this order?')) { this.form.reset(); return; } this.form.submit();">
                                        <?php foreach ($statusMap as $value => $s): ?>
                                            <option value="<?= $value ?>" <?= (int) $o['status'] === $value ? 'selected' : '' ?>>
                                                <?= e($s['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php else: ?>
                                <span class="status <?= $st['class'] ?>"><?= e($st['label']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($methodMap[$o['payment_method'] ?? ''] ?? '-') ?></td>
                        <?php if ($canDelete): ?>
                        <td>
                            <form action="/admin/orders/delete" method="post"
                                onsubmit="return confirm('Delete this order?')">
                                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id_order" value="<?= e($o['id_order']) ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>