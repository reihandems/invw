<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\POModel;
use App\Models\PRModel;

class PurchasingDashboard extends BaseController {
    protected $poModel;

    public function index() {
        $poModel = new POModel();
        $prModel = new PRModel();

        $data = [
            'menu' => 'dashboard',
            'pageTitle' => 'Dashboard',
            'totalPOToday' => $poModel->countTodayPO(),
            'totalPOMonth' => $poModel->countMonthlyPO(),
            'poActive' => $poModel->countActivePO(),
            'prWaiting' => $prModel->countWaitingApproval()
        ];

        return view('pages/purchasing/view_dashboard', $data);
    }

    public function __construct()
    {
        $this->poModel = new POModel();
    }

    public function latestPO() {
        $list = $this->poModel->getLatestPO(5);

        $data = [];
        foreach ($list as $po) {
            $row = [];
            $row[] = $po['po_number'];
            $row[] = $po['supplier_id'];
            $row[] = $po['warehouse_id'];
            $row[] = $po['status'];
            $row[] = $po['created_at'];

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }
}