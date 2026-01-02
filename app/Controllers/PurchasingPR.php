<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PRModel;

class PurchasingPR extends BaseController {
    protected $prModel;

    public function index() {
        $data = [
            'menu' => 'pr',
            'pageTitle' => 'Purchase Request (PR)'
        ];

        return view('pages/purchasing/view_pr', $data);
    }

    public function __construct()
    {
        $this->prModel = new PRModel();
    }

    public function ajaxList() {
        $pr = $this->prModel;
        $list = $pr->select('purchase_request.pr_number, warehouse.nama_gudang, users.username, purchase_request.request_date, purchase_request.status, purchase_request.approved_by, purchase_request.approved_at, purchase_request.notes')
        ->join('warehouse', 'warehouse.warehouse_id = purchase_request.warehouse_id', 'left')
        ->join('users', 'users.user_id = purchase_request.user_id', 'left')
        ->where('status', 'approved')
        ->get()
        ->getResultArray();
        $no = 0;
        $data = [];
        foreach ($list as $pr) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $pr['pr_number'];
            $row[] = $pr['nama_gudang'];
            $row[] = $pr['username'];
            $row[] = $pr['request_date'];
            $row[] = '<div class="badge badge-soft badge-success">'. $pr['status'] .'</div>';
            $row[] = $pr['approved_by'];
            $row[] = $pr['approved_at'];
            $row[] = $pr['notes'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $pr['pr_id'].'">Edit</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }
}