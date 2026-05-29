<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'operator_name', 'action', 'target_item', 'details', 'ip_address', 'created_at'];
    protected $useTimestamps    = false; // Handled manually at precision entry point
}