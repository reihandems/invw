<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
    <link href="<?= base_url('resources/css/custom.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/dataTables.dataTables.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/datatables/responsive.dataTables.min.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="grid grid-cols-12 min-h-screen">
        <!-- Sidebar -->
        <?= $this->include('layout/sidebar/manager/sidebar', ['menu' => $menu]) ?>
        <!-- Sidebar end -->
        <!-- Kanan -->
        <div class="col-span-12 md:col-span-9 min-h-screen px-8 py-2 md:py-8" style="background-color: var(--background-color);">
            <?= $this->include('layout/header') ?>
            <hr class="my-4" style="color: var(--secondary-stroke);">
            <?= $this->renderSection('content') ?>
        </div>
        <!-- Kanan end -->
    </div>
    <script src="<?= base_url('js/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/datatables/responsive.dataTables.min.js') ?>"></script>
    <!-- <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>