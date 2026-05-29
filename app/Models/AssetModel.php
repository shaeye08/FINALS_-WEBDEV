<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'assets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['asset_code', 'name', 'category_id', 'status', 'image'];

    // Helper method to pull data with joined category tracking information
    public function getAssetsWithCategory()
    {
        return $this->select('assets.*, categories.category_name')
                    ->join('categories', 'categories.id = assets.category_id');
    }
}