<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminBarangModel extends Model {
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';
    protected $allowedFields = ['nama_barang', 'satuan_id', 'harga', 'kategori_id'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getTotalBarang() {
        return $this->countAllResults();
    }

    public function getBarangByKategori($kategoriId) {
        return $this->where('kategori_id', $kategoriId)->findAll();
    }
}