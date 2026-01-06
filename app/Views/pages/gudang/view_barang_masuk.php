<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <dialog id="modalDetailMasuk" class="modal">
            <div class="modal-box w-11/12 max-w-3xl">
                <h3 class="font-bold text-lg mb-4">Detail Penerimaan Barang</h3>

                <hr class="my-3" style="color: var(--secondary-stroke);">
                
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <p class="text-gray-500">Tanggal Masuk</p>
                        <p id="det_tanggal" class="font-semibold"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Gudang</p>
                        <p id="det_gudang" class="font-semibold"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Penerima (Staff)</p>
                        <p id="det_staff" class="font-semibold"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Keterangan</p>
                        <p id="det_ket" class="font-semibold italic"></p>
                    </div>
                </div>

                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th>Rak</th>
                        </tr>
                    </thead>
                    <tbody id="det_item_tabel">
                        </tbody>
                </table>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <div class="modal-action">
                    <button class="btn" onclick="modalDetailMasuk.close()">Tutup</button>
                </div>
            </div>
        </dialog>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelPR" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Staff</th>
                        <th>Gudang</th>
                        <th>Tanggal Masuk</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Log end -->
        <br><br>
    </div>
    <!-- Main Content end -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        var csrfName = "<?= csrf_token() ?>";
        var csrfHash = "<?= csrf_hash() ?>";

        $(document).ready(function() {

            // Menampilkan data ke dalam dataTables
            var table = $('#tabelPR').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('gudang/barang-masuk/ajaxlist') ?>",
                    "type": "GET",
                    "dataSrc": function (x) {
                        return x;
                    }
                },
                "columns": [
                    {"data": 0},
                    {"data": 1},
                    {"data": 2},
                    {"data": 3},
                    {"data": 4, "className": "text-start"},
                    {"data": 5}
                ],
                "columnDefs": [
                    {"targets": [4], "orderable": false},
                    {"targets": [5], "orderable": false}
                ]
            });
        });

        $(document).on('click', '.btnDetailMasuk', function() {
            const id = $(this).data('id');
            
            $.ajax({
                url: "<?= site_url('gudang/barang-masuk/get-detail') ?>/" + id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if(res.status) {
                        // Isi Header
                        $('#det_tanggal').text(res.header.tanggal_masuk);
                        $('#det_gudang').text(res.header.nama_gudang);
                        $('#det_staff').text(res.header.nama_staff);
                        $('#det_ket').text(res.header.keterangan || '-');

                        // Isi Tabel Item
                        let html = '';
                        res.items.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.nama_barang}</td>
                                    <td class="text-center font-bold">${item.jumlah}</td>
                                    <td><span class="badge badge-ghost">${item.kode_rak}</span></td>
                                </tr>
                            `;
                        });
                        $('#det_item_tabel').html(html);
                        modalDetailMasuk.showModal();
                    }
                }
            });
        });
    </script>
<?= $this->endSection() ?>