<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="barangModal.showModal()">+ Tambah Barang</button>
            <!-- Tombol End -->
            <!-- Filter -->
            <div class="filter-table flex flex-row mb-3">
                <div class="filter-table-kategori flex flex-row items-center mr-5">
                    <p class="text-sm mr-1">Kategori:</p>
                    <select class="select select-sm">
                        <option disabled selected>Kategori</option>
                        <?php foreach ($kategori as $k) : ?>
                            <option value="<?= $k['kategori_id'] ?>"><?= esc($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-table-kategori flex flex-row items-center">
                    <p class="text-sm mr-1">Stok: </p>
                    <select class="select select-sm">
                        <option disabled selected>Stok</option>
                        <option>Semua</option>
                        <option>Stok Menipis</option>
                    </select>
                </div>
            </div>
            <!-- Filter end -->
        </div>
        <!-- Modal -->
        <dialog id="barangModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="barangModalLabel">Form Barang</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form id="barangForm">
                <input type="hidden" name="barang_id" id="barang_id">
                <!-- Nama barang -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama Barang</legend>
                    <input type="text" class="input w-full" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" required/>
                    <div class="invalid-feedback" id="nama_barang-error"></div>
                </fieldset>
                <!-- Nama barang end -->
                <!-- Kategori -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Kategori</legend>
                    <select class="select w-full" id="kategori_id" name="kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($kategori as $k) : ?>
                            <option value="<?= $k['kategori_id'] ?>">
                                <?= esc($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>
                <!-- Kategori end -->
                <!-- Satuan -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Satuan</legend>
                    <select class="select w-full" id="satuan_id" name="satuan_id">
                        <option value="">-- Pilih Satuan --</option>
                        <?php foreach($satuan as $s) : ?>
                            <option value="<?= $s['satuan_id'] ?>">
                                <?= esc($s['nama_satuan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>
                <!-- Satuan end -->
                <!-- Harga -->
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
                    id="harga"
                    name="harga"
                    />
                    <p class="validator-hint">Minimal Rp. 1.000</p>
                    <div class="invalid-feedback" id="harga-error"></div>
                </fieldset>
                <!-- Harga end -->
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
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelBarang" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
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
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('admin/barang/ajaxlist') ?>",
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