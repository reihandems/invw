<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminLaporanModel;

class AdminLaporan extends BaseController
{
    protected $adminLaporanModel;

    public function __construct()
    {
        $this->adminLaporanModel = new AdminLaporanModel();
    }

    /**
     * Halaman laporan (default)
     */

    /**
     * Ambil data laporan (AJAX)
     */
    public function getLaporan()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $tanggalAwal  = $this->request->getPost('tanggal_awal');
        $tanggalAkhir = $this->request->getPost('tanggal_akhir');

        // Validasi sederhana
        if (!$tanggalAwal || !$tanggalAkhir) {
            return $this->response->setJSON([
                'status' => false,
                'msg'    => 'Tanggal awal dan akhir wajib diisi'
            ]);
        }

        $data = $this->adminLaporanModel->getLaporanBarang($tanggalAwal, $tanggalAkhir);

        return $this->response->setJSON([
            'status' => true,
            'data'   => $data
        ]);
    }
}
