<?php

namespace App\Models;

use CodeIgniter\Model;

class SatuanModel extends Model {
    protected $table = 'satuan';
    protected $primaryKey = 'satuan_id';
    protected $allowedFields = ['nama_satuan'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}