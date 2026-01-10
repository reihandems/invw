<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\OpnameModel;

class GudangOpnameRejected extends BaseController {
    protected $opnameModel;

    public function __construct()
    {
        $this->opnameModel = new OpnameModel();
    }

    public function index() {
        $data = [
            'menu' => 'opname',
            'pageTitle' => 'Stok Opname',
            'tab' => 'rejectedOpname'
        ];

        return view('pages/gudang/view_opname_rejected', $data);
    }

    public function ajaxList() {
        $opname = $this->opnameModel;
        $warehouseId = session('warehouse_id');
        $list = $opname->getRejectedOpname($warehouseId);

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
            $row[] = '<a href="javascript:void(0)" class="btn bg-white text-[#5160FC] border-[#e5e5e5] btn-sm edit-btn" onclick="showDetail('.$op['opname_id'].', \''.$op['status'].'\')">Detail</a>
            <button onclick="mulaiHitung('.$op['opname_id'].')" class="btn btn-sm bg-[#5160FC] text-white">Mulai Hitung</button>';

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

    public function getItems($opnameId)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('opname_detail od');
        $builder->select('od.*, b.nama_barang, b.sku, r.kode_rak');
        $builder->join('barang b', 'b.barang_id = od.barang_id');
        $builder->join('warehouse_rack r', 'r.rack_id = od.rack_id');
        $builder->where('od.opname_id', $opnameId);
        
        $data = $builder->get()->getResultArray();

        // Mengirimkan response JSON
        return $this->response->setJSON($data);
    }

    public function submitFisik()
    {
        $db = \Config\Database::connect();
        $opnameId = $this->request->getPost('opname_id');
        $items = $this->request->getPost('items');

        $db->transBegin();
        try {
            foreach ($items as $item) {
                // Ambil data detail untuk hitung selisih
                $detail = $db->table('opname_detail')->where('opname_detail_id', $item['detail_id'])->get()->getRow();
                $selisih = $item['stok_fisik'] - $detail->stok_sistem;

                $db->table('opname_detail')->where('opname_detail_id', $item['detail_id'])->update([
                    'stok_fisik'    => $item['stok_fisik'],
                    'selisih'       => $selisih,
                    'catatan_staff' => $item['catatan']
                ]);
            }

            // Ubah status jadwal menjadi 'submitted' agar Manager bisa approve
            $db->table('opname_schedule')->where('opname_id', $opnameId)->update(['status' => 'submitted']);

            $db->transCommit();
            return $this->response->setJSON(['status' => true]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }


}