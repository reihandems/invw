<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinceModel extends Model {
    protected $table = 'reg_provinces';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}