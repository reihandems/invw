<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'masuk_id';
    protected $allowedFields = ['staff_id', 'warehouse_id', 'tanggal_masuk', 'keterangan'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getAllBarangMasuk($warehouseId = null)
    {
        $builder = $this->select('
                barang_masuk.masuk_id,
                users.nama_lengkap,
                warehouse.nama_gudang,
                barang_masuk.tanggal_masuk,
                barang_masuk.keterangan
            ')
            ->join('users', 'users.user_id = barang_masuk.staff_id', 'left')
            ->join('warehouse', 'warehouse.warehouse_id = barang_masuk.warehouse_id', 'left');

        // JIKA warehouseId dikirim, maka filter datanya
        if ($warehouseId !== null) {
            $builder->where('barang_masuk.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('barang_masuk.tanggal_masuk', 'DESC')
            ->findAll();
    }

    // Hitung jumlah transaksi masuk bulan ini
    public function getInCount($warehouseId, $month, $year)
    {
        return $this->where('warehouse_id', $warehouseId)
            ->where('MONTH(tanggal_masuk)', $month)
            ->where('YEAR(tanggal_masuk)', $year)
            ->countAllResults();
    }

    // Ambil statistik per bulan untuk chart
    public function getMonthlyStats($warehouseId, $year)
    {
        $query = $this->db->table($this->table)
            ->select('MONTH(tanggal_masuk) as bulan, COUNT(*) as total')
            ->where('warehouse_id', $warehouseId)
            ->where('YEAR(tanggal_masuk)', $year)
            ->groupBy('MONTH(tanggal_masuk)')
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
