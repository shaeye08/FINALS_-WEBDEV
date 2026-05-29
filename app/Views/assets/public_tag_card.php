<?php
/**
 * @var array $asset
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
...
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetFlow - Node Tracker Signature</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center vh-100 p-3">
    <div class="card bg-secondary text-white shadow-lg w-100" style="max-width: 450px;">
        <div class="card-header bg-primary text-center">
            <h5 class="mb-0">AssetFlow Field System Scanner</h5>
        </div>
        <div class="card-body text-center">
            <div class="badge bg-dark fs-6 mb-3"><?= esc($asset['asset_code']) ?></div>
            <h3><?= esc($asset['name']) ?></h3>
            <p class="text-light opacity-75">Category Element: <strong><?= esc($asset['category_name']) ?></strong></p>
            <hr class="bg-light">
            
            <h5 class="mt-3">Operational Security Status:</h5>
            <?php if($asset['status'] === 'Available'): ?>
                <span class="badge bg-success fs-4 px-4 py-2">✅ Operational / Available</span>
            <?php elseif($asset['status'] === 'In Use'): ?>
                <span class="badge bg-info fs-4 px-4 py-2">💼 Allocated / In Use</span>
            <?php else: ?>
                <span class="badge bg-danger fs-4 px-4 py-2">⚠️ System Down / Repair</span>
            <?php endif; ?>
        </div>
        <div class="card-footer text-center bg-dark text-muted py-2 small">
            AssetFlow Secure Core Infrastructure Matrix • <?= date('Y') ?>
        </div>
    </div>
</body>
</html>