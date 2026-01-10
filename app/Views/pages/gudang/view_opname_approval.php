<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <!-- Modal detail -->
        <dialog id="modal_detail_opname" class="modal modal-bottom md:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-5xl">
                <h3 class="font-bold text-lg">Detail Barang Opname</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                
                <div class="py-4">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-base-200">
                                    <th>Barang</th>
                                    <th>Lokasi Rak</th>
                                    <th class="text-center">Stok Sistem</th>
                                    <th class="text-center">Stok Fisik</th>
                                    <th class="text-center">Selisih</th>
                                    <th>Catatan Staff</th>
                                </tr>
                            </thead>
                            <tbody id="content_detail_opname">
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="" style="color: var(--secondary-stroke);">

                <div class="modal-action">
                    <div id="action_buttons" class="flex gap-2"></div>
                    <form method="dialog">
                        <button class="btn">Tutup</button>
                    </form>
                </div>
            </div>
        </dialog>
        <!-- Modal detail end -->

        <!-- Modal Hitung -->
        <dialog id="modal_hitung_stok" class="modal">
            <div class="modal-box w-11/12 max-w-4xl">
                <h3 class="font-bold text-lg border-b pb-2">Form Perhitungan Fisik</h3>
                
                <form id="form_input_fisik">
                    <input type="hidden" name="opname_id" id="input_opname_id">
                    <div class="py-4 overflow-x-auto">
                        <table class="table table-compact w-full">
                            <thead>
                                <tr>
                                    <th>Barang & SKU</th>
                                    <th>Rak</th>
                                    <th class="w-24">Fisik</th>
                                    <th>Catatan Staff</th>
                                </tr>
                            </thead>
                            <tbody id="container_item_opname">
                                </tbody>
                        </table>
                    </div>

                    <div class="modal-action">
                        <button type="button" onclick="modal_hitung_stok.close()" class="btn">Batal</button>
                        <button type="submit" class="btn bg-[#5160FC] text-white">Simpan & Kirim ke Manager</button>
                    </div>
                </form>
            </div>
        </dialog>
        <!-- Modal Hitung end -->

        <div class="atas flex flex-col md:flex-row justify-between mb-3">
            <div role="tablist" class="tabs tabs-box mb-5 md:mb-0">
                <a role="tab" class="tab <?= ($tab === 'jadwalOpname') ? 'tab-active' : '' ?>" href="<?= base_url('gudang/opname') ?>">Jadwal Opname</a>
                <a role="tab" class="tab <?= ($tab === 'waitApproval') ? 'tab-active' : '' ?>" href="<?= base_url('gudang/opname/approval') ?>">Menunggu Approval</a>
                <a role="tab" class="tab <?= ($tab === 'rejectedOpname') ? 'tab-active' : '' ?>" href="<?= base_url('gudang/opname/rejected') ?>">Ditolak</a>
                <a role="tab" class="tab <?= ($tab === 'finishedOpname') ? 'tab-active' : '' ?>" href="<?= base_url('gudang/opname/finished') ?>">Selesai</a>
            </div>
        </div>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelOpname" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jadwal</th>
                        <th>Jenis</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
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
            var table = $('#tabelOpname').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('gudang/opname/approval/ajaxlist') ?>",
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
                    {"data": 5},
                    {"data": 6}
                ],
                "columnDefs": [
                    {"targets": [6], "orderable": false}
                ]
            });

        });

        function showDetail(opnameId, status) {
            // 1. Tampilkan Loading
            $('#content_detail_opname').html('<tr><td colspan="6" class="text-center">Loading data...</td></tr>');
            modal_detail_opname.showModal();

            // 2. Load Data via AJAX
            $.get("<?= site_url('gudang/opname/detail/') ?>" + opnameId, function(data) {
                let html = '';
                data.forEach(item => {
                    // Logika warna selisih
                    let selisih = item.selisih ?? '-';
                    let warnaSelisih = '';
                    if (item.selisih < 0) warnaSelisih = 'text-error font-bold';
                    if (item.selisih > 0) warnaSelisih = 'text-success font-bold';

                    html += `
                        <tr>
                            <td>
                                <div class="font-bold">${item.nama_barang}</div>
                                <div class="text-xs opacity-50">${item.sku}</div>
                            </td>
                            <td><div class="badge badge-outline">${item.kode_rak}</div></td>
                            <td class="text-center font-mono">${item.stok_sistem}</td>
                            <td class="text-center font-mono">${item.stok_fisik ?? '<span class="badge badge-outline badge-warning text-xs">Belum diisi</span>'}</td>
                            <td class="text-center ${warnaSelisih}">${selisih}</td>
                            <td class="text-xs italic">${item.catatan_staff ?? '-'}</td>
                        </tr>`;
                });
                $('#content_detail_opname').html(html);
            });
        }
    </script>
<?= $this->endSection() ?>