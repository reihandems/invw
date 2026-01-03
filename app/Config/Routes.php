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
    $routes->get('dashboard', 'AdminDashboard::index');
    $routes->get('dashboard/activityloglist', 'AdminDashboard::activityLogList');

    // ADMIN - BARANG
    $routes->get('barang', 'Page::barangAdmin');
    $routes->get('barang/ajaxlist', 'AdminBarang::ajaxList');
    $routes->post('barang/save', 'AdminBarang::save');
    $routes->get('barang/getBarang/(:num)', 'AdminBarang::getBarang/$1');
    $routes->post('barang/deleteData/(:num)', 'AdminBarang::deleteData/$1');

    // ADMIN - SUPPLIER
    $routes->get('supplier', 'Page::supplierAdmin');
    $routes->get('supplier/ajaxlist', 'AdminSupplier::ajaxList');
    $routes->post('supplier/save', 'AdminSupplier::save');
    $routes->get('supplier/getSupplier/(:num)', 'AdminSupplier::getSupplier/$1');
    $routes->post('supplier/deleteData/(:num)', 'AdminSupplier::deleteData/$1');

    // ADMIN - USER
    $routes->get('user', 'Page::userAdmin');
    $routes->get('user/ajaxlist', 'AdminUser::ajaxList');
    $routes->post('user/save', 'AdminUser::save');
    $routes->get('user/getUser/(:num)', 'AdminUser::getUser/$1');
    $routes->post('user/deleteData/(:num)', 'AdminUser::deleteData/$1');

    // ADMIN - GUDANG
    $routes->get('gudang', 'Page::gudangAdmin');
    $routes->get('gudang/ajaxlist', 'AdminGudang::ajaxList');
    $routes->post('gudang/save', 'AdminGudang::save');
    $routes->get('gudang/getGudang/(:num)', 'AdminGudang::getGudang/$1');
    $routes->post('gudang/deleteData/(:num)', 'AdminGudang::deleteData/$1');

    // ADMIN - RAK
    $routes->get('rak', 'Page::rakAdmin');
    $routes->get('rak/ajaxlist', 'AdminRak::ajaxList');
    $routes->post('rak/save', 'AdminRak::save');
    $routes->get('rak/getRak/(:num)', 'AdminRak::getRak/$1');
    $routes->post('rak/deleteData/(:num)', 'AdminRak::deleteData/$1');

    // ADMIN - LAPORAN
    $routes->get('laporan', 'Page::laporanAdmin');
    $routes->post('laporan/data', 'AdminLaporan::data');
});

$routes->group('purchasing', ['filter' => 'role:purchasing'], function ($routes) {
    // PURCHASING - DASHBOARD
    $routes->get('dashboard', 'PurchasingDashboard::index');
    $routes->get('dashboard/latest-po', 'PurchasingDashboard::latestPO');

    // PURCHASING - PR
    $routes->get('purchase-request', 'PurchasingPR::index');
    $routes->get('purchase-request/ajaxlist', 'PurchasingPR::ajaxList');

    // PURCHASING - PO
    $routes->get('purchase-order', 'PurchasingPO::index');
    $routes->get('purchase-order/ajaxlist', 'PurchasingPO::ajaxList');

    // PURCHASING - SUPPLIER
    $routes->get('supplier', 'PurchasingSupplier::index');
    $routes->get('supplier/ajaxlist', 'PurchasingSupplier::ajaxList');

});

$routes->group('gudang', ['filter' => 'role:gudang'], function ($routes) {
    $routes->get('dashboard', 'Gudang\GudangDashboard::index');

    // GUDANG - PR
    $routes->get('purchase-request', 'Gudang\GudangPR::index');
    $routes->get('purchase-request/ajaxlist', 'Gudang\GudangPR::ajaxList');
    $routes->get('purchase-request/create', 'Gudang\GudangPR::create');
    $routes->post('purchase-request/store', 'Gudang\GudangPR::store');
    $routes->get('purchase-request/detail/(:num)', 'Gudang\GudangPR::detail/$1');
});

$routes->group('manager', ['filter' => 'role:manager'], function ($routes) {
    $routes->get('dashboard', 'Page::dashboardManager');

    // MANAGER - PR
    $routes->get('purchase-request', 'Manager\ManagerPR::index');
    $routes->get('purchase-request/ajaxlist', 'Manager\ManagerPR::ajaxList');
    $routes->get('purchase-request/detail/(:num)', 'Manager\ManagerPR::detail/$1');
    $routes->post('purchase-request/approve/(:num)', 'Manager\ManagerPR::approve/$1');
    $routes->post('purchase-request/reject/(:num)', 'Manager\ManagerPR::reject/$1');
});

