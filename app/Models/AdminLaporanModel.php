<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminLaporanModel extends Model {
    // Barang Masuk / Keluar
    public function laporanBarang($awal, $akhir)
    {
        return $this->db->table('barang b')
            ->select('
                b.nama_barang,
                IFNULL(SUM(bmd.jumlah),0) as total_masuk,
                IFNULL(SUM(bkd.jumlah),0) as total_keluar
            ')
            ->join('barang_masuk_detail bmd', 'bmd.barang_id = b.barang_id', 'left')
            ->join('barang_masuk bm', 'bm.masuk_id = bmd.masuk_id AND bm.tanggal_masuk BETWEEN "'.$awal.'" AND "'.$akhir.'"', 'left')
            ->join('barang_keluar_detail bkd', 'bkd.barang_id = b.barang_id', 'left')
            ->join('barang_keluar bk', 'bk.keluar_id = bkd.keluar_id AND bk.tanggal_keluar BETWEEN "'.$awal.'" AND "'.$akhir.'"', 'left')
            ->groupBy('b.barang_id')
            ->get()
            ->getResultArray();
    }

    // Stock Opname
    public function laporanStok($awal, $akhir)
    {
        return $this->db->table('stock_opname_detail sod')
            ->select('
                b.nama_barang,
                sod.stok_sistem,
                sod.stok_fisik,
                sod.selisih
            ')
            ->join('stock_opname so', 'so.opname_id = sod.opname_id')
            ->join('barang b', 'b.barang_id = sod.barang_id')
            ->where('so.tanggal_opname >=', $awal)
            ->where('so.tanggal_opname <=', $akhir)
            ->get()
            ->getResultArray();
    }

    // Purchasing
    public function laporanPurchasing($awal, $akhir)
    {
        return $this->db->table('purchase_order po')
            ->select('
                po.order_id,
                s.nama_supplier,
                po.tanggal_order,
                po.status,
                po.total_harga
            ')
            ->join('supplier s', 's.supplier_id = po.supplier_id')
            ->where('po.tanggal_order >=', $awal)
            ->where('po.tanggal_order <=', $akhir)
            ->get()
            ->getResultArray();
    }
}

