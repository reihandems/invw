<?php 

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController {
    public function login() {
        if (session()->get('logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('auth/login');
    }

    public function loginProcess(){
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Ambil user + role
        $user = $model->getUserWithRoleByEmail($email);

        // Validasi login
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Email atau Password salah.')
                ->withInput();
        }

        // Set session
        $session->set([
            'user_id'    => $user['user_id'],
            'user_nama'  => $user['nama_lengkap'],
            'user_email' => $user['email'],
            'user_role'  => strtolower($user['nama_role']),
            'logged_in'  => true
        ]);

        // Redirect berdasarkan role
        switch (strtolower($user['nama_role'])) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'manager':
                return redirect()->to('/manager/dashboard');
            case 'gudang':
                return redirect()->to('/gudang/dashboard');
            case 'purchasing':
                return redirect()->to('/purchasing/dashboard');
            default:
                session()->destroy();
                return redirect()->to('/login')
                    ->with('error', 'Role tidak dikenali');
        }
    }


    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }
}