<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Models\PRModel;
use App\Models\PRDetailModel;
use App\Models\AdminBarangModel;
use App\Models\ProductStockLocationModel;
use App\Models\KategoriModel;
use App\Models\ActivityLogModel;
use Config\Database;

class ManagerPR extends BaseController {
    protected $prModel;
    protected $barangModel;
    protected $productStockLocation;
    protected $kategoriModel;
    protected $prDetailModel;
    protected $activityLog;
    protected $db;

    public function __construct()
    {
        $this->prModel = new PRModel();
        $this->barangModel = new AdminBarangModel();
        $this->productStockLocation = new ProductStockLocationModel();
        $this->kategoriModel = new KategoriModel();
        $this->prDetailModel = new PRDetailModel();
        $this->activityLog = new ActivityLogModel();
        $this->db = Database::connect();
    }

    public function index() {
        $data = [
            'menu' => 'pr',
            'pageTitle' => 'Purchase Request (PR)',
            'pr' => $this->prModel->getSubmittedPR()
        ];

        return view('pages/manager/view_pr', $data);
    }

    public function ajaxList() {
        $pr = $this->prModel;
        $list = $pr->getSubmittedPR();

        $no = 0;
        $data = [];
        foreach ($list as $pr) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $pr['pr_number'];
            $row[] = $pr['nama_gudang'];
            $row[] = $pr['created_by'];
            $row[] = $pr['created_at'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" onclick="openDetailPR('.$pr['pr_id'].')" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $pr['pr_id'].'">Review</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function detail($prId){
        $header = $this->prModel->getDetailPR($prId);
        $items  = $this->prModel->getDetailItems($prId);

        return $this->response->setJSON([
            'status' => true,
            'header' => $header,
            'items'  => $items
        ]);
    }

    public function approve(){
        $prId = $this->request->getPost('pr_id');

        $this->db->transBegin();

        try {
            $this->prModel->approvePR($prId, session('user_id'));

            $this->activityLog->insert([
                'user_id'         => session('user_id'),
                'role'            => session('user_role'),
                'activity_type'   => 'APPROVE_PR',
                'reference_table' => 'purchase_request',
                'reference_id'    => $prId,
                'description'     => 'Menyetujui Purchase Request',
            ]);

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => true,
                'message' => 'PR berhasil disetujui'
            ]);

        } catch (\Throwable $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reject(){
        $prId = $this->request->getPost('pr_id');

        $this->prModel->rejectPR($prId);

        $this->activityLog->insert([
            'user_id'         => session('user_id'),
            'role'            => session('user_role'),
            'activity_type'   => 'REJECT_PR',
            'reference_table' => 'purchase_request',
            'reference_id'    => $prId,
            'description'     => 'Menolak Purchase Request',
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'PR ditolak'
        ]);
    }


}