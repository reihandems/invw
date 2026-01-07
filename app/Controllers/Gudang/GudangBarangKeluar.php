<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\BarangKeluarModel;
use App\Models\BarangKeluarDetailModel;
use App\Models\ProductStockLocationModel;
use App\Models\AdminBarangModel;

class GudangBarangKeluar extends BaseController {
    protected $barangKeluar;
    protected $stockLocationModel;
    protected $barangModel;

    public function __construct()
    {
        $this->barangKeluar = new BarangKeluarModel();
        $this->stockLocationModel = new ProductStockLocationModel();
        $this->barangModel = new AdminBarangModel();
    }

    public function index() {
        

        $data = [
            'menu' => 'barang_keluar',
            'pageTitle' => 'Barang Keluar',
            'list_barang' => $this->barangModel->findAll()
        ];

        return view('pages/gudang/view_barang_keluar', $data);
    }

    public function ajaxList() {
        $barangKeluar = $this->barangKeluar;
        $warehouseId = session('warehouse_id');

        $list = $barangKeluar->getBarangKeluar($warehouseId);

        $no = 0;
        $data = [];
        foreach($list as $b) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = date('d/m/Y', strtotime($b['tanggal_keluar']));
            $row[] = $b['nama_gudang'];
            $row[] = $b['keterangan'];
            $row[] = $b['nama_staff'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" data-id="'.$b['keluar_id'].'" class="btn bg-white text-[#5160FC] border-[#C0CFDB] btn-sm edit-btn font-bold mr-1 btnDetail">Detail</a>
            <a href="'.site_url('gudang/barang-keluar/cetak-surat-jalan/' . $b['keluar_id']).'" 
           target="_blank" class="btn btn-sm border border-gray-400">
            Cetak
        </a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function getRacks()
    {
        // 1. Ambil data dari input GET (sesuai kiriman AJAX)
        $barangId    = $this->request->getGet('barang_id');
        $warehouseId = session('warehouse_id'); // Lebih aman ambil dari session langsung

        $db = \Config\Database::connect();

        // 2. Query harus join ke product_stock_location 
        // agar hanya menampilkan rak yang ada barangnya
        $racks = $db->table('product_stock_location psl')
            ->select('psl.rack_id, r.kode_rak, psl.jumlah_stok')
            ->join('warehouse_rack r', 'r.rack_id = psl.rack_id')
            ->where('psl.barang_id', $barangId)
            ->where('psl.warehouse_id', $warehouseId)
            ->where('psl.jumlah_stok >', 0) // Hanya tampilkan yang stoknya ada
            ->get()->getResultArray();

        return $this->response->setJSON($racks);
    }

    public function save() {
        $db = \Config\Database::connect();
        $items = $this->request->getPost('items');
        $warehouseId = session('warehouse_id');

        // Pastikan session ada agar tidak error saat insert header/log
        if (!$warehouseId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Sesi habis, silakan login kembali.']);
        }

        $db->transBegin();
        try {
            // 1. Simpan Header
            $db->table('barang_keluar')->insert([
                'staff_id'       => session('user_id'),
                'warehouse_id'   => $warehouseId,
                'tanggal_keluar' => $this->request->getPost('tanggal_keluar'),
                'keterangan'     => $this->request->getPost('keterangan')
            ]);

            // AMBIL ID TERBARU
            $keluarId = $db->insertID();

            // VALIDASI: Jika ID gagal didapat, jangan lanjut ke detail
            if (!$keluarId || $keluarId == 0) {
                throw new \Exception("Gagal mendapatkan ID transaksi.");
            }

            // 2. Loop Detail
            foreach ($items as $item) {
                $jml = (int)$item['jumlah'];
                if ($jml <= 0) continue;

                // Simpan Detail
                $dataDetail = [
                    'keluar_id' => $keluarId,
                    'barang_id' => $item['barang_id'],
                    'rack_id'   => $item['rack_id'],
                    'jumlah'    => $jml
                ];

                if (!$db->table('barang_keluar_detail')->insert($dataDetail)) {
                    $err = $db->error();
                    throw new \Exception("Gagal simpan detail barang: " . $err['message']);
                }

                // 3. Update Stok (Kurangi)
                $db->table('product_stock_location')
                ->where([
                        'barang_id'    => $item['barang_id'],
                        'warehouse_id' => $warehouseId,
                        'rack_id'      => $item['rack_id']
                ])
                ->set('jumlah_stok', "jumlah_stok - $jml", false)
                ->update();
            }

            // Commit transaksi jika semua oke
            $db->transCommit();
            return $this->response->setJSON(['status' => true]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => false, 
                'message' => "Error: " . $e->getMessage()
            ]);
        }
    }

    public function detail($id)
    {
        $data = $this->barangKeluar->getBarangKeluarDetail($id);
        return $this->response->setJSON($data);
    }

    public function cetakSuratJalan($id)
    {
        $db = \Config\Database::connect();
        // Ambil data header
        $header = $db->table('barang_keluar bk')
            ->select('bk.*, u.nama_lengkap as nama_staff, w.nama_gudang, w.alamat')
            ->join('users u', 'u.user_id = bk.staff_id')
            ->join('warehouse w', 'w.warehouse_id = bk.warehouse_id')
            ->where('bk.keluar_id', $id)
            ->get()->getRowArray();

        if (!$header) {
            return "Data tidak ditemukan.";
        }

        // Ambil data detail barang
        $detail = $this->barangKeluar->getBarangKeluarDetail($id);

        $data = [
            'header' => $header,
            'detail' => $detail,
            'title'  => "SURAT JALAN BARANG KELUAR"
        ];

        $html = view('pages/gudang/surat_jalan', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'landscape'); // Ukuran A5 biasanya standar surat jalan
        $dompdf->render();
        
        return $dompdf->stream("Surat_Jalan_" . $id . ".pdf", ["Attachment" => false]);
    }
}