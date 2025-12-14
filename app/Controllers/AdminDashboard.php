<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class AdminDashboard extends BaseController {
    protected $activityLogModel;

    public function __construct() {
        $this->activityLogModel = new ActivityLogModel();
    }

    public function logActivityList() {
        $activityLog = $this->activityLogModel;
        $list = $activityLog->findAll();
        $data = [];
        foreach ($list as $activityLog) {
            $row = [];
            $row[] = $activityLog['user_id'];
            $row[] = $activityLog['role'];
            $row[] = $activityLog['activity_type'];
            $row[] = $activityLog['references_table'];
            $row[] = $activityLog['references_id'];
            $row[] = $activityLog['description'];
            $row[] = $activityLog['created_at'];

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }
}