<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AdminGudangModel;

class AdminGudang extends BaseController {
    protected $adminGudangModel;

    public function __construct() {
        $this->adminGudangModel = new AdminGudangModel();
    }

    public function ajaxList() {
        $gudang = $this->adminGudangModel;
        $list = $gudang->findAll();
        $no = 0;
        $data = [];
        foreach ($list as $gudang) {
            $no++;
            $row = [];
            $row[] = $no++;
            $row[] = $gudang['nama_gudang'];
            $row[] = $gudang['lokasi'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $gudang['warehouse_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$gudang['warehouse_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    // Metode untuk tambah / update data (Create/Update)
    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'nama_gudang' => 'required|min_length[3]',
            'lokasi' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil hanya fields yang dibutuhkan
        $fields = [
            'nama_gudang',
            'lokasi'
        ];

        $data = $this->request->getPost($fields);
        $id = $this->request->getPost('warehouse_id');

        if ($id) {
            // Update
            $this->adminGudangModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminGudangModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON([
            'status' => true,
            'msg' => $msg
        ]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getGudang($id = null) {
        $data = $this->adminGudangModel->find($id);
        return $this->response->setJSON($data);
    }

    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->adminGudangModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'msg' => 'Data berhasil dihapus!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'msg' => 'Gagal menghapus data!'
            ]);
        }
    }
}