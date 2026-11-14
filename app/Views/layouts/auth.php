<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Login') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('admin/css/pages/login.css') ?>" >
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <?= $content ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <?php
            $flash = null;
            if (!empty($_SESSION['error'])) {
                $flash = ['icon' => 'error', 'title' => 'Oops...', 'text' => $_SESSION['error']];
            } elseif (!empty($_SESSION['success'])) {
                $flash = ['icon' => 'success', 'title' => 'Success', 'text' => $_SESSION['success']];
            }
            unset($_SESSION['error'], $_SESSION['success']);
        ?>
        <?php if ($flash): ?>
            <script>
                Swal.fire(<?= json_encode($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
            </script>
        <?php endif; ?>
        <script src="<?= asset('admin/js/login.js') ?>"></script>
</body>
</html>