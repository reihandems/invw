<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <div class="atas flex flex-col md:flex-row justify-between mb-3">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white btnBarangKeluar">+ Tambah Barang Keluar</button>
            <!-- Tombol End -->
        </div>
        <!-- Modal Form Barang Keluar -->
        <dialog id="modalBarangKeluar" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg mb-4">Form Barang Keluar</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                
                <form id="formBarangKeluar">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Tanggal Keluar</legend>
                            <input type="datetime-local" class="input w-full" name="tanggal_keluar" required value="<?= date('Y-m-d H:i:s') ?>"/>
                        </fieldset>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Keterangan / Tujuan</legend>
                            <input type="text" class="input w-full" name="keterangan" placeholder="Contoh: Pengiriman ke Toko B"/>
                        </fieldset>
                    </div>

                    <table class="table w-full" id="tabelKeluar">
                        <thead>
                            <tr class="bg-base-200">
                                <th width="40%">Barang</th>
                                <th width="30%">Asal Rak (Stok)</th>
                                <th width="20%">Jumlah Keluar</th>
                                <th width="10%">#</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <button type="button" class="btn btn-dash w-full" id="btnTambahBaris">
                                        + Tambah Barang
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- Action -->
                    <div class="modal-action">
                        <button id="btnSubmitBarangKeluar" class="btn bg-[#5160FC] text-white">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </dialog>
        <!-- Modal Form Barang Keluar end -->

        <!-- Modal Detail -->
        <dialog id="modalDetail" class="modal">
            <div class="modal-box w-11/12 max-w-3xl">
                <h3 class="font-bold text-lg">Detail Barang Keluar</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">

                <div id="isiDetail" class="py-4"></div>

                <hr class="my-3" style="color: var(--secondary-stroke);">
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">Tutup</button>
                    </form>
                </div>
            </div>
        </dialog>
        <!-- Modal Detail end -->

        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelPR" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Gudang</th>
                        <th>Keterangan / Tujuan</th>
                        <th>Staff</th>
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
                    "url": "<?= base_url('gudang/barang-keluar/ajaxlist') ?>",
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

            let rowCount = 0;

            // Menampilkan Modal (Trigger dari tombol di luar modal)
            $('.btnBarangKeluar').click(function() {
                $('#itemRows').empty(); // Kosongkan baris lama
                addRow(); // Tambah satu baris default
                document.getElementById('modalBarangKeluar').showModal();
            });

            // Fungsi Tambah Baris
            $('#btnTambahBaris').click(function() {
                addRow();
            });

            function addRow() {
                rowCount++;
                let html = `
                    <tr id="row_${rowCount}">
                        <td>
                            <select name="items[${rowCount}][barang_id]" class="select select-bordered select-sm w-full select-barang" data-row="${rowCount}" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach($list_barang as $b): ?>
                                    <option value="<?= $b['barang_id'] ?>"><?= $b['sku'] ?> - <?= $b['nama_barang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="items[${rowCount}][rack_id]" class="select select-bordered select-sm w-full select-rak" id="rak_${rowCount}" required>
                                <option value="">Pilih Barang Dulu</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[${rowCount}][jumlah]" class="input input-bordered input-sm w-full input-jumlah" min="1" required placeholder="0">
                        </td>
                        <td>
                            <button type="button" class="btn btn-error btn-sm btnRemoveRow">X</button>
                        </td>
                    </tr>
                `;
                $('#tabelKeluar tbody').append(html);
            }

            // Hapus Baris
            $(document).on('click', '.btnRemoveRow', function() {
                $(this).closest('tr').remove();
            });

            // AJAX Ambil Rak saat barang dipilih
            $(document).on('change', '.select-barang', function() {
                const barangId = $(this).val();
                const currentRow = $(this).data('row');
                const rakSelect = $(`#rak_${currentRow}`);

                if (!barangId) {
                    rakSelect.html('<option value="">Pilih Barang Dulu</option>');
                    return;
                }

                $.ajax({
                    url: "<?= site_url('gudang/barang-keluar/get-racks') ?>",
                    type: "GET",
                    data: { barang_id: barangId },
                    dataType: "json",
                    success: function(data) {
                        let options = '<option value="">-- Pilih Rak (Stok) --</option>';
                        data.forEach(item => {
                            options += `<option value="${item.rack_id}" data-stok="${item.jumlah_stok}">
                                            ${item.kode_rak} (Sisa: ${item.jumlah_stok})
                                        </option>`;
                        });
                        rakSelect.html(options);
                    }
                });
            });

            // Validasi Stok
            $(document).on('change input', '.input-jumlah, .select-rak', function() {
                const row = $(this).closest('tr');
                const inputJml = row.find('.input-jumlah');
                const selectRak = row.find('.select-rak option:selected');
                const stokTersedia = parseInt(selectRak.data('stok')) || 0;
                const jmlInput = parseInt(inputJml.val()) || 0;

                if (jmlInput > stokTersedia) {
                    alert(`Stok tidak cukup! Sisa di rak ini: ${stokTersedia}`);
                    inputJml.val(stokTersedia);
                }
            });

            // Submit Form
            $('#btnSubmitBarangKeluar').click(function(e) {
                e.preventDefault();
                const formData = $('#formBarangKeluar').serialize();

                $.ajax({
                    url: "<?= site_url('gudang/barang-keluar/save') ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            alert("Barang Keluar berhasil dicatat!");
                            location.reload();
                        } else {
                            alert("Error: " + res.message);
                        }
                    }
                });
            });

            $(document).on('click', '.btnDetail', function() {
                const id = $(this).data('id');
                $('#isiDetail').html('<span class="loading loading-spinner loading-md"></span> Loading...');
                document.getElementById('modalDetail').showModal();

                $.get("<?= site_url('gudang/barang-keluar/detail/') ?>" + id, function(data) {
                    let html = `
                        <div class="overflow-x-auto rounded-box border border-gray-200 bg-base-100">
                            <table class="table w-full">
                                <thead>
                                    <tr class="bg-base-200">
                                        <th>Nama Barang</th>
                                        <th>Rak</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>
                                    <div class="font-bold">${item.nama_barang}</div>
                                    <div class="text-xs opacity-50">${item.sku}</div>
                                </td>
                                <td>${item.kode_rak}</td>
                                <td class="text-center"><b>${item.jumlah}</b> ${item.nama_satuan}</td>
                            </tr>`;
                    });

                    html += `</tbody>
                    </table>
                    </div>`;
                    $('#isiDetail').html(html);
                });
            });
        });
    </script>
<?= $this->endSection() ?>