<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model {
    protected $table = 'activity_log';
    protected $primaryKey = 'log_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'role',
        'activity_type',
        'reference_table',
        'reference_id',
        'description',
        'created_at'
    ];

    public function getActivityLog()
    {
        return $this->db->table('activity_log al')
        ->select('
            al.log_id,
            u.nama_lengkap AS user_name,
            al.role,
            al.activity_type,
            al.reference_table,
            al.description,
            al.created_at
        ')
        ->join('users u', 'u.user_id = al.user_id', 'left')
        ->orderBy('al.created_at', 'DESC')
        ->get()
        ->getResultArray();
    }
}