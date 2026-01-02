<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\POModel;

class PurchasingPO extends BaseController {
    protected $poModel;

    public function index() {
        $data = [
            'menu' => 'po',
            'pageTitle' => 'Purchase Order (PO)'
        ];

        return view('pages/purchasing/view_po', $data);
    }

    public function __construct()
    {
        $this->poModel = new POModel();
    }

    public function ajaxList() {
        $po = $this->poModel;
        $list = $po->select('purchase_order.po_id, purchase_order.po_number, warehouse.nama_gudang, purchase_request.pr_number, supplier.nama_supplier, purchase_order.purchasing_user_id, purchase_order.order_date, purchase_order.status, purchase_order.total_amount')
        ->join('warehouse', 'warehouse.warehouse_id = purchase_order.warehouse_id', 'left')
        ->join('supplier', 'supplier.supplier_id = purchase_order.supplier_id', 'left')
        ->join('purchase_request', 'purchase_request.pr_id = purchase_order.pr_id', 'left')
        ->get()
        ->getResultArray();
        $no = 0;
        $data = [];
        foreach ($list as $po) {
            $no++;
            $row = [];
            $row[] = $po['po_number'];
            $row[] = $po['nama_gudang'];
            $row[] = $po['pr_number'];
            $row[] = $po['nama_supplier'];
            $row[] = $po['purchasing_user_id'];
            $row[] = $po['order_date'];
            $row[] = $po['status'];
            $row[] = $po['total_amount'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $po['po_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$po['po_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    // Metode untuk tambah / update data (Create/Update)
    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'po_number' => 'required',
            'kategori_id' => 'required',
            'satuan_id' => 'required',
            'harga' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false, 
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil hanya fields yang dibutuhkan
        $fields = [
            'nama_barang',
            'kategori_id',
            'satuan_id',
            'harga'
        ];

        $data =  $this->request->getPost($fields);

        $id = $this->request->getPost('barang_id');

        if ($id) {
            // Update
            $this->adminBarangModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminBarangModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON(['status' => true, 'msg' => $msg]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getBarang($id = null) {
        $data = $this->adminBarangModel->find($id);
        return $this->response->setJSON($data);
    }


    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->adminBarangModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'msg' => 'Data Berhasil Dihapus!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'msg' => 'Gagal menghapus data!'
            ]);
        }
    }
}