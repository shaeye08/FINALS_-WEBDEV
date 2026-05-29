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
    <title>AssetFlow - Executive Telemetry Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?= view('partials/theme_engine') ?>
</head>
<body class="bg-body-tertiary">

<!-- Main Control Navigation Header Layout -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand mb-0 h1">🛡️ AssetFlow Command Console</span>
        <div class="d-flex align-items-center ms-auto">
            <!-- Dynamic Operator Session Telemetry -->
            <span class="navbar-text text-white me-3 small">
                👤 Welcome, <strong><?= session()->get('name') ?></strong> (Role ID: <?= session()->get('role_id') ?>)
            </span>
            <button onclick="toggleSystemTheme()" class="btn btn-outline-info btn-sm me-3 fw-bold" id="themeTogglerBtn">🌗 Toggle Mode</button>
            <span class="text-white-50 me-3 small">Cache Sync: <?= $cache_stamp ?></span>
            <a href="<?= base_url('/assets') ?>" class="btn btn-outline-light btn-sm me-2">🗃️ Open Hardware Ledger</a>
            <a href="<?= base_url('/auth/logout') ?>" class="btn btn-danger btn-sm fw-bold">🚶 Secure Logout</a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Flashdata Global Exception Feedback Array -->
    <?php if(session()->getFlashdata('error')):?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            ⚠️ <strong>System Alert:</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;?>
    
    <!-- Real-time Operational Matrix Summary Row Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title text-uppercase text-white-50 small">Total Infrastructure Nodes</h6>
                    <h2 class="fw-bold mb-0"><?= $total_count ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title text-uppercase text-white-50 small">Operational / Available</h6>
                    <h2 class="fw-bold mb-0"><?= $available ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title text-uppercase text-white-50 small">Active In-Field Service</h6>
                    <h2 class="fw-bold mb-0"><?= $in_use ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title text-uppercase text-white-50 small">Critical Maintenance Mode</h6>
                    <h2 class="fw-bold mb-0"><?= $repair ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Capital Depreciation Valuation Matrix Row -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold text-body">💵 Hardware Capital Depreciation & Asset Valuation Matrix</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 border-end">
                            <h6 class="text-uppercase text-muted small">Total Initial Invested Capital</h6>
                            <h3 class="fw-bold text-primary" id="metricInitialCost">$0.00</h3>
                        </div>
                        <div class="col-md-4 border-end">
                            <h6 class="text-uppercase text-muted small">Current Valuation (Straight-Line)</h6>
                            <h3 class="fw-bold text-success" id="metricBookSL">$0.00</h3>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-uppercase text-muted small">Current Valuation (Double-Declining Balance)</h6>
                            <h3 class="fw-bold text-warning" id="metricBookDDB">$0.00</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphical Analytical Data Matrix Area Block -->
    <div class="row mb-4">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold text-body">📊 Asset Operational State Distribution</div>
                <div class="card-body d-flex align-items-center justify-content-center" style="position: relative; height:320px;">
                    <canvas id="statusDonutChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold text-body">📈 Structural Infrastructure Allocation by Category</div>
                <div class="card-body" style="position: relative; height:320px;">
                    <canvas id="categoryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Combined Access Control Tiers Panel -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title fw-bold">🚀 Quick Management Access Tiers</h5>
            <p class="text-muted small">AssetFlow secure multi-tier infrastructure modules active.</p>
            <hr>
            <a href="<?= base_url('/assets') ?>" class="btn btn-outline-primary btn-sm me-2 fw-bold">🗃️ Hardware Ledger Base</a>
            <a href="<?= base_url('/maintenance') ?>" class="btn btn-outline-danger btn-sm fw-bold">🔧 Maintenance Desk Log</a>
        </div>
    </div>

</div>

<!-- Async Fetch Processing Engine Interface Scripts Line -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    let statusChartInstance = null;
    let categoryChartInstance = null;

    /**
     * Reads the current DOM data theme configuration state and paints
     * Chart canvas text matrices dynamically for absolute accessibility.
     */
    function buildCharts() {
        // Fire dynamic endpoint fetch query sequence
        fetch("<?= base_url('/api/analytics/overview') ?>")
            .then(response => response.json())
            .then(data => {
                const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                const textColor = isDark ? '#f8f9fa' : '#212529';
                const gridColor = isDark ? '#373b3e' : '#dee2e6';

                // Prevent memory leaking canvas overrides by scrubbing previous instances
                if(statusChartInstance) statusChartInstance.destroy();
                if(categoryChartInstance) categoryChartInstance.destroy();
                
                // 1. Compile and Render Operational State Donut Layout
                const ctxDonut = document.getElementById('statusDonutChart').getContext('2d');
                statusChartInstance = new window.Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: data.status.labels,
                        datasets: [{
                            data: data.status.values,
                            backgroundColor: ['#198754', '#0dcaf0', '#dc3545'],
                            borderColor: isDark ? '#212529' : '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { 
                                position: 'bottom',
                                labels: { color: textColor }
                            } 
                        }
                    }
                });

                // 2. Compile and Render Category Profile Allocations Bar Layout
                const ctxBar = document.getElementById('categoryBarChart').getContext('2d');
                categoryChartInstance = new window.Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: data.categories.labels,
                        datasets: [{
                            label: 'Hardware Resource Volume',
                            data: data.categories.values,
                            backgroundColor: '#0d6efd',
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { color: textColor } },
                            y: { 
                                beginAtZero: true, 
                                ticks: { stepSize: 1, color: textColor },
                                grid: { color: gridColor }
                            }
                        }
                    }
                });

            })
            .catch(error => console.error("Telemetry data link execution failure:", error));
    }

    /**
     * Polls the dynamic financial endpoint to acquire capital metrics
     * and streams them cleanly into human-readable currency formats.
     */
    function loadFinancialMetrics() {
        fetch("<?= base_url('/api/analytics/financial') ?>")
            .then(response => response.json())
            .then(data => {
                const formatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });
                
                document.getElementById('metricInitialCost').innerText = formatter.format(data.capital_metrics.initial_cost);
                document.getElementById('metricBookSL').innerText     = formatter.format(data.capital_metrics.book_value_straight_line);
                document.getElementById('metricBookDDB').innerText    = formatter.format(data.capital_metrics.book_value_accelerated);
            })
            .catch(error => console.error("Financial telemetry failure:", error));
    }

    // Run primary dashboard interface calculations initialization
    buildCharts();
    loadFinancialMetrics();

    // Attach listener hook for live UI adaptive layout repainting sweeps
    window.addEventListener('themeChanged', buildCharts);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>