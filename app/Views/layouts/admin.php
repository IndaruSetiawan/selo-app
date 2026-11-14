<!DOCTYPE html>
<html lang="en">
<?php partial('admin/head', get_defined_vars()); ?>
<body>
    <?php partial('admin/sidebar'); ?>

    <section id="content">
        <?php partial('admin/navbar') ?>

        <main>
            <?= $content ?>
        </main>
    </section>

    <?php foreach ($bodyEnd ?? [] as $extra) partial($extra); ?>
    <?php partial('admin/scripts', get_defined_vars()); ?>
    <?php partial('flash'); ?>
</body>
</html>