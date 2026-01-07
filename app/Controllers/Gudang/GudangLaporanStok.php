<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\ProductStockLocationModel;

class GudangLaporanStok extends BaseController {
    protected $stockLocation;

    public function __construct()
    {
        $this->stockLocation = new ProductStockLocationModel();
    }

    public function index() {
        $stok = $this->stockLocation;
        $warehouseId = session('warehouse_id');

        $data = [
            'menu' => 'stok',
            'pageTitle' => 'Laporan Stok',
            'stock' => $stok->getStockReport($warehouseId)
        ];

        return view('pages/gudang/view_laporan_stok', $data);
    }

    public function ajaxList() {
        $stok = $this->stockLocation;
        $warehouseId = session('warehouse_id');
        $list = $stok->getStockReport($warehouseId);

        $no = 0;
        $data = [];
        foreach($list as $stok) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $stok['sku']. ' / ' .$stok['nama_barang'];
            $row[] = $stok['nama_kategori'];
            $row[] = $stok['nama_gudang'];
            $row[] = $stok['kode_rak'];
            $row[] = $stok['jumlah_stok']. ' (' .$stok['nama_satuan'].')';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    public function exportPDF()
    {
        $warehouseId = session('warehouse_id');

        $data = [
            'stocks' => $this->stockLocation->getStockReport($warehouseId),
            'title' => "LAPORAN STOK BARANG PER LOKASI",
            'date' => date('d F Y H:i')
        ];

        // Load view untuk PDF (kita buat view terpisah agar rapi)
        $html = view('pages/gudang/laporan_stok_pdf', $data);

        // Inisialisasi Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        // (Opsional) Mengatur ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');

        // Render HTML ke PDF
        $dompdf->render();

        // Output ke Browser (download otomatis)
        $dompdf->stream("Laporan_Stok_".date('Ymd').".pdf", ["Attachment" => false]);
    }
}