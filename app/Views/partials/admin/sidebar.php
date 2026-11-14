<?php
$menus = [
	['/admin',           'bxs-dashboard',        'Dashboard'],
	['/admin/orders',    'bxs-receipt', 'Orders'],
	['/admin/store',     'bxs-shopping-bag-alt', 'My Store'],
	['/admin/analytics', 'bxs-doughnut-chart',   'Analytics'],
	['/admin/team',      'bxs-group',            'Team'],
];
?>
<section id="sidebar">
	<a href="/admin" class="brand">
		<img src="/assets/admin/img/logo.svg" class="bx bx-sm" alt="SeloApp">
		<span class="text">SeloApp</span>
	</a>
	<ul class="side-menu top">
		<?php foreach ($menus as [$url, $icon, $label]): ?>
		<li class="<?= is_active($url) ? 'active' : '' ?>">
			<a href="<?= $url ?>">
				<i class="bx <?= $icon ?> bx-sm"></i>
				<span class="text"><?= $label ?></span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<ul class="side-menu bottom">
		<li><a href="/admin/setting"><i class="bx bxs-cog bx-sm bx-spin-hover"></i><span class="text">Settings</span></a></li>
		<li><a href="/admin/login" class="logout"><i class="bx bx-power-off bx-sm bx-burst-hover"></i><span class="text">Logout</span></a></li>
	</ul>
</section>