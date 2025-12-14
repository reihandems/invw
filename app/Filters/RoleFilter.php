<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null) {
        // Belum login
        if (!session()->get('logged_in')) {
            return redirect()->to('login');
        }

        // Cek role
        if ($arguments && !in_array(session()->get('user_role'), $arguments)) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        //
    }
}