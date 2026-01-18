<?= $this->extend('layout/main/manager/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <dialog id="modalJadwalOpname" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg mb-4">Buat Jadwal Opname</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                
                <form id="formJadwalOpname">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Jenis Opname</legend>
    
                                <div class="flex flex-row">
                                    <div class="flex mr-3">
                                        <input type="radio" name="jenis" class="radio" value="full" checked="checked" checked onclick="toggleRak(false)" />
                                        <p class="label ml-2">Full (Semua Rak)</p>
                                    </div>
                                    <div class="flex">
                                        <input type="radio" name="jenis" class="radio" value="partial" onclick="toggleRak(true)"/>
                                        <p class="label ml-2">Partial (Rak Tertentu)</p>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <fieldset class="fieldset"> 
                                <legend class="fieldset-legend">Nama Jadwal</legend>
                                <input type="text" class="input w-full" name="nama_jadwal" placeholder="Contoh: Opname Akhir Tahun 2025" required/>
                            </fieldset>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <fieldset class="fieldset"> 
                                <legend class="fieldset-legend">Gudang</legend>
                                <select name="warehouse_id" id="warehouse_id" class="select w-full" required onchange="loadRacks(this.value)">
                                    <option value="">-- Pilih Gudang --</option>
                                    <?php foreach($list_gudang as $g): ?>
                                        <option value="<?= $g['warehouse_id'] ?>"><?= $g['nama_gudang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-span-6">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Tanggal Mulai</legend>
                                <input type="date" class="input w-full" name="tanggal_mulai" required/>
                            </fieldset>
                        </div>

                        <div class="col-span-6">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Tanggal Berakhir</legend>
                                <input type="date" class="input w-full" name="tanggal_berakhir" required/>
                            </fieldset>
                        </div>

                        <div class="col-span-12">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Keterangan</legend>
                                <textarea class="textarea h-24 w-full" name="keterangan" placeholder="Masukkan Keterangan"></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div id="sectionPilihRak" class="mt-6 hidden p-4 rounded-lg bg-base-200">
                        <label class="label font-bold text-sm mb-3">Pilih Rak di Gudang Tersebut:</label>
                        <div id="list_rak_container" class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <p class="text-xs italic opacity-50">Pilih gudang terlebih dahulu...</p>
                        </div>
                    </div>

                    <div class="modal-action">
                        <button type="submit" class="btn btn-primary px-10">Simpan & Publikasikan Jadwal</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Modal detail -->
        <dialog id="modal_detail_opname" class="modal modal-bottom md:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-5xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
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

                <div class="modal-action">
                    <div id="action_buttons" class="flex gap-2"></div>
                </div>
            </div>
        </dialog>
        <!-- Modal detail end -->

        <div class="atas flex flex-col md:flex-row justify-between mb-3">
            <div role="tablist" class="tabs tabs-box mb-5 md:mb-0">
                <a role="tab" class="tab <?= ($tab === 'jadwalOpname') ? 'tab-active' : '' ?>" href="<?= base_url('manager/opname') ?>">Jadwal Opname</a>
                <a role="tab" class="tab <?= ($tab === 'waitApproval') ? 'tab-active' : '' ?>" href="<?= base_url('manager/opname/approval') ?>">Menunggu Approval</a>
                <a role="tab" class="tab <?= ($tab === 'finishedOpname') ? 'tab-active' : '' ?>" href="<?= base_url('manager/opname/finished') ?>">Selesai</a>
            </div>
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="modalJadwalOpname.showModal()">+ Buat Jadwal</button>
            <!-- Tombol End -->
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
                    "url": "<?= base_url('manager/opname/ajaxlist') ?>",
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

            $('#formJadwalOpname').submit(function(e) {
                e.preventDefault();
                
                // Validasi jika partial tapi rak tidak dipilih
                if($('input[name="jenis"]:checked').val() === 'partial' && $('input[name="rak_ids[]"]:checked').length === 0) {
                    alert('Silakan pilih minimal satu rak untuk jenis Partial!');
                    return;
                }

                $.ajax({
                    url: "<?= site_url('manager/opname/save-schedule') ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        if(res.status) {
                            alert('Jadwal Berhasil Dibuat!');
                            window.location.href = "<?= site_url('manager/opname') ?>";
                        } else {
                            alert('Gagal: ' + res.message);
                        }
                    }
                });
            });

            $('#tabelOpname').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    // Lakukan AJAX Delete
                    $.ajax({
                        url: "<?= site_url('manager/opname/deleteData'); ?>/" + id,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            // Kirim CSRF Token
                            [csrfName]: csrfHash
                        },
                        success: function(response) {
                            // updateCsrfToken(response); // Update CSRF Hash

                            if (response.status) {
                                alert(response.msg);
                                table.ajax.reload(null, false); // Reload DataTables
                            } else {
                                alert('Gagal: ' + response.msg);
                            }
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan saat menghapus data: ' + xhr.responseText);
                        }
                    });
                }
            });

        });

        function loadRacks(warehouseId) {
            if (!warehouseId) return;

            // Reset container
            $('#list_rak_container').html('<span class="loading loading-spinner loading-xs"></span> Mengambil data rak...');

            $.get("<?= site_url('manager/opname/get-racks-by-warehouse/') ?>" + warehouseId, function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(rak => {
                        html += `
                        <label class="flex items-center gap-2 bg-white p-2 rounded shadow-sm cursor-pointer hover:bg-blue-50">
                            <input type="checkbox" name="rak_ids[]" value="${rak.rack_id}" class="checkbox checkbox-sm checkbox-primary">
                            <span class="text-xs">${rak.kode_rak} | ${rak.deskripsi}</span>
                        </label>`;
                    });
                } else {
                    html = '<p class="text-xs text-error">Tidak ada rak di gudang ini.</p>';
                }
                $('#list_rak_container').html(html);
            });
        }

        function toggleRak(show) {
            const section = document.getElementById('sectionPilihRak');
            if (show) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }

        function updateStatus(id, status) {
            if (confirm("Apakah Anda yakin?")) {
                $.post("<?= site_url('manager/opname/update-status') ?>", { id: id, status: status }, function(res) {
                    if (res.status) {
                        alert(res.message);
                        if(window.modal_detail_opname) modal_detail_opname.close();
                        
                        // GUNAKAN INI JUGA:
                        $('#tabelOpname').DataTable().ajax.reload(null, false);
                    }
                });
            }
        }

        function showDetail(opnameId, status) {
            // 1. Tampilkan Loading
            $('#content_detail_opname').html('<tr><td colspan="6" class="text-center">Loading data...</td></tr>');
            modal_detail_opname.showModal();

            // 2. Load Data via AJAX
            $.get("<?= site_url('manager/opname/detail/') ?>" + opnameId, function(data) {
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

                // 3. Logika Tombol Approval (Hanya muncul jika status submitted)
                let btnHtml = '';
                if (status === 'submitted') {
                    btnHtml = `
                        <button onclick="updateStatus(${opnameId}, 'rejected')" class="btn btn-error btn-outline">Tolak (Hitung Ulang)</button>
                        <button onclick="updateStatus(${opnameId}, 'approved')" class="btn bg-[#5160FC] text-white">Approve & Sinkron Stok</button>
                    `;
                }
                $('#action_buttons').html(btnHtml);
            });
        }
    </script>
<?= $this->endSection() ?>