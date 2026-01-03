<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="prModal.showModal()">+ Buat PR</button>
            <!-- Tombol End -->
        </div>
        <!-- PR Baru Modal -->
        <dialog id="prModal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-5xl">
                <h3 class="text-lg font-bold modal-title" id="formModalLabel">Form PR</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                <form id="prForm" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="pr_id" id="pr_id">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-6">
                            <!-- No PO -->
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">No. PR</legend>
                                <input type="text" class="input w-full" id="pr_number" name="pr_number" value="<?= $prNumber ?>"  readonly/>
                                <div class="invalid-feedback" id="pr_number-error"></div>
                            </fieldset>
                            <!-- No PO end -->
                        </div>
                        <div class="col-span-6">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Tanggal</legend>
                                <input type="date" class="input input-bordered w-full" value="<?= date('Y-m-d') ?>" readonly>
                                <div class="invalid-feedback" id="date-error"></div>
                            </fieldset>
                        </div>
                        <div class="col-span-6">
                            <!-- Nama -->
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Nama Pengaju</legend>
                                <input type="hidden" name="user_id" value="<?= session('user_id') ?>">
                                <input type="text" class="input w-full" value="<?= $user ?>" readonly>
                                <div class="invalid-feedback" id="user_id-error"></div>
                            </fieldset>
                            <!-- Nama end -->
                        </div>
                        <div class="col-span-6">
                            <!-- Gudang -->
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Gudang</legend>
                                <input type="hidden" name="warehouse_id" value="<?= session('warehouse_id') ?>">
                                <input type="text" class="input w-full" value="<?= $user_gudang ?>" readonly>
                                <div class="invalid-feedback" id="warehouse_id-error"></div>
                            </fieldset>
                            <!-- Gudang end -->
                        </div>
                        <div class="col-span-12">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Catatan</legend>
                                <textarea class="textarea h-24 w-full" id="notes" name="notes" placeholder="Masukkan alasan pembelian barang"></textarea>
                            </fieldset>
                        </div>
                    </div>
                    <hr class="my-5" style="color: var(--secondary-stroke);">
                    <p class="text-xs mb-2">Detail Barang</p>
                    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                        <table class="table">
                            <!-- head -->
                            <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Barang</th>
                                <th>Stok Gudang</th>
                                <th>Qty</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <!-- Detail Barang -->
                            <tbody id="detailBarang">
                                <tr class="row-template">
                                    <td>
                                        <select class="select select-bordered kategori-select" name="kategori_id[]">
                                            <option value="">-- Pilih Kategori -- </option>
                                            <?php foreach ($kategori as $k) : ?>
                                                <option value="<?= $k['kategori_id'] ?>"><?= $k['nama_kategori'] ?></option>
                                            <?php endforeach; ?>
                                            <!-- option diisi dari server -->
                                        </select>
                                    </td>

                                    <td>
                                        <select name="barang_id[]" class="select select-bordered barang-select">
                                            <option value="">-- Pilih Barang --</option>
                                        </select>
                                    </td>

                                    <td class="stok">0</td>

                                    <td>
                                        <input type="number" name="qty[]" class="input input-bordered w-24 qty" min="1">
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-error btn-sm remove-row">✕</button>
                                    </td>
                                </tr>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <button type="button" id="btnAddRow" class="btn btn-outline">
                                        + Tambah Barang
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
                <hr class="my-5" style="color: var(--secondary-stroke);">
                <div class="modal-action">
                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Close</button>
                        <button type="submit" class="btn bg-[#5160FC] text-white" id="save-btn" form="prForm">Simpan</button>
                    </form>
                </div>
            </div>
        </dialog>
        <!-- PR Baru Modal end -->
        
        <dialog id="detailPrModal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box max-w-4xl">
                <h3 class="font-bold text-lg" id="d_pr_number">Detail Purchase Request</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                <div class="grid grid-cols-12 gap-4 mt-4 text-sm">
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Tanggal:</b> <span id="d_created_at"></span></p>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Status:</b> <span id="d_status"></span></p>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Gudang:</b> <span id="d_warehouse"></span></p>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Pengaju:</b> <span id="d_created_by"></span></p>
                    </div>
                </div>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <div class="mt-4">
                <p class="text-sm font-semibold mb-1">Catatan</p>
                <p class="text-sm bg-base-200 p-3 rounded" id="d_notes"></p>
                </div>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <div class="mt-6">
                <p class="font-semibold mb-2">Detail Barang</p>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="detailItemsTable"></tbody>
                    </table>
                </div>
                </div>

                <div class="modal-action">
                    <button class="btn" onclick="detailPrModal.close()">Close</button>
                </div>
            </div>
        </dialog>

        <?php if (session()->getFlashdata('success')): ?>
                    <div class="toast toast-top toast-center">
                        <div class="alert alert-success alert-soft border-shaded-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4"/>
                            </svg>
                            <span><?= session()->getFlashdata('success') ?></span>
                        </div>
                    </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
                    <div class="toast toast-top toast-center">
                        <div class="alert alert-success alert-soft border-shaded-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4"/>
                            </svg>
                            <span><?= session()->getFlashdata('error') ?></span>
                        </div>
                    </div>
        <?php endif; ?>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelPR" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. PR</th>
                        <th>Gudang</th>
                        <th>Status</th>
                        <th>Dibuat Pada</th>
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

            function rowTemplate() {
                return `
                    <tr>
                    <td>
                        <select name="kategori_id[]" class="select select-bordered kategori-select">
                        <option value="">-- Pilih Barang --</option>
                        <!-- option diisi dari server -->
                        </select>
                    </td>

                    <td>
                        <select name="barang_id[]" class="select select-bordered barang-select">
                        <option value="">-- Pilih Barang --</option>
                        <!-- option diisi dari server -->
                        </select>
                    </td>

                    <td>
                        <span class="stok-text">0</span>
                        <input type="hidden" name="stok[]" class="stok-hidden">
                    </td>

                    <td>
                        <input type="number" name="qty[]" class="input input-bordered qty-input w-24" min="1">
                    </td>

                    <td>
                        <button type="button" class="btn btn-error btn-sm btnRemove">X</button>
                    </td>
                    </tr>
                `;
            }

            // URL dasar untuk AJAX (sesuaikan jika ada perubahan routing)
            const baseUrl = '<?= base_url() ?>';

            $(document).on('change', '.kategori-select', function () {
                const kategoriId = $(this).val();
                const row = $(this).closest('tr');
                const $barangSelect = row.find('.barang-select');

                $barangSelect.html('<option value="">Memuat...</option>').prop('disabled', true);

                if (kategoriId) {
                    $.ajax({
                        url: '<?= base_url("gudang/purchase-request/get-barang") ?>',
                        method: 'POST',
                        data: { kategori_id: kategoriId },
                        dataType: 'json',
                        success: function (response) {
                            $barangSelect.prop('disabled', false);
                            $barangSelect.html('<option value="">-- Pilih Barang --</option>');

                            if (response.length > 0) {
                                $.each(response, function (i, barang) {
                                    $barangSelect.append(
                                        $('<option>', {
                                            value: barang.barang_id,
                                            text: barang.nama_barang
                                        })
                                    );
                                });
                            } else {
                                $barangSelect.html('<option value="">Tidak ada barang</option>');
                            }
                        },
                        error: function () {
                            $barangSelect.html('<option value="">Gagal memuat data</option>');
                        }
                    });
                } else {
                    $barangSelect.html('<option value="">Pilih kategori terlebih dahulu</option>').prop('disabled', true);
                }
            });

            $('#btnAddRow').on('click', function () {
                let newRow = $('.row-template').first().clone();

                // reset value
                newRow.find('select').val('');
                newRow.find('.stok').text('0');
                newRow.find('input.qty').val('');

                $('#detailBarang').append(newRow);
            });


            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
            });


            $(document).on('change', '.barang-select', function () {
                const barangId = $(this).val();
                const row = $(this).closest('tr');
                const stokCell = row.find('.stok');

                stokCell.text('0');

                if (!barangId) return;

                $.ajax({
                    url: '<?= base_url("gudang/purchase-request/get-stok") ?>',
                    method: 'GET',
                    data: { barang_id: barangId },
                    dataType: 'json',
                    success: function (res) {
                    stokCell.text(res.stok ?? 0);
                    },
                    error: function () {
                    stokCell.text('0');
                    }
                });
            });


            // Menampilkan data ke dalam dataTables
            var table = $('#tabelPR').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('gudang/purchase-request/ajaxlist') ?>",
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
                    {"targets": [5], "orderable": false}
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#prForm')[0].reset();
                $('#formModalLabel').text('Tambah PR Baru');
                $('#pr_id').val('');
                $('.invalid-feedback').text('').hide();
                $('#prModal').modal('show');
                $.get('<?= base_url('gudang/purchase-request/generate-number') ?>', function (res) {
                    $('#pr_number').val(res.pr_number);
                    document.getElementById('prModal').showModal();
                });
            });

            $('#prForm').on('submit', function(e) {
            e.preventDefault();

            console.log('FORM SUBMITTED');

            var formData = new FormData(this);

            $.ajax({
                url: "<?= site_url('/gudang/purchase-request/store'); ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response.message);

                    if (response.status) {
                        $('#prModal')[0].close();
                        table.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan');
                }
            });

        });

        })

        function openDetailPR(prId) {
            fetch(`/gudang/purchase-request/detail/${prId}`)
                .then(res => res.json())
                .then(res => {
                if (!res.status) return alert('Gagal ambil data');

                const h = res.header;
                document.getElementById('d_pr_number').textContent = h.pr_number;
                document.getElementById('d_created_at').textContent = h.created_at;
                document.getElementById('d_status').textContent = h.status;
                document.getElementById('d_warehouse').textContent = h.nama_gudang;
                document.getElementById('d_created_by').textContent = h.created_by;
                document.getElementById('d_notes').textContent = h.notes ?? '-';

                const tbody = document.getElementById('detailItemsTable');
                tbody.innerHTML = '';

                res.items.forEach((item, i) => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${item.nama_barang}</td>
                        <td>${item.qty}</td>
                    </tr>
                    `;
                });

                detailPrModal.showModal();
                });
        }
    </script>
<?= $this->endSection() ?>