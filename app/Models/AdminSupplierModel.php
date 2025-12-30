<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminSupplierModel extends Model {
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    protected $allowedFields = ['nama_supplier', 'kontak', 'alamat'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getTotalSupplier() {
        return $this->countAllResults();
    }
}