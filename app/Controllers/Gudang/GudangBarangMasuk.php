<?php 

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\BarangMasukModel;

class GudangBarangMasuk extends BaseController {
    protected $barangMasukModel;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
    }

    public function index() {
        $data = [
            'menu' => 'barang_masuk',
            'pageTitle' => 'Barang Masuk'
        ];

        return view('pages/gudang/view_barang_masuk', $data);
    }

    public function ajaxList() {
        $barangMasuk = $this->barangMasukModel;
        $userWarehouseId = session('warehouse_id'); // Ambil ID gudang staff dari session
        // Staff Gudang hanya melihat gudangnya sendiri
        $list = $barangMasuk->getAllBarangMasuk($userWarehouseId);

        $no = 0;
        $data = [];
        foreach ($list as $b) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $b['nama_lengkap'];
            $row[] = $b['nama_gudang'];
            $row[] = $b['tanggal_masuk'];
            $row[] = $b['keterangan'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" data-id="'.$b['masuk_id'].'" class="btn bg-white text-[#5160FC] border-[#C0CFDB] btn-sm edit-btn font-bold btnDetailMasuk">Detail</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function getDetail($id)
    {
        $db = \Config\Database::connect();

        // 1. Ambil Header
        $header = $db->table('barang_masuk bm')
            ->select('bm.*, w.nama_gudang, u.nama_lengkap as nama_staff')
            ->join('warehouse w', 'w.warehouse_id = bm.warehouse_id')
            ->join('users u', 'u.user_id = bm.staff_id') // Sesuaikan nama tabel user Anda
            ->where('bm.masuk_id', $id)
            ->get()->getRow();

        // 2. Ambil Items + Info Rak (Penting!)
        // Karena saat simpan kita tidak simpan rack_id di barang_masuk_detail, 
        // kita bisa menariknya dari product_stock_location atau menambah kolom rack_id di detail.
        // Asumsi: Anda mengikuti saran saya menambah kolom rack_id di barang_masuk_detail.
        $items = $db->table('barang_masuk_detail bmd')
            ->select('bmd.*, b.nama_barang, r.kode_rak')
            ->join('barang b', 'b.barang_id = bmd.barang_id')
            ->join('warehouse_rack r', 'r.rack_id = bmd.rack_id', 'left') // Jika Anda simpan rack_id di detail
            ->where('bmd.masuk_id', $id)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => true,
            'header' => $header,
            'items'  => $items
        ]);
    }
}