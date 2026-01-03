<?php

namespace App\Models;

use CodeIgniter\Model;

class POModel extends Model {
    protected $table = 'purchase_order';
    protected $primaryKey = 'po_id';
    protected $allowedFields = ['po_number', 'warehouse_id', 'pr_id', 'supplier_id', 'purchasing_user_id', 'order_date', 'status', 'total_amount', 'created_at'];
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
}