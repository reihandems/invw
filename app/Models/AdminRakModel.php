<?php

namespace App\Models;
use CodeIgniter\Model;

class AdminRakModel extends Model {
    protected $table = 'warehouse_rack';
    protected $primaryKey = 'rack_id';
    protected $allowedFields = ['warehouse_id', 'kode_rak', 'deskripsi'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}