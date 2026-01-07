<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductStockLocationModel extends Model {
    protected $table = 'product_stock_location';
    protected $primaryKey = 'stock_id';
    protected $allowedFields = ['barang_id', 'warehouse_id', 'rack_id', 'jumlah_stok', 'kategori_id'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getStockReport($warehouseId = null)
    {
        $builder = $this->db->table('product_stock_location psl')
            ->select('
                b.nama_barang, 
                b.sku, 
                w.nama_gudang, 
                r.kode_rak, 
                psl.jumlah_stok, 
                s.nama_satuan,
                k.nama_kategori
            ')
            ->join('barang b', 'b.barang_id = psl.barang_id')
            ->join('warehouse w', 'w.warehouse_id = psl.warehouse_id')
            ->join('warehouse_rack r', 'r.rack_id = psl.rack_id')
            ->join('satuan s', 's.satuan_id = b.satuan_id', 'left')
            ->join('kategori k', 'k.kategori_id = b.kategori_id', 'left');

        // Jika yang melihat adalah Staff Gudang, filter hanya gudang dia
        if (!empty($warehouseId)) {
            $builder->where('psl.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('b.nama_barang', 'ASC')
                    ->orderBy('w.nama_gudang', 'ASC')
                    ->get()->getResultArray();
    }
}