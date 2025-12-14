<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\BarangModel;

class Barang extends BaseController {
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    public function ajaxList() {
        $barang = $this->barangModel;
        $list = $barang->findAll();
        $no = 0;
        $data = [];
        foreach ($list as $barang) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $barang['namaBarang'];
            $row[] = 'Rp ' . number_format($barang['hargaBarang'], 0, ',', '.');

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn btn-sm btn-info edit-btn" data-id="'. $barang['id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn btn-sm btn-error delete-btn" data-id="'.$barang['id'].'">Hapus</a>';

            $data[] = $row;

        }
        return $this->response->setJSON($data);
    }
}