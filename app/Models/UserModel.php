<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $tabel = 'user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['username', 'nama_lengkap', 'email', 'password', 'role'];
}