<?php
/**
 * @var array $asset
 * @var array $categories
 */
?>
<!DOCTYPE html>
<html lang="en">
...
<head>
    <meta charset="UTF-8">
    <title>Edit Asset Matrix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark"><h5>Update Asset Specifications</h5></div>
        <div class="card-body">
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach(session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/assets/update/'.$asset['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Asset Tag/Serial Identifier</label>
                    <input type="text" name="asset_code" value="<?= esc($asset['asset_code']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Asset Common Name</label>
                    <input type="text" name="name" value="<?= esc($asset['name']) ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Infrastructure Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $asset['category_id'] ? 'selected' : '' ?>><?= esc($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Operational Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Available" <?= $asset['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="In Use" <?= $asset['status'] == 'In Use' ? 'selected' : '' ?>>In Use</option>
                        <option value="Repair" <?= $asset['status'] == 'Repair' ? 'selected' : '' ?>>Repair</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/assets') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Commit Structural Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>