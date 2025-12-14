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

    public function loginProcess() {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id' => $user['id'],
                'user_nama' => $user['nama_lengkap'],
                'user_email' => $user['email'],
                'logged_in' => true
            ]);
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Email atau Password salah.')->withInput();
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }
}