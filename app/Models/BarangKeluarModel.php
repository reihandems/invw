<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model {
    protected $table = 'barang_keluar';
    protected $primaryKey = 'keluar_id';
    protected $allowedFields = ['staff_id', 'warehouse_id', 'tanggal_keluar', 'keterangan'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    // Ambil daftar riwayat (Header)
    public function getBarangKeluar($warehouseId = null)
    {
        $builder = $this->db->table('barang_keluar bk')
            ->select('bk.*, u.nama_lengkap as nama_staff, w.nama_gudang')
            ->join('users u', 'u.user_id = bk.staff_id')
            ->join('warehouse w', 'w.warehouse_id = bk.warehouse_id');

        if ($warehouseId) {
            $builder->where('bk.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('bk.tanggal_keluar', 'DESC')->get()->getResultArray();
    }

    // Ambil detail barang dalam satu transaksi
    public function getBarangKeluarDetail($keluarId)
    {
        return $this->db->table('barang_keluar_detail bkd')
            ->select('bkd.*, b.nama_barang, b.sku, r.kode_rak, s.nama_satuan')
            ->join('barang b', 'b.barang_id = bkd.barang_id')
            ->join('warehouse_rack r', 'r.rack_id = bkd.rack_id')
            ->join('satuan s', 's.satuan_id = b.satuan_id', 'left')
            ->where('bkd.keluar_id', $keluarId)
            ->get()->getResultArray();
    }
    
}