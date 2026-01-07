<?php 

namespace App\Models;

use CodeIgniter\Model;

class BarangKeluarDetailModel extends Model {
    protected $table = 'barang_keluar_detail';
    protected $primaryKey = 'keluar_detail_id';
    protected $allowedFields = ['keluar_id', 'barang_id', 'jumlah'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}