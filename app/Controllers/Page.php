<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\SatuanModel;
use App\Models\RoleModel;

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
        $satuanModel = new SatuanModel();

        return view('pages/admin/view_barang', [
            'menu' => 'barang',
            'pageTitle' => 'Data Barang',
            'kategori' => $kategoriModel->findAll(),
            'satuan' => $satuanModel->findAll()
        ]);
    }

    public function supplierAdmin() {
        return view('pages/admin/view_supplier', [
            'menu' => 'supplier',
            'pageTitle' => 'Data Supplier'
        ]);
    }

    public function userAdmin() {
        $roleModel = new RoleModel();
        return view('pages/admin/view_user', [
            'menu' => 'user',
            'pageTitle' => 'Data User',
            'role' => $roleModel->findAll()
        ]);
    }
}