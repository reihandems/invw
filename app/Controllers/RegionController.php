<?php

namespace App\Controllers;

use App\Models\ProvinceModel;
use App\Models\RegencyModel;

class RegionController extends BaseController {

    // Tampilkan form dengan daftar provinsi
    public function index() {
        return redirect()->to('admin/supplier');
    }

    // Handle request AJAX untuk mendapatkan daftar kabupaten/kota
    public function getRegencies() {
        // Pastikan ini adalah permintaan AJAX dan ada data province_id
        if ($this->request->isAJAX() && $this->request->getPost('province_id')) {
            $provinceId = $this->request->getPost('province_id');

            $regencyModel = new RegencyModel();
            $regencies = $regencyModel->getRegenciesByProvince($provinceId);

            // Mengembalikan data dalam format JSON
            return $this->response->setJSON($regencies);
        }

        // Jika bukan permintaan AJAX, kembalikan response 404 atau JSON kosong
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Invalid request']);
    }
}