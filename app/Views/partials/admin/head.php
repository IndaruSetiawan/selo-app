<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='stylesheet'>
	<link rel="stylesheet" href="/assets/admin/css/base/main.css">
	<link rel="stylesheet" href="/assets/admin/css/layout/layout.css">

    <?php foreach($styles ?? [] as $css): ?>
    <link rel="stylesheet" href="<?= asset("admin/css/pages/$css.css") ?> ">
    <?php endforeach; ?>

</head>