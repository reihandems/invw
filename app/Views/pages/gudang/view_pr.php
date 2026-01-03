<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="barangModal.showModal()">+ Buat PR</button>
            <!-- Tombol End -->
        </div>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelBarang" class="table responsive nowrap display">
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

            // Menampilkan data ke dalam dataTables
            var table = $('#tabelBarang').DataTable({
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
                    {"data": 4, "className": "text-end"},
                    {"data": 5}
                ],
                "columnDefs": [
                    {"targets": [5], "orderable": false}
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#barangForm')[0].reset();
                $('#barangModalLabel').text('Tambah Data Barang');
                $('#barang_id').val('');
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
                        $('#barang_id').val(data.barang_id);
                        $('#nama_barang').val(data.nama_barang);
                        $('#satuan_id').val(data.satuan_id);
                        $('#kategori_id').val(data.kategori_id);
                        // Pastikan nama input sesuai dengan nama kolom di database: HargaBarang
                        $('#harga').val(data.harga);
                        
                        $('#barangModal')[0].showModal();

                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data untuk Edit: ' + xhr.responseText);
                    }
                })
            });


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