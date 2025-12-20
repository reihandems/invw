<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\ProvinceModel;

class Page extends BaseController {
    // LOGIN
    public function login() {
        return view('auth/login');
    }

    // ADMIN
    public function dashboardAdmin() {
        return view('pages/admin/view_dashboard', [
            'menu' => 'dashboard',
            'pageTitle' => 'Dashboard'
        ]);
    }

    public function barangAdmin() {
        $kategoriModel = new KategoriModel();

        return view('pages/admin/view_barang', [
            'menu' => 'barang',
            'pageTitle' => 'Data Barang',
            'kategori' => $kategoriModel->findAll()
        ]);
    }

    public function supplierAdmin() {
        $provinces = new ProvinceModel();

        return view('pages/admin/view_supplier', [
            'menu' => 'supplier',
            'pageTitle' => 'Data Supplier',
            'provinces' => $provinces->findAll()
        ]);
    }
}