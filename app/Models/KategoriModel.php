<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model {
    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    protected $allowedFields = ['nama_kategori', 'deskripsi', 'status'];
    protected $returnType = 'array';
}