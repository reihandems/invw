<?php

namespace App\Models;
use CodeIgniter\Model;

class AdminGudangModel extends Model {
    protected $table = 'warehouse';
    protected $primaryKey = 'warehouse_id';
    protected $allowedFields = ['nama_gudang', 'lokasi'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}