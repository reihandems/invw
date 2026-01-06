<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PRModel;
use App\Models\POModel;
use App\Models\PODetailModel;
use App\Models\ActivityLogModel;
use App\Models\AdminSupplierModel;
use Config\Database;

class PurchasingPR extends BaseController {
    protected $prModel;
    protected $poModel;
    protected $poDetailModel;
    protected $activityLog;
    protected $supplierModel;
    protected $db;

    public function index() {
        $data = [
            'menu' => 'purchasing',
            'pageTitle' => 'Purchase Request (PR)'
        ];

        return view('pages/purchasing/view_pr', $data);
    }

    public function __construct()
    {
        $this->prModel = new PRModel();
        $this->poModel = new POModel();
        $this->poDetailModel = new PODetailModel();
        $this->activityLog = new ActivityLogModel();
        $this->supplierModel = new AdminSupplierModel();
        $this->db = Database::connect();
    }

    public function ajaxList() {
        $pr = $this->prModel;
        $list = $pr->getApprovedWithoutPO();

        $no = 0;
        $data = [];
        foreach ($list as $pr) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $pr['pr_number'];
            $row[] = $pr['nama_gudang'];
            $row[] = $pr['approved_at'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn btnGeneratePO" data-id="'. $pr['pr_id'].'">Buat PO</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function detail($prId){
        if (!$prId) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'PR ID tidak ditemukan'
            ]);
        }

        $header = $this->prModel->getDetailPRForPO($prId);
        if (!$header) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data PR tidak ditemukan'
            ]);
        }

        $items = $this->prModel->getDetailItemsForPO($prId);
        $suppliers = $this->supplierModel->findAll();

        return $this->response->setJSON([
            'status' => true,
            'header' => $header,
            'items'  => $items,
            'suppliers' => $suppliers
        ]);
    }

    public function generatePO(){
        $prId       = $this->request->getPost('pr_id');
        $supplierId = $this->request->getPost('supplier_id');
        $barangIds  = $this->request->getPost('barang_id');
        $qtys       = $this->request->getPost('qty');
        $hargas     = $this->request->getPost('harga');

        // =====================
        // VALIDASI DASAR
        // =====================
        if (
            empty($prId) || empty($supplierId) ||
            empty($barangIds) || empty($qtys) || empty($hargas)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data tidak lengkap'
            ]);
        }

        if (
            count($barangIds) !== count($qtys) ||
            count($barangIds) !== count($hargas)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data item tidak valid'
            ]);
        }

        // =====================
        // VALIDASI PR
        // =====================
        $pr = $this->prModel->find($prId);

        if (!$pr) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'PR tidak ditemukan'
            ]);
        }

        if ($pr['status'] !== 'approved') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'PR belum disetujui'
            ]);
        }

        if ($pr['po_created'] == 1) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'PO sudah pernah dibuat'
            ]);
        }

        // =====================
        // VALIDASI ITEM
        // =====================
        foreach ($barangIds as $i => $barangId) {
            if ($qtys[$i] <= 0 || $hargas[$i] <= 0) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Qty dan harga harus lebih dari 0'
                ]);
            }
        }

            $this->db->transBegin();

            try {
                // =====================
                // SIMPAN PO HEADER
                // =====================
                $poData = [
                    'po_number'   => $this->poModel->generatePONumber(),
                    'pr_id'       => $prId,
                    'supplier_id' => $supplierId,
                    'warehouse_id'=> $pr['warehouse_id'],
                    'status'      => 'draft',
                    'created_by'  => session('user_id'),
                    'created_at'  => date('Y-m-d H:i:s')
                ];

                $this->poModel->insert($poData);
                $poId = $this->poModel->insertID();

                if (!$poId) {
                    throw new \Exception('Gagal menyimpan PO');
                }

                // =====================
                // SIMPAN PO DETAIL
                // =====================
                $detailData = [];
                foreach ($barangIds as $i => $barangId) {
                    $detailData[] = [
                        'po_id'     => $poId,
                        'barang_id' => $barangId,
                        'qty'       => $qtys[$i],
                        'harga'     => $hargas[$i],
                        'subtotal'  => $qtys[$i] * $hargas[$i]
                    ];
                }

                $this->poDetailModel->insertBatch($detailData);

                // =====================
                // UPDATE PR
                // =====================
                $this->prModel->update($prId, [
                    'po_created' => 1
                ]);

                // =====================
                // ACTIVITY LOG
                // =====================
                $this->activityLog->insert([
                    'user_id'       => session('user_id'),
                    'role'          => session('user_role'),
                    'activity_type' => 'CREATE_PO',
                    'reference_table' => 'purchase_order',
                    'reference_id'  => $poId,
                    'description'   => 'Membuat PO dari PR ' . $pr['pr_number'],
                    'created_at'    => date('Y-m-d H:i:s')
                ]);

                $this->db->transCommit();

                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'PO berhasil dibuat'
                ]);

            } catch (\Throwable $e) {
                $this->db->transRollback();

                return $this->response->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
    }

}