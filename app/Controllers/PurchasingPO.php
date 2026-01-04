<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\POModel;
use App\Models\PRModel;
use App\Models\PODetailModel;
use App\Models\ActivityLogModel;
use App\Models\AdminSupplierModel;
use Config\Database;

class PurchasingPO extends BaseController {
    protected $poModel;
    protected $prModel;
    protected $poDetailModel;
    protected $activityLog;
    protected $supplierModel;
    protected $db;

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
        $this->prModel = new PRModel();
        $this->poDetailModel = new PODetailModel();
        $this->activityLog = new ActivityLogModel();
        $this->supplierModel = new AdminSupplierModel();
        $this->db = Database::connect();
    }

    public function ajaxList() {
        $po = $this->poModel;
        $list = $po->getAllPO();
        $no = 0;
        $data = [];
        foreach ($list as $po) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $po['po_number'];
            $row[] = $po['nama_supplier'];
            $row[] = $po['status'];
            $row[] = $po['created_at'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn btnDetailPO" data-id="'. $po['po_id'].'">Detail</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    // Di Controller PurchasingPO.php
    public function detail($id = null)
    {
        $header = $this->poModel->getDetailPO($id);
        $items  = $this->poModel->getDetailItems($id);

        // Debugging: Pastikan header tidak null
        if (!$header) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Header PO dengan ID ' . $id . ' tidak ditemukan di database'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'header' => $header,
            'items'  => $items ?: [] // Kirim array kosong jika tidak ada item
        ]);
    }

    public function store()
    {
        // Pastikan database terhubung
        $db = \Config\Database::connect();
        
        $prId        = $this->request->getPost('pr_id');
        $supplierId  = $this->request->getPost('supplier_id');
        $expectedDelivery = $this->request->getPost('expected_delivery_date');
        $notes       = $this->request->getPost('notes');
        $barangIds   = $this->request->getPost('barang_id');
        $qtys        = $this->request->getPost('qty');
        $hargas      = $this->request->getPost('harga');

        // ... validasi dasar tetap sama ...

        $db->transBegin();

        try {
            $pr = $this->prModel->find($prId);
            
            // Hitung total
            $grandTotal = 0;
            foreach ($hargas as $i => $h) {
                $grandTotal += ($h * $qtys[$i]);
            }

            // Generate nomor PO tepat sebelum insert
            $newPoNumber = $this->poModel->generatePONumber();

            $poData = [
                'po_number'    => $newPoNumber,
                'pr_id'        => $prId,
                'supplier_id'  => $supplierId,
                'warehouse_id' => $pr['warehouse_id'],
                'order_date'   => date('Y-m-d'),
                'expected_delivery_date' => $expectedDelivery ?: null, // set null jika kosong
                'notes'        => $notes,
                'subtotal'     => $grandTotal,
                'total'        => $grandTotal,
                'status'       => 'draft',
                'created_by'   => session('user_id'),
            ];

            // Insert Header
            $db->table('purchase_order')->insert($poData);
            $poId = $db->insertID();

            // Insert Detail
            $detailData = [];
            foreach ($barangIds as $i => $bid) {
                $detailData[] = [
                    'po_id'     => $poId,
                    'barang_id' => $bid,
                    'qty'       => $qtys[$i],
                    'price'     => $hargas[$i] // Pastikan kolom di DB adalah 'price'
                ];
            }
            $db->table('purchase_order_detail')->insertBatch($detailData);

            // 🔹 3. TAMBAHKAN KODE INI: Update status di table purchase_request
            $db->table('purchase_request')
            ->where('pr_id', $prId)
            ->update(['po_created' => '1']);

            // 4. INSERT KE ACTIVITY LOG
            $logData = [
                'user_id'         => session('user_id'),
                'role'            => session('user_role'), // Pastikan session ini ada
                'activity_type'   => 'GENERATE_PO',
                'reference_table' => 'purchase_order',
                'reference_id'    => $poId,
                'description'     => 'Generate PO Nomor ' . $newPoNumber . ' dari PR ID: ' . $prId,
                'created_at'      => date('Y-m-d H:i:s')
            ];
            $db->table('activity_log')->insert($logData);

            if ($db->transStatus() === FALSE) {
                $db->transRollback();
                return $this->response->setJSON(['status' => false, 'message' => 'Database Transaction Failed']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => true, 'message' => 'Berhasil']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => false, 
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatusSent($id)
    {
        $db = \Config\Database::connect();
        
        $db->transBegin();
        try {
            // 1. Update status PO
            $db->table('purchase_order')
            ->where('po_id', $id)
            ->update(['status' => 'sent']);

            // 2. Catat ke Activity Log
            $po = $db->table('purchase_order')->where('po_id', $id)->get()->getRow();
            $db->table('activity_log')->insert([
                'user_id'         => session('user_id'),
                'role'            => session('user_role'),
                'activity_type'   => 'SEND_PO',
                'reference_table' => 'purchase_order',
                'reference_id'    => $id,
                'description'     => 'Mengirim Purchase Order Nomor: ' . $po->po_number,
                'created_at'      => date('Y-m-d H:i:s')
            ]);

            if ($db->transStatus() === FALSE) {
                $db->transRollback();
                return $this->response->setJSON(['status' => false, 'message' => 'Gagal memperbarui status']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => true, 'message' => 'PO berhasil ditandai sebagai terkirim (Sent)']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function printPDF($id)
    {
        $header = $this->poModel->getDetailPO($id);
        $items  = $this->poModel->getDetailItems($id);

        if (!$header) {
            return "Data PO tidak ditemukan";
        }

        $data = [
            'header' => $header,
            'items'  => $items
        ];

        // Initialize Dompdf
        $dompdf = new \Dompdf\Dompdf();
        
        // Load HTML dari view khusus PDF
        $html = view('pages/purchasing/po_pdf', $data);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output ke browser (Inline)
        return $this->response->setHeader('Content-Type', 'application/json') // Optional safety
            ->setBody($dompdf->stream("PO-" . $header['po_number'] . ".pdf", ["Attachment" => 0]));
    }

}