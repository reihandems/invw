<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AdminSupplierModel;

class AdminSupplier extends BaseController {
    protected $adminSupplierModel;

    public function __construct() {
        $this->adminSupplierModel = new AdminSupplierModel();
    }

    public function ajaxList() {
        $supplier = $this->adminSupplierModel;
        $list = $supplier->findAll();
        $no = 0;
        $data = [];
        foreach ($list as $supplier) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $supplier['nama_supplier'];
            $row[] = $supplier['kontak'];
            $row[] = $supplier['alamat'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $supplier['supplier_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$supplier['supplier_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    // Metode untuk tambah / update data (Create/Update)
    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'nama_supplier' => 'required',
            'kontak' => 'required',
            'alamat' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil hanya fields yang dibutuhkan
        $fields = [
            'nama_supplier',
            'kontak',
            'alamat'
        ];

        $data = $this->request->getPost($fields);
        $id = $this->request->getPost('supplier_id');

        if ($id) {
            // Update
            $this->adminSupplierModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminSupplierModel->insert($data);
            $msg = "Data berhasil ditambahkan";
        }

        return $this->response->setJSON([
            'status' => true,
            'msg' => $msg
        ]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getSupplier($id = null) {
        $data = $this->adminSupplierModel->find($id);
        return $this->response->setJSON($data);
    }

    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->adminSupplierModel->delete($id)) {
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