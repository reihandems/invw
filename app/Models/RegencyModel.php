<?php

namespace App\Models;
use CodeIgniter\Model;

class RegencyModel extends Model {
    protected $table = 'reg_regencies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['province_id', 'name'];

    // Fungsi untuk mendapatkan semua Kabupaten/Kota berdasarkan province_id
    public function getRegenciesByProvince($provinceId) {
        return $this->where('province_id', $provinceId)->findAll();
    }
}