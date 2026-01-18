<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

use App\Controllers\AuthController;

// AUTENTIKASI - LOGIN
$routes->get('/', 'Page::login');
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
    $routes->get('laporan/export-pdf', 'AdminLaporan::exportPDF');
});

$routes->group('purchasing', ['filter' => 'role:purchasing'], function ($routes) {
    // PURCHASING - DASHBOARD
    $routes->get('dashboard', 'PurchasingDashboard::index');
    $routes->get('dashboard/latest-po', 'PurchasingDashboard::latestPO');

    // PURCHASING - PR
    $routes->get('purchase-request', 'PurchasingPR::index');
    $routes->get('purchase-request/ajaxlist', 'PurchasingPR::ajaxList');
    $routes->get('purchase-request/detail/(:num)', 'PurchasingPR::detail/$1');

    // PURCHASING - PO
    $routes->get('purchase-order', 'PurchasingPO::index');
    $routes->get('purchase-order/ajaxlist', 'PurchasingPO::ajaxList');
    $routes->get('purchase-order/create/(:num)', 'PurchasingPO::create/$1');
    $routes->post('purchase-order/store', 'PurchasingPO::store');
    $routes->get('purchase-order/detail/(:num)', 'PurchasingPO::detail/$1');
    $routes->post('purchase-order/update-status-sent/(:num)', 'PurchasingPO::updateStatusSent/$1');
    $routes->get('purchase-order/print/(:num)', 'PurchasingPO::printPDF/$1');

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
    $routes->get('gudang/purchase-request/generate-number', 'Gudang\GudangPR::generatePRNumber');
    $routes->post('purchase-request/get-barang', 'Gudang\GudangPR::getBarang');
    $routes->get('purchase-request/get-stok', 'Gudang\GudangPR::getStok');
    $routes->post('purchase-request/store', 'Gudang\GudangPR::store');
    $routes->get('purchase-request/detail/(:num)', 'Gudang\GudangPR::detail/$1');

    // GUDANG - PO
    $routes->get('purchase-order', 'Gudang\GudangPO::index');
    $routes->get('purchase-order/ajaxlist', 'Gudang\GudangPO::ajaxList');
    $routes->get('purchase-order/detail-po/(:num)', 'Gudang\GudangPO::getPODetail/$1');
    $routes->get('purchase-order/get-racks/(:num)', 'Gudang\GudangPO::getRacks/$1');
    $routes->post('purchase-order/save', 'Gudang\GudangPO::save');

    // GUDANG - BARANG MASUK
    $routes->get('barang-masuk', 'Gudang\GudangBarangMasuk::index');
    $routes->get('barang-masuk/ajaxlist', 'Gudang\GudangBarangMasuk::ajaxList');
    $routes->get('barang-masuk/get-detail/(:num)', 'Gudang\GudangBarangMasuk::getDetail/$1');

    // GUDANG - BARANG KELUAR
    $routes->get('barang-keluar', 'Gudang\GudangBarangKeluar::index');
    $routes->get('barang-keluar/ajaxlist', 'Gudang\GudangBarangKeluar::ajaxList');
    $routes->get('barang-keluar/get-racks', 'Gudang\GudangBarangKeluar::getRacks');
    $routes->post('barang-keluar/save', 'Gudang\GudangBarangKeluar::save');
    $routes->get('barang-keluar/detail/(:num)', 'Gudang\GudangBarangKeluar::detail/$1');
    $routes->get('barang-keluar/cetak-surat-jalan/(:num)', 'Gudang\GudangBarangKeluar::cetakSuratJalan/$1');
    

    // GUDANG - LAPORAN STOK
    $routes->get('laporan-stok', 'Gudang\GudangLaporanStok::index');
    $routes->get('laporan-stok/ajaxlist', 'Gudang\GudangLaporanStok::ajaxList');
    $routes->get('laporan-stok/export-pdf', 'Gudang\GudangLaporanStok::exportPDF');

    // GUDANG - STOK OPNAME
    $routes->get('opname', 'Gudang\GudangOpname::index');
    $routes->get('opname/ajaxlist', 'Gudang\GudangOpname::ajaxList');
    $routes->get('opname/detail/(:num)', 'Gudang\GudangOpname::getDetail/$1');
    $routes->get('opname/get-items/(:num)', 'Gudang\GudangOpname::getItems/$1');
    $routes->post('opname/submit-fisik', 'Gudang\GudangOpname::submitFisik/$1');

    $routes->get('opname/approval', 'Gudang\GudangOpnameApproval::index');
    $routes->get('opname/approval/ajaxlist', 'Gudang\GudangOpnameApproval::ajaxList');
    $routes->get('opname/approval/detail/(:num)', 'Gudang\GudangOpnameApproval::getDetail/$1');

    $routes->get('opname/rejected', 'Gudang\GudangOpnameRejected::index');
    $routes->get('opname/rejected/ajaxlist', 'Gudang\GudangOpnameRejected::ajaxList');
    $routes->get('opname/rejected/detail/(:num)', 'Gudang\GudangOpnameRejected::getDetail/$1');

    $routes->get('opname/finished', 'Gudang\GudangOpnameFinished::index');
    $routes->get('opname/finished/ajaxlist', 'Gudang\GudangOpnameFinished::ajaxList');
    $routes->get('opname/finished/detail/(:num)', 'Gudang\GudangOpnameFinished::getDetail/$1');

});

$routes->group('manager', ['filter' => 'role:manager'], function ($routes) {
    // MANAGER - DASHBOARD
    $routes->get('dashboard', 'Page::dashboardManager');

    // MANAGER - PR
    $routes->get('purchase-request', 'Manager\ManagerPR::index');
    $routes->get('purchase-request/ajaxlist', 'Manager\ManagerPR::ajaxList');
    $routes->get('purchase-request/detail/(:num)', 'Manager\ManagerPR::detail/$1');
    $routes->post('purchase-request/approve', 'Manager\ManagerPR::approve');
    $routes->post('purchase-request/reject', 'Manager\ManagerPR::reject');

    // MANAGER - STOK OPNAME
    $routes->get('opname', 'Manager\ManagerOpname::index');
    $routes->get('opname/ajaxlist', 'Manager\ManagerOpname::ajaxList');
    $routes->get('opname/get-racks-by-warehouse/(:num)', 'Manager\ManagerOpname::getRacksByWarehouse/$1');
    $routes->post('opname/save-schedule', 'Manager\ManagerOpname::saveSchedule');
    $routes->get('opname/detail/(:num)', 'Manager\ManagerOpname::getDetail/$1');
    $routes->post('opname/deleteData/(:num)', 'Manager\ManagerOpname::deleteData/$1');
    $routes->post('opname/update-status', 'Manager\ManagerOpname::updateStatus');
});

