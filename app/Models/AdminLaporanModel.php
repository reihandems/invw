<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminLaporanModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';
    protected $returnType = 'array';

    /**
     * Laporan Barang Masuk / Keluar
     */
    public function getLaporanBarang($tanggalAwal, $tanggalAkhir)
    {
        return $this->db->table('barang b')
            ->select('
                b.nama_barang,
                IFNULL(SUM(bm.qty), 0) AS total_masuk,
                IFNULL(SUM(bk.qty), 0) AS total_keluar,
                (IFNULL(SUM(bm.qty), 0) - IFNULL(SUM(bk.qty), 0)) AS selisih
            ')
            ->join(
                'barang_masuk bm',
                'bm.barang_id = b.barang_id AND bm.tanggal BETWEEN "' . $tanggalAwal . '" AND "' . $tanggalAkhir . '"',
                'left'
            )
            ->join(
                'barang_keluar bk',
                'bk.barang_id = b.barang_id AND bk.tanggal BETWEEN "' . $tanggalAwal . '" AND "' . $tanggalAkhir . '"',
                'left'
            )
            ->groupBy('b.barang_id')
            ->get()
            ->getResultArray();
    }
}
