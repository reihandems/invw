<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use App\Models\ManagerLaporanModel;

class ManagerLaporan extends BaseController
{
    public function index()
    {
        $data = [
            'menu' => 'laporan', // for sidebar active state
            'pageTitle' => 'Laporan Management'
        ];
        return view('pages/manager/view_laporan', $data);
    }

    public function listData()
    {
        $managerLaporan = new ManagerLaporanModel();
        $data = $managerLaporan->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }

    public function download($id)
    {
        $managerLaporan = new ManagerLaporanModel();
        $report = $managerLaporan->find($id);

        if (!$report) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan');
        }

        $path = 'uploads/laporan/' . $report['file_path']; // Assuming public/uploads/laporan

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        return $this->response->download($path, null)->setFileName($report['judul'] . '.pdf');
    }
}
