<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarModel extends Model
{
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

    // Hitung jumlah transaksi keluar bulan ini
    public function getOutCount($warehouseId, $month, $year)
    {
        return $this->where('warehouse_id', $warehouseId)
            ->where('MONTH(tanggal_keluar)', $month)
            ->where('YEAR(tanggal_keluar)', $year)
            ->countAllResults();
    }

    // Ambil statistik per bulan untuk chart
    public function getMonthlyStats($warehouseId, $year)
    {
        $query = $this->db->table($this->table)
            ->select('MONTH(tanggal_keluar) as bulan, COUNT(*) as total')
            ->where('warehouse_id', $warehouseId)
            ->where('YEAR(tanggal_keluar)', $year)
            ->groupBy('MONTH(tanggal_keluar)')
            ->orderBy('bulan', 'ASC')
            ->get()->getResultArray();

        // Format data agar index 1-12 selalu ada
        $stats = array_fill(1, 12, 0); // Default 0 dari Jan-Des

        foreach ($query as $row) {
            $stats[$row['bulan']] = (int)$row['total'];
        }

        return array_values($stats); // Return indexed 0-11
    }
}
