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
            users.user_id,
            users.username,
            users.nama_lengkap,
            users.email,
            roles.nama_role,
            users.gambar
        ')
        ->join('roles', 'roles.role_id = users.role_id', 'left')
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
            $row[] = $user['nama_role'];
            $row[] = '<img src="'.base_url('uploads/'.$user['gambar']).'" width="50">';

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $user['user_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$user['user_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function save() {
        $validation = \Config\Services::validation();
        $id = $this->request->getPost('user_id');
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'username' => 'required|min_length[5]',
            'email' => 'required|valid_email',
            'role_id' => 'required|integer',
            'gambar' => 'permit_empty|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]'
        ];

        // password hanya wajib saat CREATE
        if (!$id) {
            $rules['password'] = 'required|min_length[6]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false, 
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil hanya fields yang dibutuhkan
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
        ];

        // password OPTIONAL
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            );
        }

        // upload gambar OPTIONAL
        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaGambar = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $namaGambar);
            $data['gambar'] = $namaGambar;
        }

        if ($id) {
            // Update
            $this->adminUserModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminUserModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON([
            'status' => true, 
            'msg' => $msg,
        ]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getUser($id = null) {
        $data = $this->adminUserModel->
                select('user_id, nama_lengkap, username, email, role_id, gambar')
                ->find($id);
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