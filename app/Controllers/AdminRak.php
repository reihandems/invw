<?php 

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AdminRakModel;

class AdminRak extends BaseController {
    protected $adminRakModel;

    public function __construct() {
        $this->adminRakModel = new AdminRakModel();
    }

    public function ajaxList() {
        $rak = $this->adminRakModel;
        $list = $rak->select('warehouse_rack.rack_id, warehouse.nama_gudang, warehouse_rack.kode_rak, warehouse_rack.deskripsi')
        ->join('warehouse', 'warehouse.warehouse_id = warehouse_rack.warehouse_id', 'left')
        ->get()
        ->getResultArray();
        $no = 0;
        $data = [];
        foreach ($list as $rak) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $rak['nama_gudang'];
            $row[] = $rak['kode_rak'];
            $row[] = $rak['deskripsi'];

            // Kolom aksi
            $row[] = '<a href="javascript:void(0)" class="btn bg-[#5160FC] text-white border-[#e5e5e5] btn-sm edit-btn" data-id="'. $rak['rack_id'].'">Edit</a>
            <a href="javascript:void(0)" class="btn-sm bg-white btn text-red-500 border-[#e5e5e5] delete-btn" data-id="'.$rak['rack_id'].'">Hapus</a>';

            $data[] = $row;
        }
        return $this->response->setJSON($data);
    }

    // Metode untuk tambah / update data (Create/Update)
    public function save() {
        $validation = \Config\Services::validation();
        $rules = [
            'warehouse_id' => 'required',
            'kode_rak' => 'required',
            'deskripsi' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false, 
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil hanya fields yang dibutuhkan
        $fields = [
            'warehouse_id',
            'kode_rak',
            'deskripsi',
        ];

        $data =  $this->request->getPost($fields);

        $id = $this->request->getPost('rack_id');

        if ($id) {
            // Update
            $this->adminRakModel->update($id, $data);
            $msg = 'Data berhasil diubah';
        } else {
            // Tambah baru
            $this->adminRakModel->insert($data);
            $msg = 'Data berhasil ditambahkan';
        }

        return $this->response->setJSON(['status' => true, 'msg' => $msg]);
    }

    // Metode untuk mendapatkan data tunggal (untuk form edit)
    public function getRak($id = null) {
        $data = $this->adminRakModel->find($id);
        return $this->response->setJSON($data);
    }


    // Metode untuk hapus data (Delete)
    public function deleteData ($id = null) {
        if ($this->adminRakModel->delete($id)) {
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