<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;

class GudangDashboard extends BaseController {
    public function index() {
        $data = [
            'menu' => 'dashboard',
            'pageTitle' => 'Dashboard'
        ];

        return view('pages/gudang/view_dashboard', $data);
    }
}