<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['nama_lengkap', 'username', 'email', 'password', 'role_id', 'warehouse_id', 'gambar',];
    protected $returnType = 'array';

    public function getTotalUser() {
        return $this->countAllResults();
    }

    public function getUserWithRoleByEmail($email){
    return $this->db->table('users u')
        ->select('
            u.user_id,
            u.nama_lengkap,
            u.email,
            u.password,
            u.warehouse_id,
            r.nama_role,
            w.nama_gudang,
            u.gambar
        ')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->join('warehouse w', 'w.warehouse_id = u.warehouse_id', 'left')
        ->where('u.email', $email)
        ->get()
        ->getRowArray();
}

}