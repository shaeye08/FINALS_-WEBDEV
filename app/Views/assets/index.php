<?php
/**
 * @var array $assets
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Asset Ledger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Hardware Inventory Ledger</h2>
        <div class="d-flex align-items-center">
            <div class="btn-group me-2">
                <a href="<?= base_url('/assets/export/excel') ?>" class="btn btn-outline-success">📊 Export Excel</a>
                <a href="<?= base_url('/assets/export/pdf_view') ?>" class="btn btn-outline-danger">📄 Export PDF Summary</a>
            </div>

            <div>
                <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">Dashboard</a>
                <a href="<?= base_url('/assets/scan') ?>" class="btn btn-dark me-2">📷 Live Camera Scan</a>

                <?php if(in_array(session()->get('role_id'), [1, 2])): ?>
                    <a href="<?= base_url('/assets/create') ?>" class="btn btn-primary">Add Asset Resource</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Asset Code</th>
                        <th>Device Identity</th>
                        <th>Category Type</th>
                        <th>Status Badge</th>
                        <th class="text-center">QR Label</th> 
                        <?php if(in_array(session()->get('role_id'), [1, 2])): ?>
                            <th>Operations</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($assets) && is_array($assets)): ?>
                        <?php foreach($assets as $item): ?>
                            <tr>
                                <td><strong><?= esc($item['asset_code']) ?></strong></td>
                                <td><?= esc($item['name']) ?></td>
                                <td><?= esc($item['category_name']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $item['status'] === 'Available' ? 'success' : ($item['status'] === 'In Use' ? 'info' : 'danger') ?>">
                                        <?= $item['status'] ?>
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <img src="<?= \App\Controllers\QrController::generateAssetTag($item['asset_code']) ?>" 
                                         alt="QR Label Code" 
                                         style="width: 50px; height: 50px;" 
                                         title="Scan for infrastructure telemetry data tracking">
                                </td>

                                <?php if(in_array(session()->get('role_id'), [1, 2])): ?>
                                <td>
                                    <a href="<?= base_url('/assets/edit/'.$item['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <?php if(session()->get('role_id') == 1): ?>
                                        <a href="<?= base_url('/assets/delete/'.$item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirm hardware unit purge?')">Delete</a>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No structural assets cataloged.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="d-flex justify-content-end mt-3">
                <?= $pager->links('default', 'bootstrap_full') ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>