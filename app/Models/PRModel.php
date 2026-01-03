<?php

namespace App\Models;

use CodeIgniter\Model;

class PRModel extends Model {
    protected $DBGroup = 'default';
    protected $table = 'purchase_request';
    protected $primaryKey = 'pr_id';
    protected $allowedFields = ['pr_number', 'warehouse_id', 'user_id', 'status', 'request_date', 'approved_by', 'approved_at', 'notes', 'created_at', 'updated_at', 'po_created'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function countWaitingApproval()
    {
        return $this->where('status', 'submitted')
                    ->countAllResults();
    }

    public function approvedPR() {
        return $this->where('status', 'approved')
            ->findAll();
    }

    // Gudang
    public function getByGudang($userId){
        return $this->select('
                purchase_request.pr_id,
                purchase_request.pr_number,
                purchase_request.status,
                purchase_request.created_at,
                warehouse.nama_gudang
            ')
            ->join('warehouse', 'warehouse.warehouse_id = purchase_request.warehouse_id')
            ->where('purchase_request.user_id', $userId)
            ->orderBy('purchase_request.created_at', 'DESC')
            ->findAll();
    }

    // Manager
    public function getSubmittedPR(){
        return $this->select('
                purchase_request.pr_id,
                purchase_request.pr_number,
                purchase_request.created_at,
                warehouse.nama_gudang,
                users.nama_lengkap AS created_by
            ')
            ->join('warehouse', 'warehouse.warehouse_id = purchase_request.warehouse_id')
            ->join('users', 'users.user_id = purchase_request.user_id')
            ->where('purchase_request.status', 'submitted')
            ->orderBy('purchase_request.created_at', 'ASC')
            ->findAll();
    }

    public function approvePR($prId, $managerId){
        return $this->update($prId, [
            'status'       => 'approved',
            'approved_by'  => $managerId,
            'approved_at'  => date('Y-m-d H:i:s')
        ]);
    }

    public function rejectPR($prId){
        return $this->update($prId, [
            'status' => 'rejected'
        ]);
    }

    // Purchasing
    public function getApprovedWithoutPO(){
        return $this->select('
                purchase_request.pr_id,
                purchase_request.pr_number,
                purchase_request.approved_at,
                warehouse.nama_gudang
            ')
            ->join('warehouse', 'warehouse.warehouse_id = purchase_request.warehouse_id')
            ->where('purchase_request.status', 'approved')
            ->where('purchase_request.po_created', 0)
            ->orderBy('purchase_request.approved_at', 'ASC')
            ->findAll();
    }

    public function markAsPOCreated($prId){
        return $this->update($prId, [
            'po_created' => 1
        ]);
    }



    // All
    public function getDetailPR($prId){
        return $this->select('
                purchase_request.*,
                warehouse.nama_gudang,
                users.nama_lengkap AS created_by,
                manager.nama_lengkap AS approved_by_name
            ')
            ->join('warehouse', 'warehouse.warehouse_id = purchase_request.warehouse_id')
            ->join('users', 'users.user_id = purchase_request.user_id')
            ->join('users manager', 'manager.user_id = purchase_request.approved_by', 'left')
            ->where('purchase_request.pr_id', $prId)
            ->first();
    }

    public function getDetailItems($prId){
        return $this->db->table('purchase_request_detail')
            ->select('
                purchase_request_detail.qty,
                barang.nama_barang,
                barang.harga
            ')
            ->join('barang', 'barang.barang_id = purchase_request_detail.barang_id')
            ->where('purchase_request_detail.pr_id', $prId)
            ->get()
            ->getResultArray();
    }

    public function generatePRNumber(){
        $date = date('Ymd');
        $count = $this->where('DATE(created_at)', date('Y-m-d'))->countAllResults();

        return 'PR-' . $date . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

}