<?php

namespace App\Models;

use CodeIgniter\Model;

class PRDetailModel extends Model {
    protected $DBGroup = 'default';
    protected $table = 'purchase_request_detail';
    protected $primaryKey = 'pr_detail_id';
    protected $allowedFields = ['pr_id', 'barang_id', 'qty'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}