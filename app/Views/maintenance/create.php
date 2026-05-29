<?php
/**
 * @var array $assets
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Repair Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-danger text-white"><h5>File Maintenance Report</h5></div>
        <div class="card-body">
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach(session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/maintenance/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Select Faulty Hardware Unit</label>
                    <select name="asset_id" class="form-select" required>
                        <?php foreach($assets as $asset): ?>
                            <option value="<?= $asset['id'] ?>"><?= esc($asset['asset_code']) ?> - <?= esc($asset['name']) ?> (<?= $asset['status'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Detailed Diagnostics / Nature of Failure</label>
                    <textarea name="issue" class="form-control" rows="4" required><?= old('issue') ?></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/maintenance') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-danger">File Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>