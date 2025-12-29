<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-supplier font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="supplierModal.showModal()">+ Tambah Supplier</button>
            <!-- Tombol End -->
        </div>
        <!-- Modal -->
        <dialog id="supplierModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="supplierModalLabel">Form Supplier</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form id="supplierForm">
                <input type="hidden" name="supplier_id" id="supplier_id">
                <!-- Nama supplier -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama Supplier</legend>
                    <input type="text" class="input w-full" id="nama_supplier" name="nama_supplier" placeholder="Masukkan nama supplier" required/>
                    <div class="invalid-feedback" id="nama_supplier-error"></div>
                </fieldset>
                <!-- Nama supplier end -->
                <!-- Kontak -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Kontak</legend>
                    <input type="text" class="input w-full" id="kontak" name="kontak" placeholder="Masukkan kontak supplier (No. Hp / Email)" required/>
                    <div class="invalid-feedback" id="kontak-error"></div>
                </fieldset>
                <!-- Kontak end -->
                <!-- Alamat -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat</legend>
                    <textarea class="textarea h-24 w-full" id="alamat" name="alamat" placeholder="Masukkan alamat supplier" required></textarea>
                    <div class="invalid-feedback" id="alamat-error"></div>
                </fieldset>
                <!-- Alamat end -->
            </form>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button type="submit" class="btn bg-[#5160FC] text-white" id="save-btn" form="supplierForm">Simpan</button>
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
        </dialog>
        <!-- Modal end -->
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelSupplier" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
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
            var table = $('#tabelSupplier').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('admin/supplier/ajaxlist') ?>",
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
                    {"data": 4}
                ],
                "columnDefs": [
                    {"targets": [4], "orderable": false}
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#supplierForm')[0].reset();
                $('#supplierModalLabel').text('Tambah Data Supplier');
                $('#supplier_id').val('');
                $('.invalid-feedback').text('').hide();
                $('#supplierModal').modal('show');
            });

            // 2. Simpan Data (Tambah dan Edit)
            $('#supplierForm').on('submit', function(e) {
                // ... (Kode pencegahan default dan persiapan FormData)

                e.preventDefault();

                var formData = new FormData(this);
                formData.append(csrfName, csrfHash);

                $.ajax({
                    url: "<?= site_url('/admin/supplier/save'); ?>",
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
                            $('#supplierModal')[0].close();

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
            $('#tabelSupplier').on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $('#supplierForm')[0].reset();
                $('#supplierModalLabel').text('Ubah Data Supplier');
                $('.invalid-feedback').removeClass('d-block').hide();
                $('.form-control').removeClass('is-invalid');

                // Ambil data barang dari controller menggunakan AJAX
                $.ajax({
                    url: "<?= site_url('/admin/supplier/getSupplier/'); ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        // Isi form dengan data yang didapatkan
                        $('#supplier_id').val(data.supplier_id);
                        $('#nama_supplier').val(data.nama_supplier);
                        $('#kontak').val(data.kontak);
                        // Pastikan nama input sesuai dengan nama kolom di database: alamat
                        $('#alamat').val(data.alamat);
                        
                        $('#supplierModal')[0].showModal();

                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data untuk Edit: ' + xhr.responseText);
                    }
                })
            });


            // 4. Hapus data
            $('#tabelSupplier').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    // Lakukan AJAX Delete
                    $.ajax({
                        url: "<?= site_url('admin/supplier/deleteData'); ?>/" + id,
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