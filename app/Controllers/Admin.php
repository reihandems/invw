<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\BarangModel;

class Admin extends BaseController {
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
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $barang['id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$barang['id'].'">Hapus</a>';

            $data[] = $row;

        }
        return $this->response->setJSON($data);
    }

    // Metode untuk tambah / update data (Create/Update)
    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'namaBarang' => 'required',
            'hargaBarang' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => false, 'errors' => $this->validator->getErrors()]);
        }

        $data = [
            'namaBarang' => $this->request->getPost('namaBarang'),
            'hargaBarang' => $this->request->getPost('hargaBarang')
        ];

        $id = $this->request->getPost('id');

        if ($id) {
            // Update
            $this->barangModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->barangModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON(['status' => true, 'msg' => $msg]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getBarang($id = null) {
        $data = $this->barangModel->find($id);
        return $this->response->setJSON($data);
    }


    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->barangModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'msg' => 'Data Berhasil Dihapus!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'msg' => 'Gagal menghapus data!'
            ]);
        }
    }
}