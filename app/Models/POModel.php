<?php

namespace App\Models;

use CodeIgniter\Model;

class POModel extends Model {
    protected $table = 'purchase_order';
    protected $primaryKey = 'po_id';
    protected $allowedFields = [
        'po_number', 
        'pr_id', 
        'supplier_id', 
        'warehouse_id', 
        'order_date', 
        'expected_delivery_date', 
        'subtotal', 
        'total', 
        'status', 
        'notes', 
        'created_by',
        'created_at'
    ];
// Karena created_at & updated_at sudah DEFAULT_GENERATED di DB, 
// tidak perlu dimasukkan ke allowedFields jika tidak ingin diisi manual.
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function countTodayPO()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))
                    ->countAllResults();
    }

    public function countMonthlyPO()
    {
        return $this->where('MONTH(created_at)', date('m'))
                    ->where('YEAR(created_at)', date('Y'))
                    ->countAllResults();
    }

    public function countActivePO()
    {
        return $this->whereIn('status', ['open','process'])
                    ->countAllResults();
    }

    public function getLatestPO($limit = 5)
    {
        return $this->select('po_number, warehouse_id, supplier_id, status, created_at')
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    public function getAllPO(){
        return $this->select('
                purchase_order.po_id,
                purchase_order.po_number,
                purchase_order.status,
                purchase_order.created_at,
                supplier.nama_supplier
            ')
            ->join('supplier', 'supplier.supplier_id = purchase_order.supplier_id')
            ->orderBy('purchase_order.created_at', 'DESC')
            ->findAll();
    }

    public function getDetailPO($poId)
    {
        return $this->select('
                purchase_order.po_id,
                purchase_order.po_number,
                purchase_order.order_date,
                purchase_order.expected_delivery_date,
                purchase_order.status,
                purchase_order.total, 
                purchase_order.notes, 

                supplier.nama_supplier,
                supplier.kontak,
                supplier.alamat AS alamat_supplier,

                warehouse.nama_gudang,
                warehouse.alamat AS alamat_gudang,

                users.nama_lengkap AS purchasing_name
            ')
            ->join('supplier', 'supplier.supplier_id = purchase_order.supplier_id')
            ->join('warehouse', 'warehouse.warehouse_id = purchase_order.warehouse_id')
            ->join('users', 'users.user_id = purchase_order.created_by')
            ->where('purchase_order.po_id', $poId)
            ->first();
    }

    public function getDetailItems($poId)
    {
        return $this->db->table('purchase_order_detail pod')
            ->select('
                b.barang_id,
                b.sku,
                b.nama_barang,
                s.nama_satuan,
                pod.qty,
                pod.price,
                (pod.qty * pod.price) AS subtotal
            ')
            ->join('barang b', 'b.barang_id = pod.barang_id')
            ->join('satuan s', 's.satuan_id = b.satuan_id')
            ->where('pod.po_id', $poId)
            ->get()
            ->getResultArray();
    }

    public function generatePONumber()
    {
        $date = date('Ymd');
        $prefix = 'PO-' . $date . '-';

        // Ambil nomor PO terakhir di hari ini, meskipun datanya belum ter-commit sempurna
        $lastPO = $this->db->table($this->table)
                    ->select('po_number')
                    ->like('po_number', $prefix, 'after')
                    ->orderBy('po_number', 'DESC')
                    ->limit(1)
                    ->get()
                    ->getRowArray();

        if ($lastPO) {
            // Mengambil 4 digit terakhir dan ditambah 1
            $lastNumber = (int) substr($lastPO['po_number'], -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Gudang

    public function getReadyToReceive($warehouseId = null)
    {
        $builder = $this->db->table('purchase_order po')
            ->select('
                po.po_id, 
                po.po_number, 
                po.order_date, 
                po.expected_delivery_date, 
                po.status, 
                po.total,
                s.nama_supplier, 
                w.nama_gudang,
                w.warehouse_id
            ')
            ->join('supplier s', 's.supplier_id = po.supplier_id')
            ->join('warehouse w', 'w.warehouse_id = po.warehouse_id')
            ->where('po.status', 'sent'); // Filter status utama

        // Tambahkan filter gudang HANYA jika warehouseId ada isinya
        if (!empty($warehouseId)) {
            $builder->where('po.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('po.order_date', 'DESC')
                    ->get()
                    ->getResultArray();
    }

}