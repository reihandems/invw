<?php 

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Models\OpnameModel;
use App\Models\RacksModel;
use App\Models\AdminGudangModel;

class ManagerOpname extends BaseController {
    protected $opnameModel;
    protected $racksModel;
    protected $gudangModel;

    public function __construct()
    {
        $this->opnameModel = new OpnameModel();
        $this->racksModel = new RacksModel();
        $this->gudangModel = new AdminGudangModel();
    }

    public function index() {
        $data = [
            'menu' => 'opname',
            'pageTitle' => 'Stok Opname',
            'tab' => 'jadwalOpname',
            'list_gudang' => $this->gudangModel->findAll(),
            'list_rak' => $this->racksModel->findAll()
        ];

        return view('pages/manager/view_opname', $data);
    }

    public function ajaxList() {
        $opname = $this->opnameModel;
        $list = $opname->findAll();

        $no = 0;
        $data = [];
        foreach($list as $op) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $op['nama_jadwal'];
            $row[] = $op['jenis'];
            $row[] = $op['tanggal_mulai'];
            $row[] = $op['tanggal_berakhir'];
            $row[] = $op['status'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-white text-[#5160FC] border-[#e5e5e5] btn-sm edit-btn" onclick="showDetail('.$op['opname_id'].', \''.$op['status'].'\')">Detail</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$op['opname_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function getRacksByWarehouse($warehouseId)
    {
        $db = \Config\Database::connect();
        $racks = $db->table('warehouse_rack')
                    ->where('warehouse_id', $warehouseId)
                    ->get()
                    ->getResultArray();
                    
        return $this->response->setJSON($racks);
    }

    public function saveSchedule()
    {
        $db = \Config\Database::connect();
        
        $warehouseId = $this->request->getPost('warehouse_id');
        $nama        = $this->request->getPost('nama_jadwal');
        $jenis       = $this->request->getPost('jenis');
        $rak_ids     = $this->request->getPost('rak_ids');

        $db->transBegin();
        try {
            // 1. Simpan Header dengan warehouse_id
            $db->table('opname_schedule')->insert([
                'nama_jadwal'      => $nama,
                'warehouse_id'     => $warehouseId, // Simpan gudang mana yang di-opname
                'jenis'            => $jenis,
                'tanggal_mulai'    => $this->request->getPost('tanggal_mulai'),
                'tanggal_berakhir' => $this->request->getPost('tanggal_berakhir'),
                'keterangan'       => $this->request->getPost('keterangan'),
                'status'           => 'scheduled',
                'created_by'       => session('user_id')
            ]);
            
            $opnameId = $db->insertID();

            // 2. Query Snapshot Stok
            $builder = $db->table('product_stock_location psl');
            $builder->select('psl.barang_id, psl.rack_id, psl.jumlah_stok');
            
            // WAJIB FILTER BERDASARKAN GUDANG
            $builder->where('psl.warehouse_id', $warehouseId);
            
            // Jika partial, filter lagi berdasarkan rak
            if ($jenis == 'partial' && !empty($rak_ids)) {
                $builder->whereIn('psl.rack_id', $rak_ids);
            }

            $stocks = $builder->get()->getResultArray();

            // 3. Masukkan ke Detail
            foreach ($stocks as $s) {
                $db->table('opname_detail')->insert([
                    'opname_id'   => $opnameId,
                    'barang_id'   => $s['barang_id'],
                    'rack_id'     => $s['rack_id'],
                    'stok_sistem' => $s['jumlah_stok']
                ]);
            }

            $db->transCommit();
            return $this->response->setJSON(['status' => true]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getDetail($opnameId)
    {
        $db = \Config\Database::connect();
        
        // Ambil data detail barang di jadwal tersebut
        $detail = $db->table('opname_detail od')
            ->select('od.*, b.nama_barang, b.sku, r.kode_rak, s.nama_satuan')
            ->join('barang b', 'b.barang_id = od.barang_id')
            ->join('warehouse_rack r', 'r.rack_id = od.rack_id')
            ->join('satuan s', 's.satuan_id = b.satuan_id', 'left')
            ->where('od.opname_id', $opnameId)
            ->get()->getResultArray();

        return $this->response->setJSON($detail);
    }

    public function deleteData ($id = null) {
        if ($this->opnameModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'msg' => 'Data Berhasil Dihapus!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'msg' => 'Gagal menghapus data!'
            ]);
        }
    }
}