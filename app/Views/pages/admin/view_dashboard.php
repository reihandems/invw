<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="ringkasan font-semibold mt-8">
        <h5 style="color: var(--secondary-text); margin-bottom: 18px;">Ringkasan</h5>
        <!-- Informasi -->
        <div class="grid grid-rows-1 grid-cols-12 text-center gap-5">
            <div class="col-span-12 sm:col-span-4 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total Barang</p>
                <h1>173</h1>
            </div>
            <div class="col-span-12 sm:col-span-4 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total Supplier</p>
                <h1>3</h1>
            </div>
            <div class="col-span-12 sm:col-span-4 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total User</p>
                <h1>50</h1>
            </div>
        </div>
        <!-- Informasi end -->
    </div>
    <div class="log font-semibold mt-8">
        <h5 style="color: var(--secondary-text); margin-bottom: 18px;">Log Aktivitas Sistem</h5>
        <!-- Modal -->
        <dialog id="barangModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="barangModalLabel">Form Barang</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form id="barangForm">
                <input type="hidden" name="id" id="id">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama Barang</legend>
                    <input type="text" class="input w-full" id="namaBarang" name="namaBarang" placeholder="Masukkan nama barang" required/>
                    <div class="invalid-feedback" id="namaBarang-error"></div>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Harga Barang</legend>
                    <input
                    type="number"
                    class="input validator w-full"
                    required
                    placeholder="(Rp.) Masukkan harga barang"
                    min="1000"
                    max="100000000"
                    title="Minimal Rp. 1.000"
                    id="hargaBarang"
                    name="hargaBarang"
                    />
                    <p class="validator-hint">Minimal Rp. 1.000</p>
                    <div class="invalid-feedback" id="hargaBarang-error"></div>
                </fieldset>
            </form>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                        <button type="submit" class="btn bg-[#5160FC] text-white" id="save-btn" form="barangForm">Simpan</button>
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
        </dialog>
        <!-- Modal end -->
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 pb-5">
            <table id="tabelBarang" class="table display nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga Barang</th>
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
            var table = $('#tabelBarang').DataTable({
                // Custom style table
                // opsional: matiin style bawaan
                "dom":
                    "<'flex justify-between items-center mb-3'<'search'f><'length'l>>" +
                    "t" +
                    "<'flex justify-between items-center mt-3'<'info'i><'paginate'p>>",

                "initComplete": function() {

                    // SEARCH BOX → DaisyUI
                    $('.dataTables_filter input')
                        .addClass('input input-bordered input-sm')
                        .attr("placeholder", "Cari data…");

                    // LENGTH MENU → DaisyUI
                    $('.dataTables_length select')
                        .addClass('select select-bordered select-sm');

                    // TABLE → DaisyUI
                    $('#myTable').addClass('table table-zebra w-full');

                    // PAGINATION → DaisyUI
                    $('.dataTables_paginate a')
                        .addClass('btn btn-sm mx-1');
                },

                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "ordering": false,
                "responsive": true,
                "paging": false,
                "searching": false,
                "ajax": {
                    "url": "<?= base_url('/admin/barang/ajaxlist') ?>",
                    "type": "GET",
                    "dataSrc": function (x) {
                        return x;
                    }
                },
                "columns": [
                    {"data": 0},
                    {"data": 1},
                    {"data": 2, "className": "text-end"},
                    {"data": 3}
                ],
                "columnDefs": [
                    {"targets": [3], "orderable": false}
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#barangForm')[0].reset();
                $('#barangModalLabel').text('Tambah Data Barang');
                $('#id').val('');
                $('.invalid-feedback').text('').hide();
                $('#barangModal').modal('show');
            });

            // 2. Simpan Data (Tambah dan Edit)
            $('#barangForm').on('submit', function(e) {
                // ... (Kode pencegahan default dan persiapan FormData)

                e.preventDefault();

                var formData = new FormData(this);
                formData.append(csrfName, csrfHash);

                $.ajax({
                    url: "<?= site_url('/admin/barang/save'); ?>",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Update CSRF Hash
                        csrfHash = response.token;

                        if (response.status) {
                            // Sukses
                            alert(response.msg);
                            $('#barangModal')[0].close();

                            // Memuat ulang data dari DataTables dari sumber AJAX
                            table.ajax.reload(null, false); // 'null' untuk callback, 'false' untuk tetap pada halaman saat ini
                        } else {
                            // Validasi gagal
                            $('.invalid-feedback').text('').hide();
                            $.each(response.errors, function(key, value) {
                                $('#' + key + '-error').text(value).show().prev().addClass('is-invalid');
                            });
                        }
                        
                    },

                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                });

                // Fungsi untuk memperbarui CSRF Token dari respon AJAX
                function updateCsrfToken(response) {
                    // Cek jika ada token di respons (sesuai format CI4 standar)
                    var tokenName = Object.keys(response).filter(key => key.length === 32)[0];

                    if (tokenName) {
                        csrfName = tokenName;
                        csrfHash = response[tokenName];
                    } else if (response.token) {
                        // Jika controller hanya mengirim hash dengan key 'token' (Sesuai kode anda sebelumnya)
                        csrfHash = response.token;
                    }
                }
            });

            // 3. Edit Data (Mengisi form di modal)
            $('#tabelBarang').on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $('#barangForm')[0].reset();
                $('#barangModalLabel').text('Ubah Data Barang');
                $('.invalid-feedback').removeClass('d-block').hide();
                $('.form-control').removeClass('is-invalid');

                // Ambil data barang dari controller menggunakan AJAX
                $.ajax({
                    url: "<?= site_url('/admin/barang/getBarang/'); ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        // Isi form dengan data yang didapatkan
                        $('#id').val(data.id);
                        $('#namaBarang').val(data.namaBarang);
                        // Pastikan nama input sesuai dengan nama kolom di database: HargaBarang
                        $('#hargaBarang').val(data.hargaBarang);
                        
                        $('#barangModal')[0].showModal();

                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data untuk Edit: ' + xhr.responseText);
                    }
                })
            })


            // 4. Hapus data
            $('#tabelBarang').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    // Lakukan AJAX Delete
                    $.ajax({
                        url: "<?= site_url('admin/barang/deleteData'); ?>/" + id,
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
        })
    </script>
<?= $this->endSection() ?>