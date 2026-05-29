<?php
/**
 * @var array $categories
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white"><h5>Asset Ingestion Form</h5></div>
        <div class="card-body">
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach(session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/assets/store') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label">Asset Tag/Serial Identifier</label>
                    <input type="text" name="asset_code" value="<?= old('asset_code') ?>" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Asset Common Name</label>
                    <input type="text" name="name" value="<?= old('name') ?>" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Infrastructure Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Initial Operational Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Available">Available</option>
                        <option value="In Use">In Use</option>
                        <option value="Repair">Repair</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Hardware Device Image Upload (.jpg, .jpeg, .png)</label>
                    <input type="file" name="asset_image" class="form-control">
                    <div class="form-text">Images will be resized down to 300px and optimized automatically.</div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/assets') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Configuration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>