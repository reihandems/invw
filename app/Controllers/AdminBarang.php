<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AdminBarangModel;

class AdminBarang extends BaseController {
    protected $adminBarangModel;

    public function __construct()
    {
        $this->adminBarangModel = new AdminBarangModel();
    }

    public function ajaxList() {
        $barang = $this->adminBarangModel;
        $list = $barang->select('barang.barang_id, barang.nama_barang, barang.satuan, barang.harga, kategori.nama_kategori')
        ->join('kategori', 'kategori.kategori_id = barang.kategori_id', 'left')
        ->get()
        ->getResultArray();;
        $no = 0;
        $data = [];
        foreach ($list as $barang) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $barang['nama_barang'];
            $row[] = $barang['nama_kategori'];
            $row[] = $barang['satuan'];
            $row[] = 'Rp ' . number_format($barang['harga'], 0, ',', '.');

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $barang['barang_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$barang['barang_id'].'">Hapus</a>';

            $data[] = $row;

        }
        return $this->response->setJSON($data);
    }

    // Metode untuk tambah / update data (Create/Update)
    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'nama_barang' => 'required',
            'kategori_id' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false, 
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil hanya fields yang dibutuhkan
        $fields = [
            'nama_barang',
            'kategori_id',
            'satuan',
            'harga'
        ];

        $data =  $this->request->getPost($fields);
        $id = $this->request->getPost('barang_id');

        if ($id) {
            // Update
            $this->adminBarangModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminBarangModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON(['status' => true, 'msg' => $msg]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getBarang($id = null) {
        $data = $this->adminBarangModel->find($id);
        return $this->response->setJSON($data);
    }


    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->adminBarangModel->delete($id)) {
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