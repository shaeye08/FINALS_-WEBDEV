<?php
/**
 * @var array $log
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Process Maintenance Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-dark text-white"><h5>Process Ticket #<?= $log['id'] ?></h5></div>
        <div class="card-body">
            <p><strong>Device:</strong> <?= esc($log['asset_name']) ?> (<?= esc($log['asset_code']) ?>)</p>
            <p><strong>Issue Logged:</strong> <?= esc($log['issue']) ?></p>
            <hr>
            <form action="<?= base_url('/maintenance/update/'.$log['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Technician Actions / Resolutions Notes</label>
                    <textarea name="repair_notes" class="form-control" rows="3"><?= esc($log['repair_notes'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Update Ticket Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Pending" <?= $log['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Ongoing" <?= $log['status'] === 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="Completed" <?= $log['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('/maintenance') ?>" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Update Ticket State</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>