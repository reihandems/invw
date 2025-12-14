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

$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    // ADMIN - DASHBOARD
    $routes->get('dashboard', 'Page::dashboardAdmin');
    $routes->get('dashboard/activityloglist', 'AdminDashboard::logActivityList');

    // ADMIN - BARANG
    $routes->get('barang', 'Page::barangAdmin');
    $routes->get('barang/ajaxlist', 'AdminBarang::ajaxList');
    $routes->post('barang/save', 'AdminBarang::save');
    $routes->get('barang/getBarang/(:num)', 'AdminBarang::getBarang/$1');
    $routes->post('barang/deleteData/(:num)', 'AdminBarang::deleteData/$1');

    // ADMIN - SUPPLIER
    $routes->get('supplier', 'Page::supplierAdmin');
    $routes->post('supplier/getRegencies', 'RegionController::getRegencies');
});

$routes->group('manager', ['filter' => 'role:manager'], function ($routes) {
    $routes->get('dashboard', 'Page::dashboardManager');
});

$routes->group('gudang', ['filter' => 'role:gudang'], function ($routes) {
    $routes->get('dashboard', 'Page::dashboardGudang');
});

$routes->group('purchasing', ['filter' => 'role:purchasing'], function ($routes) {
    $routes->get('dashboard', 'Page::dashboardPurchasing');
});