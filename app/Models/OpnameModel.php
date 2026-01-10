<?php

namespace App\Models;

use CodeIgniter\Model;

class OpnameModel extends Model {
    protected $table = 'opname_schedule';
    protected $primaryKey = 'opname_id';
    protected $allowedFields = ['nama_jadwal', 'jenis', 'tanggal_mulai', 'tanggal_berakhir', 'keterangan', 'status', 'created_by', 'created_at', 'updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = true;

    public function getScheduledOpname($warehouseId = null) {
        $builder = $this->select('
                opname_schedule.nama_jadwal,
                opname_schedule.jenis,
                opname_schedule.tanggal_mulai,
                opname_schedule.tanggal_berakhir,
                opname_schedule.keterangan,
                opname_schedule.status
            ')
            ->join('opname_detail','opname_detail.opname_id = opname_schedule.opname_id', 'left')
            ->join('warehouse', 'warehouse.warehouse_id = opname_schedule.warehouse_id', 'left')
            ->where('status', 'scheduled');

        // JIKA warehouseId dikirim, maka filter datanya
        if ($warehouseId !== null) {
            $builder->where('opname_schedule.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('opname_schedule.tanggal_berakhir', 'ASC')
                    ->findAll();
    }

    public function getApprovalOpname($warehouseId = null) {
        $builder = $this->select('
                    opname_schedule.opname_id, 
                    opname_schedule.nama_jadwal,
                    opname_schedule.jenis,
                    opname_schedule.tanggal_mulai,
                    opname_schedule.tanggal_berakhir,
                    opname_schedule.keterangan,
                    opname_schedule.status
                ')
                ->join('opname_detail','opname_detail.opname_id = opname_schedule.opname_id', 'left')
                ->join('warehouse', 'warehouse.warehouse_id = opname_schedule.warehouse_id', 'left')
                ->where('opname_schedule.status', 'submitted')
                // TAMBAHKAN GROUP BY DISINI
                ->groupBy('opname_schedule.opname_id'); 

        if ($warehouseId !== null) {
            $builder->where('opname_schedule.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('opname_schedule.tanggal_berakhir', 'ASC')->findAll();
    }

    public function getRejectedOpname($warehouseId = null) {
        $builder = $this->select('
                    opname_schedule.opname_id, 
                    opname_schedule.nama_jadwal,
                    opname_schedule.jenis,
                    opname_schedule.tanggal_mulai,
                    opname_schedule.tanggal_berakhir,
                    opname_schedule.keterangan,
                    opname_schedule.status
                ')
                ->join('opname_detail','opname_detail.opname_id = opname_schedule.opname_id', 'left')
                ->join('warehouse', 'warehouse.warehouse_id = opname_schedule.warehouse_id', 'left')
                ->where('opname_schedule.status', 'rejected')
                // TAMBAHKAN GROUP BY DISINI
                ->groupBy('opname_schedule.opname_id'); 

        if ($warehouseId !== null) {
            $builder->where('opname_schedule.warehouse_id', $warehouseId);
        }

        return $builder->orderBy('opname_schedule.tanggal_berakhir', 'ASC')->findAll();
    }
}