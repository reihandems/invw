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
}