<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceLogModel extends Model
{
    protected $table            = 'maintenance_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['asset_id', 'issue', 'repair_notes', 'status'];

    // Join method to see which asset name belongs to the ticket
    public function getLogsWithAssets()
    {
        return $this->select('maintenance_logs.*, assets.name as asset_name, assets.asset_code')
                    ->join('assets', 'assets.id = maintenance_logs.asset_id');
    }
}