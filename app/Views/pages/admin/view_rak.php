<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-rak font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="rakModal.showModal()">+ Tambah Rak</button>
            <!-- Tombol End -->
        </div>
        <!-- Modal -->
        <dialog id="rakModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="rakModalLabel">Form Rak</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form id="rakForm">
                <input type="hidden" name="rack_id" id="rack_id">
                <!-- Gudang -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Gudang</legend>
                    <select class="select w-full" id="warehouse_id" name="warehouse_id">
                        <option value="">-- Pilih Gudang --</option>
                        <?php foreach($gudang as $g) : ?>
                            <option value="<?= $g['warehouse_id'] ?>">
                                <?= esc($g['nama_gudang']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>
                <!-- Gudang end -->
                <!-- Kode Rak -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Kode Rak</legend>
                    <input type="text" class="input w-full" id="kode_rak" name="kode_rak" placeholder="Masukkan kode rak gudang" required/>
                    <div class="invalid-feedback" id="kode_rak-error"></div>
                </fieldset>
                <!-- Kode Rak end -->
                <!-- Deskripsi -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Deskripsi</legend>
                    <textarea class="textarea h-24 w-full" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi rak" required></textarea>
                    <div class="invalid-feedback" id="deskripsi-error"></div>
                </fieldset>
                <!-- Deskripsi end -->
            </form>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                        <button type="submit" class="btn bg-[#5160FC] text-white" id="save-btn" form="rakForm">Simpan</button>
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
        </dialog>
        <!-- Modal end -->
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelRak" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gudang</th>
                        <th>Kode Rak</th>
                        <th>Deskripsi</th>
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
            var table = $('#tabelRak').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('admin/rak/ajaxlist') ?>",
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
                    {"targets": [3], "orderable": false},
                    {"targets": [4], "orderable": false},
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#rakForm')[0].reset();
                $('#rakModalLabel').text('Tambah Data Rak');
                $('#rack_id').val('');
                $('.invalid-feedback').text('').hide();
                $('#rakModal').modal('show');
            });

            // 2. Simpan Data (Tambah dan Edit)
            $('#rakForm').on('submit', function(e) {
                // ... (Kode pencegahan default dan persiapan FormData)

                e.preventDefault();

                var formData = new FormData(this);
                formData.append(csrfName, csrfHash);

                $.ajax({
                    url: "<?= site_url('/admin/rak/save'); ?>",
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
                            $('#rakModal')[0].close();

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
            $('#tabelRak').on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $('#rakForm')[0].reset();
                $('#rakModalLabel').text('Ubah Data Rak');
                $('.invalid-feedback').removeClass('d-block').hide();
                $('.form-control').removeClass('is-invalid');

                // Ambil data barang dari controller menggunakan AJAX
                $.ajax({
                    url: "<?= site_url('/admin/rak/getRak/'); ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        // Isi form dengan data yang didapatkan
                        $('#rack_id').val(data.rack_id);
                        $('#warehouse_id').val(data.warehouse_id);
                        $('#kode_rak').val(data.kode_rak);
                        $('#deskripsi').val(data.deskripsi);
                        // Pastikan nama input sesuai dengan nama kolom di database: deskripsi
                        
                        $('#rakModal')[0].showModal();

                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data untuk Edit: ' + xhr.responseText);
                    }
                })
            });


            // 4. Hapus data
            $('#tabelRak').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    // Lakukan AJAX Delete
                    $.ajax({
                        url: "<?= site_url('admin/rak/deleteData'); ?>/" + id,
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