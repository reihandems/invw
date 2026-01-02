<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminSupplierModel;

class PurchasingSupplier extends BaseController {
    protected $supplierModel;

    public function index() {
        $data = [
            'menu' => 'supplier',
            'pageTitle' => 'Daftar Supplier'
        ];

        return view('pages/purchasing/view_supplier', $data);
    }

    public function __construct()
    {
        $this->supplierModel = new AdminSupplierModel();
    }

    public function ajaxList() {
        $supplier = $this->supplierModel;
        $list = $supplier->findAll();
        $no = 0;
        $data = [];
        foreach ($list as $supplier) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $supplier['nama_supplier'];
            $row[] = $supplier['kontak'];
            $row[] = $supplier['alamat'];

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }
}