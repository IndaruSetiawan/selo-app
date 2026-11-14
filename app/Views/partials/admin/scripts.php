<?php foreach ($vendorScripts ?? [] as $src): ?>
<script src="<?= e($src) ?>"></script>
<?php endforeach; ?>
<script src="<?= asset('admin/js/main.js') ?>"></script>
<?php foreach ($scripts ?? [] as $js): ?>
<script src="<?= asset("admin/js/$js.js") ?>"></script>
<?php endforeach; ?>
