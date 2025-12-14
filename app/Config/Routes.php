<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

use App\Controllers\AuthController;

// AUTENTIKASI - LOGIN
$routes->get('/login', 'Page::login');
$routes->post('/login/process', 'Auth::loginProcess');
$routes->get('/logout', 'Auth::logout');

$routes->get('/dashboard', function() {
    if (!session()->get('logged_in')) {
        return redirect()->to('/login');
    }
    return view('pages/admin/view_dashboard');
});

// ADMIN - DASHBOARD
$routes->get('/admin/dashboard', 'Page::dashboardAdmin');

// ADMIN - BARANG
$routes->get('/admin/barang', 'Page::barangAdmin');
$routes->get('/admin/barang/ajaxlist', 'Admin::ajaxList');
$routes->post('/admin/barang/save', 'Admin::save');
$routes->get('/admin/barang/getBarang/(:num)', 'Admin::getBarang/$1');
$routes->post('/admin/barang/deleteData/(:num)', 'Admin::deleteData/$1');

// ADMIN - SUPPLIER
$routes->get('/admin/supplier', 'Page::supplierAdmin');
$routes->post('/admin/supplier/getRegencies', 'RegionController::getRegencies');