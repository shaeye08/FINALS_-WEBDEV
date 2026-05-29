<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ====================================================================
// 🚪 ROOT REDIRECTION GATEWAY
// ====================================================================
// Redirects any traffic hitting http://localhost:8080/ cleanly to the login page
$routes->get('/', function() {
    return redirect()->to('/auth/login');
});

// ====================================================================
// 🔓 PUBLIC / AUTHENTICATION ROUTES
// ====================================================================
$routes->get('auth/login', '\App\Controllers\Auth\LoginController::index');
$routes->post('auth/loginAuth', '\App\Controllers\Auth\LoginController::loginAuth');
$routes->get('auth/logout', '\App\Controllers\Auth\LoginController::logout');

// ====================================================================
// 🔒 PROTECTED WEB ROUTES (Requires User Authentication Session)
// ====================================================================
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Optimized Dashboard Matrix Entry
    $routes->get('dashboard', '\App\Controllers\DashboardController::index');

    // 🛡️ SuperAdmin Only Global Settings Section
    $routes->group('admin', ['filter' => 'role:SuperAdmin'], function($routes) {
        $routes->get('settings', function() {
            return "<h3>SuperAdmin Core Configuration Clearances Active.</h3>";
        });
    });

    // 🛡️ Management Shared Core Analytics Section
    $routes->group('management', ['filter' => 'role:SuperAdmin,Manager'], function($routes) {
        $routes->get('analytics', function() {
            return "<h3>Management and Administrative Analytics Panel.</h3>";
        });
    });

    // 📦 Asset Core Management Block
    $routes->get('assets', '\App\Controllers\AssetController::index');
    
    $routes->group('assets', ['filter' => 'role:SuperAdmin,Manager'], function($routes) {
        $routes->get('create', '\App\Controllers\AssetController::create');
        $routes->post('store', '\App\Controllers\AssetController::store');
        $routes->get('edit/(:num)', '\App\Controllers\AssetController::edit/$1');
        $routes->post('update/(:num)', '\App\Controllers\AssetController::update/$1');
    });

    $routes->group('assets', ['filter' => 'role:SuperAdmin'], function($routes) {
        $routes->get('delete/(:num)', '\App\Controllers\AssetController::delete/$1');
    });

    // 🔧 Maintenance Pipeline Sub-Module Block
    $routes->get('maintenance', '\App\Controllers\MaintenanceController::index');
    $routes->get('maintenance/create', '\App\Controllers\MaintenanceController::create');
    $routes->post('maintenance/store', '\App\Controllers\MaintenanceController::store');

    $routes->group('maintenance', ['filter' => 'role:SuperAdmin,Manager'], function($routes) {
        $routes->get('edit/(:num)', '\App\Controllers\MaintenanceController::edit/$1');
        $routes->post('update/(:num)', '\App\Controllers\MaintenanceController::update/$1');
    });

    // Public QR Scan Asset Landing Target
    $routes->get('assets/view_tag/(:any)', '\App\Controllers\AssetController::viewTag/$1');

    // Barcode Continuous Scanning Lookup Router
    $routes->get('assets/lookup/(:any)', '\App\Controllers\AssetController::lookupBarcode/$1');
    $routes->get('assets/scan', '\App\Controllers\AssetController::scan');

    // Advanced Feature Tier 5: Reporting Infrastructure Engine
    $routes->get('assets/export/excel', '\App\Controllers\AssetController::exportExcel');
    $routes->get('assets/export/pdf_view', '\App\Controllers\AssetController::exportPdfView');

    // Advanced Feature Tier 6 & 8: Secure Dashboard Telemetry Pipelines
    $routes->get('api/analytics/overview', '\App\Controllers\DashboardController::getAnalyticsData');
    $routes->get('api/analytics/financial', '\App\Controllers\DashboardController::getFinancialAnalytics');
});

// ====================================================================
// 🔌 DECOUPLED API CORE PLATFORM SERVICES
// ====================================================================
$routes->group('api', ['namespace' => 'App\Controllers\API', 'filter' => 'apiAuth'], function($routes) {
    $routes->get('assets', 'AssetApiController::index');
    $routes->get('assets/available', 'AssetApiController::checkAvailable');
    $routes->get('assets/(:num)', 'AssetApiController::show/$1');
    $routes->get('stocks', 'AssetApiController::stockSummary');
    $routes->post('maintenance', 'AssetApiController::createMaintenanceLog');
});