<?php

namespace App\Models;

use CodeIgniter\Model;

class PODetailModel extends Model {
    protected $table = 'purchase_order_detail';
    protected $primaryKey = 'po_detail_id';
    protected $allowedFields = ['po_id', 'barang_id', 'qty', 'price', 'subtotal'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}