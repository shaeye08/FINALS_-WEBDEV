<?php
/**
 * @var int $total_count
 * @var int $available
 * @var int $in_use
 * @var int $repair
 * @var string $cache_stamp
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AssetFlow - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">AssetFlow Panel</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Welcome, <?= session()->get('name') ?> (Role ID: <?= session()->get('role_id') ?>)</span>
            <a href="<?= base_url('/auth/logout') ?>" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if(session()->getFlashdata('error')):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif;?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Operational Dashboard</h2>
        <small class="text-muted">Metrics Cache Frame Stamp: <?= esc($cache_stamp) ?></small>
    </div>
    
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-dark text-white text-center p-3 shadow-sm">
                <h3><?= $total_count ?></h3>
                <h6>Total Hardware Units</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center p-3 shadow-sm">
                <h3><?= $available ?></h3>
                <h6>Units Available</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white text-center p-3 shadow-sm">
                <h3><?= $in_use ?></h3>
                <h6>Units Allocated</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center p-3 shadow-sm">
                <h3><?= $repair ?></h3>
                <h6>Units In Shop</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Quick Management Access Tiers</h5>
            <p class="text-muted">AssetFlow secure infrastructure active.</p>
            <hr>
            <a href="<?= base_url('/assets') ?>" class="btn btn-outline-primary me-2">Hardware Ledger Base</a>
            <a href="<?= base_url('/maintenance') ?>" class="btn btn-outline-danger">Maintenance Desk Log</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>