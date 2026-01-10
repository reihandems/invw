<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\OpnameModel;

class GudangOpnameApproval extends BaseController {
    protected $opnameModel;

    public function __construct()
    {
        $this->opnameModel = new OpnameModel();
    }

    public function index() {
        $data = [
            'menu' => 'opname',
            'pageTitle' => 'Stok Opname',
            'tab' => 'waitApproval'
        ];

        return view('pages/gudang/view_opname_approval', $data);
    }

    public function ajaxList() {
        $opname = $this->opnameModel;
        $warehouseId = session('warehouse_id');
        $list = $opname->getApprovalOpname($warehouseId);

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
            $row[] = '<div class="badge badge-sm badge-outline badge-primary">'.$op['status'].'</div>';

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-white text-[#5160FC] border-[#e5e5e5] btn-sm edit-btn" onclick="showDetail('.$op['opname_id'].', \''.$op['status'].'\')">Detail</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
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
}