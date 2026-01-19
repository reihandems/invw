<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\ProductStockLocationModel;
use App\Models\BarangMasukModel;
use App\Models\BarangKeluarModel;

class GudangDashboard extends BaseController
{
    public function index()
    {
        $warehouseId = session()->get('warehouse_id');

        $stockModel = new ProductStockLocationModel();
        $masukModel = new BarangMasukModel();
        $keluarModel = new BarangKeluarModel();

        $year = date('Y');
        $month = date('m');

        // Stats
        $totalItems = $stockModel->where('warehouse_id', $warehouseId)->countAllResults(); // approximation of unique items in stock location
        // Better: sum of stock? Or count of different products?
        // Let's use countAllResults() on ProductStockLocation which is basically row count (unique item per location).

        $totalStockQuery = $stockModel->selectSum('jumlah_stok')->where('warehouse_id', $warehouseId)->get()->getRow();
        $totalStock = $totalStockQuery->jumlah_stok ?? 0;

        $masukBulanIni = $masukModel->getInCount($warehouseId, $month, $year);
        $keluarBulanIni = $keluarModel->getOutCount($warehouseId, $month, $year);

        // Chart Data
        $chartMasuk = $masukModel->getMonthlyStats($warehouseId, $year);
        $chartKeluar = $keluarModel->getMonthlyStats($warehouseId, $year);

        $data = [
            'menu' => 'dashboard',
            'pageTitle' => 'Dashboard',
            'stats' => [
                'total_items' => $totalItems,
                'total_stock' => $totalStock,
                'masuk_bulan_ini' => $masukBulanIni,
                'keluar_bulan_ini' => $keluarBulanIni
            ],
            'chart' => [
                'masuk' => $chartMasuk,
                'keluar' => $chartKeluar
            ]
        ];

        return view('pages/gudang/view_dashboard', $data);
    }
}
