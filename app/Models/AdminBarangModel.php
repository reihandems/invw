<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminBarangModel extends Model {
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';
    protected $allowedFields = ['namaBarang', 'hargaBarang'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}