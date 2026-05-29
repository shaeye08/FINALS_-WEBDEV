<?php
/**
 * @var array $logs
 * @var \CodeIgniter\Pager\Pager $pager
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Maintenance Log System</h2>
        <div>
            <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">Dashboard</a>
            <a href="<?= base_url('/maintenance/create') ?>" class="btn btn-danger">File Repair Request</a>
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
                        <th>Device Name</th>
                        <th>Issue Reported</th>
                        <th>Technician Notes</th>
                        <th>Ticket Status</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($logs) && is_array($logs)): ?>
                        <?php foreach($logs as $ticket): ?>
                            <tr>
                                <td><strong><?= esc($ticket['asset_code']) ?></strong></td>
                                <td><?= esc($ticket['asset_name']) ?></td>
                                <td><?= esc($ticket['issue']) ?></td>
                                <td><?= esc($ticket['repair_notes'] ?? 'No comments yet') ?></td>
                                <td>
                                    <span class="badge bg-<?= $ticket['status'] === 'Completed' ? 'success' : ($ticket['status'] === 'Ongoing' ? 'warning' : 'secondary') ?>">
                                        <?= $ticket['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if(in_array(session()->get('role_id'), [1, 2])): ?>
                                        <a href="<?= base_url('/maintenance/edit/'.$ticket['id']) ?>" class="btn btn-sm btn-dark">Manage Ticket</a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light" disabled>Read-only</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No active repair logs tracked.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <?= $pager->links('default', 'bootstrap_full') ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>