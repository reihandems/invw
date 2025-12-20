<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class AdminUser extends BaseController {
    protected $adminUserModel;

    public function __construct() {
        $this->adminUserModel = new AdminUserModel();
    }

    public function ajaxList() {
        $user = $this->adminUserModel;
        $list = $user->select('
            user.user_id,
            user.username,
            user.nama_lengkap,
            user.email,
            user.password,
            roles.nama_role,
            user.gambar
        ')
        ->join('roles', 'roles.role_id = user.role_id', 'left')
        ->get()
        ->getResultArray();
        $no = 0;
        $data = [];
        foreach ($list as $user) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $user['nama_lengkap'];
            $row[] = $user['username'];
            $row[] = $user['email'];
            $row[] = $user['password'];
            $row[] = $user['nama_role'];
            $row[] = $user['gambar'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $user['user_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$user['user_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'username' => 'required|min_length[8]',
            'email' => 'required',
            'password' => 'required',
            'nama_role' => 'required',
            'gambar' => 'mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar, 2048]|is_image[gambar]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false, 
                'errors' => $this->validator->getErrors()
            ]);
        }

        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = '';

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/', $namaGambar);
        }

        // Ambil hanya fields yang dibutuhkan
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('nama_lengkap'),
            'password' => $this->request->getPost('nama_lengkap'),
            'role_id' => $this->request->getPost('nama_lengkap'),
            'gambar' => $namaGambar
        ];

        $id = $this->request->getPost('user_id');

        if ($id) {
            // Update
            $this->adminUserModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminUserModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON(['status' => true, 'msg' => $msg]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getUser($id = null) {
        $data = $this->adminUserModel->find($id);
        return $this->response->setJSON($data);
    }

    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->adminUserModel->delete($id)) {
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