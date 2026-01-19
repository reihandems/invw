<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminLaporanModel;

class AdminLaporan extends BaseController
{
    protected $laporan;

    public function __construct()
    {
        $this->laporan = new AdminLaporanModel();
    }

    public function data()
    {
        $jenis  = $this->request->getPost('jenis_laporan');
        $awal   = $this->request->getPost('tanggal_awal');
        $akhir  = $this->request->getPost('tanggal_akhir');

        // Validasi input
        if (empty($jenis) || empty($awal) || empty($akhir)) {
            return $this->response->setJSON([
                'data' => [],
                'error' => 'Parameter tidak lengkap'
            ]);
        }

        $data = [];

        try {
            switch ($jenis) {
                case 'barang':
                    $rows = $this->laporan->laporanBarang($awal, $akhir);
                    $no = 1;
                    foreach ($rows as $r) {
                        $data[] = [
                            $no++,
                            $r['nama_barang'],
                            $r['total_masuk'],
                            $r['total_keluar'],
                            $r['total_masuk'] - $r['total_keluar']
                        ];
                    }
                    break;

                case 'stok':
                    $rows = $this->laporan->laporanStok($awal, $akhir);
                    $no = 1;
                    foreach ($rows as $r) {
                        $data[] = [
                            $no++,
                            $r['nama_barang'],
                            $r['stok_sistem'],
                            $r['stok_fisik'],
                            $r['selisih']
                        ];
                    }
                    break;

                case 'purchasing':
                    $rows = $this->laporan->laporanPurchasing($awal, $akhir);
                    $no = 1;
                    foreach ($rows as $r) {
                        $data[] = [
                            $no++,
                            $r['nama_supplier'],
                            date('d/m/Y', strtotime($r['tanggal_order'])),
                            '<span class="badge badge-' . $this->getStatusBadge($r['status']) . '">' . $r['status'] . '</span>',
                            'Rp ' . number_format($r['total_harga'], 0, ',', '.')
                        ];
                    }
                    break;

                default:
                    return $this->response->setJSON([
                        'data' => [],
                        'error' => 'Jenis laporan tidak valid'
                    ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error generating report: ' . $e->getMessage());
            return $this->response->setJSON([
                'data' => [],
                'error' => 'Terjadi kesalahan saat mengambil data'
            ]);
        }

        return $this->response->setJSON([
            'data' => $data
        ]);
    }

    // Helper untuk badge status
    private function getStatusBadge($status)
    {
        $badges = [
            'draft' => 'warning',
            'sent' => 'info',
            'received' => 'success',
            'cancelled' => 'error'
        ];

        return $badges[$status] ?? 'ghost';
    }

    // Method untuk export PDF (opsional)
    public function exportPDF()
    {
        $jenis = $this->request->getGet('jenis');
        $awal  = $this->request->getGet('awal');
        $akhir = $this->request->getGet('akhir');

        if (empty($jenis) || empty($awal) || empty($akhir)) {
            return redirect()->back()->with('error', 'Parameter tidak lengkap');
        }

        // Generate data sesuai jenis
        switch ($jenis) {
            case 'barang':
                $rows = $this->laporan->laporanBarang($awal, $akhir);
                $title = 'Laporan Barang Masuk/Keluar';
                break;
            case 'stok':
                $rows = $this->laporan->laporanStok($awal, $akhir);
                $title = 'Laporan Stok Opname';
                break;
            case 'purchasing':
                $rows = $this->laporan->laporanPurchasing($awal, $akhir);
                $title = 'Laporan Purchasing';
                break;
            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }

        $data = [
            'title' => $title,
            'periode' => date('d/m/Y', strtotime($awal)) . ' - ' . date('d/m/Y', strtotime($akhir)),
            'rows' => $rows,
            'jenis' => $jenis
        ];

        // Generate PDF
        $html = view('pages/admin/laporan_pdf', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream("Laporan_{$jenis}_" . date('Ymd') . ".pdf", ["Attachment" => false]);
    }
    // Manager Laporan (Upload & List)
    public function manager_list()
    {
        $managerLaporan = new \App\Models\ManagerLaporanModel();
        $data = $managerLaporan->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON([
            'data' => $data
        ]);
    }

    public function upload()
    {
        $judul = $this->request->getPost('judul_laporan');
        $jenis = $this->request->getPost('jenis_laporan');
        $periode = $this->request->getPost('periode_laporan');
        $file = $this->request->getFile('file_laporan');

        if (!$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => $file->getErrorString()]);
        }

        if ($file->getExtension() !== 'pdf') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Hanya file PDF yang diperbolehkan']);
        }

        $newName = $file->getRandomName();
        $file->move('uploads/laporan', $newName);

        $managerLaporan = new \App\Models\ManagerLaporanModel();
        $managerLaporan->insert([
            'judul' => $judul,
            'jenis_laporan' => $jenis,
            'periode' => $periode,
            'file_path' => $newName,
            'uploaded_by' => session()->get('id_user') ?? 1 // Fallback ID 1 if session not set (for safety)
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Laporan berhasil diupload']);
    }

    public function delete($id)
    {
        $managerLaporan = new \App\Models\ManagerLaporanModel();
        $report = $managerLaporan->find($id);

        if ($report) {
            // Delete file
            if (file_exists('uploads/laporan/' . $report['file_path'])) {
                unlink('uploads/laporan/' . $report['file_path']);
            }
            $managerLaporan->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Laporan berhasil dihapus']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Laporan tidak ditemukan']);
    }
}
