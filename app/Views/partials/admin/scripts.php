<?php foreach ($vendorScripts ?? [] as $src): ?>
<script src="<?= e($src) ?>"></script>
<?php endforeach; ?>
<?php foreach ($jsData ?? [] as $name => $value): ?>
<script>window.<?= $name ?> = <?= json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;</script>
<?php endforeach; ?>
<script src="<?= asset('admin/js/main.js') ?>"></script>
<?php foreach ($scripts ?? [] as $js): ?>
<script src="<?= asset("admin/js/$js.js") ?>"></script>
<?php endforeach; ?>
