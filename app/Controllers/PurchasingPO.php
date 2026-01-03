<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\POModel;

class PurchasingPO extends BaseController {
    protected $poModel;

    public function index() {
        $data = [
            'menu' => 'po',
            'pageTitle' => 'Purchase Order (PO)'
        ];

        return view('pages/purchasing/view_po', $data);
    }

    public function __construct()
    {
        $this->poModel = new POModel();
    }

    public function ajaxList() {
        $po = $this->poModel;
        $list = $po->getAllPO();
        $no = 0;
        $data = [];
        foreach ($list as $po) {
            $no++;
            $row = [];
            $row[] = $po['po_number'];
            $row[] = $po['nama_supplier'];
            $row[] = $po['status'];
            $row[] = $po['created_at'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $po['po_id'].'">Detail</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }
}