<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;

class ManagerDashboard extends BaseController
{
    public function index()
    {
        // 1. Load Models
        $barangModel = new \App\Models\AdminBarangModel();
        $supplierModel = new \App\Models\AdminSupplierModel();
        $poModel = new \App\Models\POModel();
        $barangMasukModel = new \App\Models\BarangMasukModel();
        $barangKeluarModel = new \App\Models\BarangKeluarModel();

        // 2. Fetch Stats
        $stats = [
            'total_products' => $barangModel->countAll(),
            'total_suppliers' => $supplierModel->countAll(),
            'active_pos'     => $poModel->countActivePO(),
            'today_pos'      => $poModel->countTodayPO(),
        ];

        // 3. Generate Chart Data (Last 6 Months)
        $months = [];
        $incomingData = [];
        $outgoingData = [];

        // Loop backwards for 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('F Y', strtotime("-$i months"));

            $months[] = $monthName;

            // Count Incoming (Barang Masuk)
            // Note: Assuming 'tanggal_masuk' is the date field. 
            // Querying manually for efficiency in loop or using Model logic if custom method existed.
            // Using basic builder here just to get the count by month pattern.
            $countMasuk = $barangMasukModel->like('tanggal_masuk', $date)->countAllResults();
            $incomingData[] = $countMasuk;

            // Count Outgoing (Barang Keluar)
            $countKeluar = $barangKeluarModel->like('tanggal_keluar', $date)->countAllResults();
            $outgoingData[] = $countKeluar;
        }

        $chartData = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Barang Masuk',
                    'data' => $incomingData,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Barang Keluar',
                    'data' => $outgoingData,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'tension' => 0.4
                ]
            ]
        ];

        $data = [
            'menu' => 'dashboard',
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'chartData' => $chartData
        ];

        return view('pages/manager/view_dashboard', $data);
    }
}
