<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Models\PRModel;

class ManagerPR extends BaseController {
    protected $prModel;

    public function index() {
        $data = [
            'menu' => 'pr',
            'pageTitle' => 'Purchase Request (PR)'
        ];

        return view('pages/manager/view_pr', $data);
    }

    public function __construct()
    {
        $this->prModel = new PRModel();
    }

    public function ajaxList() {
        $pr = $this->prModel;
        $list = $pr->getSubmittedPR();

        $no = 0;
        $data = [];
        foreach ($list as $pr) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $pr['pr_number'];
            $row[] = $pr['nama_gudang'];
            $row[] = $pr['created_by'];
            $row[] = $pr['created_at'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $pr['pr_id'].'">Review</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function approve($id)
    {
        $model = new PRModel();

        $model->approvePR($id, session('user_id'));

        return redirect()->back()->with('success', 'PR disetujui');
    }

}