<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductStockLocationModel extends Model {
    protected $table = 'product_stock_location';
    protected $primaryKey = 'stock_id';
    protected $allowedFields = ['barang_id', 'warehouse_id', 'rack_id', 'jumlah_stok', 'kategori_id'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}