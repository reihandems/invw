<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminLaporanModel extends Model
{
    // ✅ Laporan Barang Masuk / Keluar
    public function laporanBarang($awal, $akhir)
    {
        return $this->db->table('barang b')
            ->select('
                b.nama_barang,
                COALESCE(SUM(bmd.jumlah), 0) as total_masuk,
                COALESCE(SUM(bkd.jumlah), 0) as total_keluar
            ')
            ->join('barang_masuk_detail bmd', 'bmd.barang_id = b.barang_id', 'left')
            ->join('barang_masuk bm', 'bm.masuk_id = bmd.masuk_id AND DATE(bm.tanggal_masuk) BETWEEN "' . $awal . '" AND "' . $akhir . '"', 'left')
            ->join('barang_keluar_detail bkd', 'bkd.barang_id = b.barang_id', 'left')
            ->join('barang_keluar bk', 'bk.keluar_id = bkd.keluar_id AND DATE(bk.tanggal_keluar) BETWEEN "' . $awal . '" AND "' . $akhir . '"', 'left')
            ->groupBy('b.barang_id')
            ->having('total_masuk > 0 OR total_keluar > 0') // Hanya tampilkan yang ada transaksi
            ->get()
            ->getResultArray();
    }

    // 🔧 Laporan Stok Opname (FIXED)
    public function laporanStok($awal, $akhir)
    {
        // Cek apakah tabel stock_opname ada
        // Jika tidak ada, kita harus membuat laporan stok dari data yang ada

        // Opsi 1: Jika tabel stock_opname sudah ada
        if ($this->db->tableExists('stock_opname')) {
            return $this->db->table('stock_opname_detail sod')
                ->select('
                    b.nama_barang,
                    sod.stok_sistem,
                    sod.stok_fisik,
                    sod.selisih
                ')
                ->join('stock_opname so', 'so.opname_id = sod.opname_id')
                ->join('barang b', 'b.barang_id = sod.barang_id')
                ->where('DATE(so.tanggal_opname) >=', $awal)
                ->where('DATE(so.tanggal_opname) <=', $akhir)
                ->get()
                ->getResultArray();
        }

        // Opsi 2: Generate laporan stok dari product_stock_location
        // (untuk sementara jika belum ada tabel stock_opname)
        return $this->db->table('product_stock_location psl')
            ->select('
                b.nama_barang,
                SUM(psl.jumlah_stok) as stok_sistem,
                0 as stok_fisik,
                0 as selisih
            ')
            ->join('barang b', 'b.barang_id = psl.barang_id')
            ->groupBy('b.barang_id')
            ->having('stok_sistem > 0')
            ->get()
            ->getResultArray();
    }

    // 🔧 Laporan Purchasing (FIXED)
    public function laporanPurchasing($awal, $akhir)
    {
        return $this->db->table('purchase_order po')
            ->select('
                po.po_id,
                s.nama_supplier,
                po.order_date as tanggal_order,
                po.status,
                po.total as total_harga
            ')
            ->join('supplier s', 's.supplier_id = po.supplier_id')
            ->where('DATE(po.order_date) >=', $awal)
            ->where('DATE(po.order_date) <=', $akhir)
            ->orderBy('po.order_date', 'DESC')
            ->get()
            ->getResultArray();
    }
}
