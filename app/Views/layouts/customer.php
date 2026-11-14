<?php
/**
 * @var string $content
 */
?>

<!DOCTYPE html>
<html lang="en"<?= !empty($htmlClass) ? ' class="' . e($htmlClass) . '"' : '' ?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
	<title><?= e($title ?? 'Self Order') ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Coustard:wght@400;900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= asset('customer/css/style.css') ?>">
	<?php foreach ($styles ?? [] as $css): ?>
	<link rel="stylesheet" href="<?= asset("customer/css/$css.css") ?>">
	<?php endforeach; ?>
</head>
<body<?= !empty($bodyClass) ? ' class="' . e($bodyClass) . '"' : '' ?>>
<?= $content ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="<?= asset('customer/js/common.js') ?>"></script>
	<?php foreach ($jsData ?? [] as $name => $value): ?>
		<script>window.<?= $name ?> = <?= json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;</script>
	<?php endforeach; ?>
	<?php foreach ($scripts ?? [] as $js): ?>
		<script src="<?= asset("customer/js/$js.js") ?>"></script>
	<?php endforeach; ?>
</body>
</html>