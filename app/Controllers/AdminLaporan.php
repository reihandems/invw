<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminLaporanModel;

class AdminLaporan extends BaseController
{
    protected $laporan;

    public function __construct()
    {
        $this->laporan = new AdminLaporanModel();
    }

    public function data()
    {
        $jenis  = $this->request->getPost('jenis_laporan');
        $awal   = $this->request->getPost('tanggal_awal');
        $akhir  = $this->request->getPost('tanggal_akhir');

        $data = [];

        if ($jenis === 'barang') {
            $rows = $this->laporan->laporanBarang($awal, $akhir);
            $no = 1;
            foreach ($rows as $r) {
                $data[] = [
                    $no++,
                    $r['nama_barang'],
                    $r['total_masuk'],
                    $r['total_keluar'],
                    $r['total_masuk'] - $r['total_keluar']
                ];
            }
        }

        if ($jenis === 'stok') {
            $rows = $this->laporan->laporanStok($awal, $akhir);
            $no = 1;
            foreach ($rows as $r) {
                $data[] = [
                    $no++,
                    $r['nama_barang'],
                    $r['stok_sistem'],
                    $r['stok_fisik'],
                    $r['selisih']
                ];
            }
        }

        if ($jenis === 'purchasing') {
            $rows = $this->laporan->laporanPurchasing($awal, $akhir);
            $no = 1;
            foreach ($rows as $r) {
                $data[] = [
                    $no++,
                    $r['nama_supplier'],
                    $r['tanggal_order'],
                    $r['status'],
                    number_format($r['total_harga'],0,',','.')
                ];
            }
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }
}

