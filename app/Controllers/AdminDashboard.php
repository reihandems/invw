<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class AdminDashboard extends BaseController {
    protected $activityLogModel;

    public function __construct() {
        $this->activityLogModel = new ActivityLogModel();
    }

    public function activityLogList() {
        $list = $this->activityLogModel->getActivityLog();

        $data = [];
        foreach ($list as $log) {
            $row = [];
            $row[] = $log['user_name'];
            $row[] = $log['role'];
            $row[] = $log['activity_type'];
            $row[] = $log['reference_table'];
            $row[] = $log['description'];
            $row[] = $log['created_at'];

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }
}