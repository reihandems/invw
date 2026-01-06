<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangMasukModel extends Model {
    protected $table = 'barang_masuk';
    protected $primaryKey = 'masuk_id';
    protected $allowedFields = ['staff_id', 'warehouse_id', 'tanggal_masuk', 'keterangan'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getAllBarangMasuk($warehouseId = null) {
        $builder = $this->select('
                barang_masuk.masuk_id,
                users.nama_lengkap,
                warehouse.nama_gudang,
                barang_masuk.tanggal_masuk,
                barang_masuk.keterangan
            ')
            ->join('users','users.user_id = barang_masuk.staff_id', 'left')
            ->join('warehouse', 'warehouse.warehouse_id = barang_masuk.warehouse_id', 'left');

        // JIKA warehouseId dikirim, maka filter datanya
        if ($warehouseId !== null) {
            $builder->where('barang_masuk.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('barang_masuk.tanggal_masuk', 'DESC')
                    ->findAll();
    }
}