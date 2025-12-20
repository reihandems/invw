<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model {
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $returnType = 'array';
    public function getUserWithRoleByEmail($email)
    {
        return $this->db->table('user u')
            ->select('
                u.user_id,
                u.nama_lengkap,
                u.email,
                u.password,
                r.nama_role
            ')
            ->join('roles r', 'r.role_id = u.role_id')
            ->where('u.email', $email)
            ->get()
            ->getRowArray();
    }
}