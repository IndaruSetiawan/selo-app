<?php
$flash = null;

if (!empty($_SESSION['error'])) {
    $flash = ['icon' => 'error', 'title' => 'Oops...', 'text' => $_SESSION['error']];
} elseif (!empty($_SESSION['success'])) {
    $flash = ['icon' => 'success', 'title' => 'Success', 'text' => $_SESSION['success']];
}
unset($_SESSION['error'], $_SESSION['success']);

if ($flash): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire(<?= json_encode($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
</script>
<?php endif; ?>