<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\PRModel;
use App\Models\PRDetailModel;
use App\Models\AdminBarangModel;
use App\Models\ProductStockLocationModel;
use App\Models\KategoriModel;
use App\Models\ActivityLogModel;
use Config\Database;

class GudangPR extends BaseController
{
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

    public function index()
    {
        $data = [
            'menu' => 'purchasing',
            'pageTitle' => 'Purchase Request (PR)',
            'tab' => 'pr',
            'prNumber' => $this->prModel->generatePRNumber(),
            'user' => session('user_nama'),
            'user_gudang' => session('user_gudang'),
            'kategori' => $this->kategoriModel->findAll(),
            'barang' => $this->barangModel->findAll()
        ];

        return view('pages/gudang/view_pr', $data);
    }

    public function ajaxList()
    {
        $pr = $this->prModel;
        $list = $pr->getByGudang(session('user_id'));

        $no = 0;
        $data = [];
        foreach ($list as $pr) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $pr['pr_number'];
            $row[] = $pr['nama_gudang'];
            $row[] = $pr['status'];
            $row[] = $pr['created_at'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" onclick="openDetailPR(' . $pr['pr_id'] . ')" class="btn bg-white text-[#5160FC] border-[#C0CFDB] btn-sm edit-btn font-bold">Detail</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function generatePRNumber()
    {
        return $this->response->setJSON([
            'pr_number' => $this->prModel->generatePRNumber()
        ]);
    }


    // Handle request AJAX untuk mendapatkan daftar kabupaten/kota
    public function getBarang()
    {
        // Pastikan ini adalah permintaan AJAX dan ada data province_id
        if ($this->request->isAJAX() && $this->request->getPost('kategori_id')) {
            $kategoriId = $this->request->getPost('kategori_id');

            $barangModel = $this->barangModel;
            $barang = $barangModel->getBarangByKategori($kategoriId);

            // Mengembalikan data dalam format JSON
            return $this->response->setJSON($barang);
        }

        // Jika bukan permintaan AJAX, kembalikan response 404 atau JSON kosong
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Invalid request']);
    }

    public function getStok()
    {
        $barangId = $this->request->getGet('barang_id');
        $warehouseId = session('warehouse_id');

        if (!$barangId || !$warehouseId) {
            return $this->response->setJSON(['stok' => 0]);
        }

        $stok = $this->productStockLocation
            ->selectSum('jumlah_stok', 'total')
            ->where('barang_id', $barangId)
            ->where('warehouse_id', $warehouseId)
            ->get()
            ->getRowArray();

        return $this->response->setJSON([
            'stok' => $stok ? (int)$stok['total'] : 0
        ]);
    }

    public function store()
    {
        $barangIds = $this->request->getPost('barang_id');
        $qtys      = $this->request->getPost('qty');

        if (empty($barangIds) || empty($qtys)) {
            return $this->response->setJSON([
                'status' => false,
                'msg' => 'Detail barang tidak boleh kosong'
            ]);
        }

        $this->db->transException(true);
        $this->db->transBegin();

        try {
            $prData = [
                'pr_number'    => $this->request->getPost('pr_number'),
                'warehouse_id' => session('warehouse_id'),
                'user_id'      => session('user_id'),
                'status'       => 'submitted',
                'notes'        => $this->request->getPost('notes'),
            ];

            $this->prModel->insert($prData);
            $prId = $this->prModel->insertID();

            if (!$prId) {
                throw new \Exception('Gagal menyimpan PR header');
            }

            $detailData = [];
            foreach ($barangIds as $i => $barangId) {
                $detailData[] = [
                    'pr_id'     => $prId,
                    'barang_id' => $barangId,
                    'qty'       => $qtys[$i]
                ];
            }

            $this->prDetailModel->insertBatch($detailData);

            $this->activityLog->insert([
                'user_id'         => session('user_id'),
                'role'            => session('user_role'),
                'activity_type'   => 'CREATE_PR',
                'reference_table' => 'purchase_request',
                'reference_id'    => $prId,
                'description'     => 'Membuat Purchase Request ' . $prData['pr_number'],
            ]);

            $this->db->transCommit();

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Purchase Request berhasil diajukan'
            ]);
        } catch (\Throwable $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detail($prId)
    {
        $header = $this->prModel->getDetailPR($prId);
        $items  = $this->prModel->getDetailItems($prId);

        return $this->response->setJSON([
            'status' => true,
            'header' => $header,
            'items'  => $items
        ]);
    }
}
