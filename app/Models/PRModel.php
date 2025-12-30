<?php

namespace App\Models;

use CodeIgniter\Model;

class PRModel extends Model {
    protected $table = 'purchase_request';
    protected $primaryKey = 'pr_id';
    protected $allowedFields = ['pr_number', 'warehouse_id', 'user_id', 'request_date', 'approved_by', 'approved_at', 'notes', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = true;

    public function countWaitingApproval()
    {
        return $this->where('status', 'submitted')
                    ->countAllResults();
    }
}