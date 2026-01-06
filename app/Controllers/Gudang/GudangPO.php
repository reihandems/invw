<?php 

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\POModel;
use App\Models\PODetailModel;

class GudangPO extends BaseController {
    protected $poModel;
    protected $poDetailModel;

    public function __construct()
    {
        $this->poModel = new POModel();
        $this->poDetailModel = new PODetailModel();
    }

    public function index() {
        $data = [
            'menu' => 'purchasing',
            'pageTitle' => 'Purchase Order (PO)',
            'tab' => 'po'
        ];

        return view('pages/gudang/view_po', $data);
    }

    public function ajaxList() {
        $po = $this->poModel;
        $list = $po->getReadyToReceive();

        $no = 0;
        $data = [];
        foreach ($list as $po) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $po['po_number'];
            $row[] = $po['nama_supplier'];
            $row[] = $po['nama_gudang'];
            $row[] = $po['expected_delivery_date'];

            // Kolom Aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn btnProsesMasuk" data-id="'. $po['po_id'].'" data-po="'. $po['po_number'] .'" data-warehouse="'. $po['warehouse_id'] . '">Terima Barang</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function getPODetail($id)
    {
        // Gunakan model yang sama, tapi panggil di controller gudang
        $header = $this->poModel->getDetailPO($id);
        $items  = $this->poModel->getDetailItems($id);

        return $this->response->setJSON([
            'status' => true,
            'header' => $header,
            'items'  => $items
        ]);
    }

    public function getRacks($warehouseId)
    {
        $db = \Config\Database::connect();
        $racks = $db->table('warehouse_rack')
                        ->where('warehouse_id', $warehouseId)
                        ->get()->getResultArray();
        return $this->response->setJSON($racks);
    }

    public function save() {
        $db = \Config\Database::connect();
        
        $poId = $this->request->getPost('po_id');
        $warehouseId = $this->request->getPost('warehouse_id');
        $rackId = $this->request->getPost('rack_id');
        $items = $this->request->getPost('items');

        $db->transBegin();
        try {
            // 1. Simpan Header
            $headerData = [
                'staff_id'      => session('user_id'), 
                'warehouse_id'  => $warehouseId,
                'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
                'keterangan'    => $this->request->getPost('keterangan'),
            ];
            
            $db->table('barang_masuk')->insert($headerData);
            
            // Cek jika insert header gagal
            if ($db->error()['code'] !== 0) {
                throw new \Exception("Gagal Header: " . $db->error()['message']);
            }
            
            $masukId = $db->insertID();

            // 2. Simpan Detail & Update Stok
            foreach ($items as $item) {
                $idBarang = $item['barang_id'];
                $jml      = intval($item['jumlah']);
                $rackId   = $item['rack_id']; // Ambil rak_id per baris

                if ($jml > 0 && !empty($rackId)) {
                    // 1. Simpan ke detail barang masuk
                    $db->table('barang_masuk_detail')->insert([
                        'masuk_id'  => $masukId,
                        'barang_id' => $idBarang,
                        'jumlah'    => $jml
                    ]);

                    // 2. Update Stok berdasarkan Barang, Gudang, DAN Rak spesifik tersebut
                    $sql = "INSERT INTO product_stock_location (barang_id, warehouse_id, rack_id, jumlah_stok) 
                            VALUES (?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE jumlah_stok = jumlah_stok + VALUES(jumlah_stok)";
                    
                    $db->query($sql, [$idBarang, $warehouseId, $rackId, $jml]);
                }
            }

            // 3. Update Status PO
            $db->table('purchase_order')->where('po_id', $poId)->update(['status' => 'received']);

            // 4. INSERT KE ACTIVITY LOG
            $logData = [
                'user_id'         => session('user_id'),
                'role'            => session('user_role'), // Pastikan session role tersedia (Gudang)
                'activity_type'   => 'RECEIVE_GOODS',
                'reference_table' => 'barang_masuk',
                'reference_id'    => $masukId,
                'description'     => "Menerima barang dari PO: " . $this->request->getPost('in_po_number') . " ke Gudang ID: " . $warehouseId,
                'created_at'      => date('Y-m-d H:i:s')
            ];
            
            $db->table('activity_log')->insert($logData);

            // Cek error log
            if ($db->error()['code'] !== 0) {
                throw new \Exception("Gagal mencatat log: " . $db->error()['message']);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => true]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }
}