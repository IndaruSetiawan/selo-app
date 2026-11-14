<?php
/**
 * @var bool   $canWrite
 * @var array  $menus
 * @var array  $categories
 */
?>

<div class="head-title">
	<div class="left">
		<h1>My Store</h1>
		<ul class="breadcrumb">
			<li><a href="#">Dashboard</a></li>
			<li><i class="bx bx-chevron-right"></i></li>
			<li><a class="active" href="#">My Store</a></li>
		</ul>
	</div>
	<?php if ($canWrite): ?>
	<button type="button" class="btn-download" id="btn-add">
		<i class="bx bx-plus"></i>
		<span class="text">Add Menu</span>
	</button>
	<?php endif; ?>
</div>
<div class="table-data">
	<div class="order">
		<table class="menu-table">
			<thead>
				<tr>
					<th>Menu Id</th>
					<th>Name</th>
					<th>Category</th>
					<th>Picture</th>
					<th>Description</th>
					<th>Cost</th>
					<th>Price</th>
					<?php if ($canWrite): ?>
						<th>Action</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody id="menu-body">
				<?php foreach ($menus as $m): ?>
					<tr data-search="<?= e(mb_strtolower($m['menu_name'] . ' ' . ($categories[$m['category']] ?? $m['category']))) ?>">
						<td class="left"><?= e(menu_code($m['id_menu'])) ?></td>
						<td><?= e($m['menu_name']) ?></td>
						<td><?= e($categories[$m['category']] ?? $m['category']) ?></td>
						<td><img class="thumb" src="<?= e(menu_image_url($m['image'])) ?>" alt="<?= e($m['menu_name']) ?>"></td>
						<td><p class="desc mb-0"><?= e($m['description']) ?></p></td>
						<td><?= e(rupiah($m['cost'])) ?></td>
						<td><?= e(rupiah($m['price'])) ?></td>
						<?php if ($canWrite): ?>
							<td>
								<div class="d-flex flex-column gap-1">
									<button type="button" class="btn-mint" data-action="edit"
										data-id="<?= (int) $m['id_menu'] ?>"
										data-name="<?= e($m['menu_name']) ?>"
										data-category="<?= e($m['category']) ?>"
										data-desc="<?= e($m['description']) ?>"
										data-cost="<?= (int) $m['cost'] ?>"
										data-price="<?= (int) $m['price'] ?>"
										data-img="<?= e($m['image'] ? basename($m['image']) : '') ?>">Edit</button>
									<button type="button" class="btn-pink" data-action="delete" data-id="<?= (int) $m['id_menu'] ?>">Delete</button>
								</div>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p id="empty" class="<?= $menus ? 'd-none' : '' ?> text-center py-4"><?= $menus ? 'Menu not found.' : 'No menu yet.' ?></p>
	</div>
</div>