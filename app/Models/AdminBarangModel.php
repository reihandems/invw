<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminBarangModel extends Model {
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';
    protected $allowedFields = ['nama_barang', 'satuan', 'harga', 'kategori_id'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}