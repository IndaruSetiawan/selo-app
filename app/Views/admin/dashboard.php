<?php
/** @var array $stats */
/** @var array $recent */
$statusMap = [
	0 => ['class' => 'pending',   'label' => 'Pending'],
    1 => ['class' => 'process',   'label' => 'Process'],
    2 => ['class' => 'completed', 'label' => 'Completed'],
    3 => ['class' => 'canceled',  'label' => 'Canceled'],
];

?>

<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li><a href="#">Dashboard</a></li>
						<li><i class="bx bx-chevron-right"></i></li>
						<li><a class="active" href="#">Dashboard</a></li>
					</ul>
				</div>
			</div>
			<ul class="box-info">
				<li>
					<i class='bx bxs-receipt' ></i>
					<span class="text">
						<h3><?= (int) $stats['orders'] ?></h3>
						<p>Orders Today</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-package' ></i>
					<span class="text">
						<h3><?= (int) $stats['items'] ?></h3>
						<p>Items Sold</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">
						<h3><?= e(rupiah($stats['sales'])) ?></h3>
						<p>Total Sales</p>
					</span>
				</li>
			</ul>


			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Orders</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>Customer</th>
								<th>Date Order</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($recent)): ?>
								<tr><td colspan="3" class="text-center py-3">No orders yet.</td></tr>
							<?php endif; ?>
							<?php foreach ($recent as $o):
								$st = $statusMap[(int) $o['status'] ?? $statusMap[0]];
							?>
								<tr>
									<td><p><?= e($o['customer_name']) ?></p></td>
									<td><?= e(date('d M Y', strtotime($o['order_date']))) ?></td>
									<td><span class="status <?= $st['class'] ?>"><?= e($st['label']) ?></span></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Todos</h3>
						<i class='bx bx-plus icon'></i>
						<i class='bx bx-filter' ></i>
	
					</div>
					<ul class="todo-list">
						<li class="completed">
							<p>Check Inventory</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="completed">
							<p>Manage Delivery Team</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="not-completed">
							<p>Contact Selma: Confirm Delivery</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="completed">
							<p>Update Shop Catalogue</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
						<li class="not-completed">
							<p>Count Profit Analytics</p>
							<i class='bx bx-dots-vertical-rounded' ></i>
						</li>
					</ul>
				</div>
			</div>