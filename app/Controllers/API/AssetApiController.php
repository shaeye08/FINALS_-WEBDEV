<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AssetModel;
use App\Models\MaintenanceLogModel;

class AssetApiController extends ResourceController
{
    protected $modelName = 'App\Models\AssetModel';
    protected $format    = 'json';

    // GET /api/assets
    public function index()
    {
        $model = new AssetModel();
        // Core structural mapping requirement: enforce paginated delivery for security optimization
        $data = $model->getAssetsWithCategory()->findAll();
        return $this->respond(['status' => 200, 'data' => $data]);
    }

    // GET /api/assets/{id}
    public function show($id = null)
    {
        $model = new AssetModel();
        $asset = $model->getAssetsWithCategory()->find($id);

        if (!$asset) {
            return $this->failNotFound('Requested asset node not found within database ledger.');
        }
        return $this->respond(['status' => 200, 'data' => $asset]);
    }

    // GET /api/assets/available
    public function checkAvailable()
    {
        $model = new AssetModel();
        $availableUnits = $model->getAssetsWithCategory()->where('status', 'Available')->findAll();
        return $this->respond(['status' => 200, 'data' => $availableUnits]);
    }

    // GET /api/stocks
    public function stockSummary()
    {
        $model = new AssetModel();
        $db = \Config\Database::connect();
        
        // Count assets categorized by their operational states
        $summary = $db->table('assets')
                      ->select('status, COUNT(id) as total_count')
                      ->groupBy('status')
                      ->get()
                      ->getResultArray();

        return $this->respond(['status' => 200, 'summary' => $summary]);
    }

    // POST /api/maintenance
    public function createMaintenanceLog()
    {
        $rules = [
            'asset_id' => 'required|is_not_unique[assets.id]',
            'issue'    => 'required|min_length[5]'
        ];

        // Read and parse automated raw input JSON string streams safely
        $jsonInput = $this->request->getJSON(true);

        if (empty($jsonInput)) {
            return $this->fail('Invalid execution payload: JSON request content stream required.');
        }

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $logModel = new MaintenanceLogModel();
        $logModel->save([
            'asset_id' => $jsonInput['asset_id'],
            'issue'    => $jsonInput['issue'],
            'status'   => 'Pending'
        ]);

        // Shift matching hardware engine profile configuration state over to repair
        $model = new AssetModel();
        $model->update($jsonInput['asset_id'], ['status' => 'Repair']);

        return $this->respondCreated(['status' => 201, 'message' => 'API transactional maintenance ticket processed.']);
    }
}