<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Libraries\DepreciationEngine;

class DashboardController extends BaseController
{
    protected $assetModel;

    public function __construct()
    {
        $this->assetModel = new AssetModel();
    }

    public function index()
    {
        $cacheKey = 'dashboard_metrics_summary';

        // Check if optimized dashboard metrics are already cached
        if (!$data = cache()->get($cacheKey)) {
            // Generate clean calculation summaries
            $data = [
                'total_count' => $this->assetModel->countAllResults(),
                'available'   => $this->assetModel->where('status', 'Available')->countAllResults(),
                'in_use'      => $this->assetModel->where('status', 'In Use')->countAllResults(),
                'repair'      => $this->assetModel->where('status', 'Repair')->countAllResults(),
                'cache_stamp' => date('Y-m-d H:i:s') // Track performance lifecycle
            ];

            // Cache data engine vectors for 300 seconds (5 minutes)
            cache()->save($cacheKey, $data, 300);
        }

        return view('dashboard/index', $data);
    }

    /**
     * ====================================================================
     * 📊 ADVANCED FEATURE: JSON TELEMETRY AGGREGATION ENGINE
     * ====================================================================
     * Processes inventory groupings and streams data sets out to frontend charts.
     */
    public function getAnalyticsData()
    {
        // 1. Compile Status Metrics for the Donut Chart
        $statusCounts = [
            'Available' => $this->assetModel->where('status', 'Available')->countAllResults(),
            'In Use'    => $this->assetModel->where('status', 'In Use')->countAllResults(),
            'Repair'    => $this->assetModel->where('status', 'Repair')->countAllResults()
        ];

        // 2. Compile Category Distribution Metrics via Query Builder
        $db = \Config\Database::connect();
        $categoryData = $db->table('assets')
            ->select('categories.category_name, COUNT(assets.id) as total')
            ->join('categories', 'categories.id = assets.category_id')
            ->groupBy('assets.category_id')
            ->get()
            ->getResultArray();

        $categories = [];
        $categoryTotals = [];

        foreach ($categoryData as $row) {
            $categories[]     = $row['category_name'];
            $categoryTotals[] = (int)$row['total'];
        }

        return $this->response->setJSON([
            'status' => [
                'labels' => array_keys($statusCounts),
                'values' => array_values($statusCounts)
            ],
            'categories' => [
                'labels' => $categories,
                'values' => $categoryTotals
            ]
        ]);
    }

    /**
     * ====================================================================
     * 💵 FINANCIAL DEPRECIATION TELEMETRY PIPELINE
     * ====================================================================
     * Loops through tracking logs to compute real-time infrastructure values.
     */
    public function getFinancialAnalytics()
    {
        $assets = $this->assetModel->findAll();
        
        $totalInitialCapital = 0.00;
        $currentBookValueSL   = 0.00;
        $currentBookValueDDB  = 0.00;

        foreach ($assets as $asset) {
            $cost    = (float)($asset['purchase_cost'] ?? 0.00);
            $salvage = (float)($asset['salvage_value'] ?? 0.00);
            $life    = (int)($asset['useful_life_years'] ?? 5);
            $pDate   = $asset['purchase_date'] ?? null;

            $totalInitialCapital += $cost;

            // Execute processing formulas against the specialized math core
            $sl  = DepreciationEngine::calculateStraightLine($cost, $salvage, $life, $pDate);
            $ddb = DepreciationEngine::calculateDoubleDeclining($cost, $salvage, $life, $pDate);

            $currentBookValueSL  += $sl['current_value'];
            $currentBookValueDDB += $ddb['current_value'];
        }

        return $this->response->setJSON([
            'capital_metrics' => [
                'initial_cost'             => round($totalInitialCapital, 2),
                'book_value_straight_line' => round($currentBookValueSL, 2),
                'book_value_accelerated'   => round($currentBookValueDDB, 2),
            ]
        ]);
    }
}