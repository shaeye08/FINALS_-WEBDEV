<?php
/**
 * @var array $assets
 * @var string $generation_time
 */
?>
<!DOCTYPE html>
<html lang="en">
...
<head>
    <meta charset="UTF-8">
    <title>AssetFlow - Infrastructure Audit Ledger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; color: #000; font-size: 13px; }
        /* Core Media Print Rule Mapping overrides standard desktop browser displays */
        @media print {
            .no-print { display: none !important; }
            @page { margin: 1.5cm; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light border rounded">
        <div>
            <h4 class="mb-0 text-primary">📄 Document Print Node Interface</h4>
            <small class="text-muted">Review data structural parameters before saving system records.</small>
        </div>
        <div>
            <button onclick="window.print();" class="btn btn-success fw-bold">🖨️ Execute PDF/Print Save</button>
            <a href="<?= base_url('/assets') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </div>

    <div class="row mb-4 border-bottom pb-3">
        <div class="col-6">
            <h2 class="fw-bold text-dark m-0">AssetFlow Core</h2>
            <p class="text-muted">Enterprise Hardware Infrastructure Asset Ledger</p>
        </div>
        <div class="col-6 text-end">
            <p class="mb-1"><strong>Report Classification:</strong> Internal Security Audit Summary</p>
            <p class="mb-1"><strong>Compiled On:</strong> <?= $generation_time ?></p>
            <p class="mb-0"><strong>Status Level:</strong> Operational / Live Database Sync</p>
        </div>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Asset Code</th>
                <th>Device Identity Name</th>
                <th>Category Classification</th>
                <th>Status State</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assets as $item): ?>
                <tr>
                    <td><code class="text-dark fw-bold"><?= esc($item['asset_code']) ?></code></td>
                    <td><?= esc($item['name']) ?></td>
                    <td><?= esc($item['category_name']) ?></td>
                    <td>
                        <span class="badge border text-dark border-secondary">
                            <?= esc($item['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row mt-5 pt-4 border-top">
        <div class="col-4 text-center">
            <div style="border-top: 1px dashed #6c757d; width: 80%; margin: 0 auto;" class="mt-4"></div>
            <small class="text-muted">Operations Manager Endorsement</small>
        </div>
        <div class="col-4"></div>
        <div class="col-4 text-center">
            <div style="border-top: 1px dashed #6c757d; width: 80%; margin: 0 auto;" class="mt-4"></div>
            <small class="text-muted">Compliance Security Officer</small>
        </div>
    </div>
</div>
</body>
</html>