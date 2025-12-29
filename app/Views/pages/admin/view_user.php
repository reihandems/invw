<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-user font-semibold mt-6">
        <div class="atas flex flex-col-reverse md:flex-row justify-between">
            <!-- Tombol -->
            <button class="btn bg-[#5160FC] text-white" style="margin-bottom: 14px;" id="add-btn" onclick="userModal.showModal()">+ Tambah User</button>
            <!-- Tombol End -->
        </div>
        <!-- Modal -->
        <dialog id="userModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="userModalLabel">Form User</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form id="userForm" enctype="multipart/form-data">
                <input type="hidden" name="user_id" id="user_id">
                <!-- Nama lengkap -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama Lengkap</legend>
                    <input type="text" class="input w-full" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap user" required/>
                    <div class="invalid-feedback" id="nama_lengkap-error"></div>
                </fieldset>
                <!-- Nama lengkap end -->
                <!-- Username -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Username</legend>
                    <input type="text" class="input w-full" id="username" name="username" placeholder="Masukkan username" required/>
                    <div class="invalid-feedback" id="username-error"></div>
                </fieldset>
                <!-- Username end -->
                <!-- Role -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Role</legend>
                    <select class="select w-full" id="role_id" name="role_id">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($role as $r) : ?>
                            <option value="<?= $r['role_id'] ?>">
                                <?= esc($r['nama_role']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="role_id-error"></div>
                </fieldset>
                <!-- Role end -->
                <!-- Email -->
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Email</legend>
                    <input type="email" class="input w-full" id="email" name="email" placeholder="Masukkan email" required/>
                    <div class="invalid-feedback" id="email-error"></div>
                </fieldset>
                <!-- Email end -->
                <!-- Password -->
                <div id="password-wrapper">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Password</legend>
                        <input type="password" class="input w-full" id="password" name="password" placeholder="Masukkan password" required/>
                        <div class="invalid-feedback" id="password-error"></div>
                    </fieldset>
                </div>
                <!-- Password end -->
                <!-- Gambar -->
                <!-- Preview gambar (hidden by default) -->
                <div id="gambar-preview-wrapper" class="my-3 hidden">
                    <p class="text-sm font-semibold mb-2">Preview Gambar</p>
                    <img
                        id="gambar-preview"
                        src=""
                        class="w-24 h-24 rounded-full object-cover border"
                        alt="Preview gambar"
                    >
                </div>

                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Gambar</legend>
                    <input type="file" class="file-input w-full" id="gambar" name="gambar" />
                    <label class="label">Ukuran Maks 2MB</label>
                </fieldset>
                <!-- Gambar end -->
            </form>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button type="submit" class="btn bg-[#5160FC] text-white" id="save-btn" form="userForm">Simpan</button>
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
        </dialog>
        <!-- Modal end -->
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelUser" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Gambar</th>
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
            var table = $('#tabelUser').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('admin/user/ajaxlist') ?>",
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
                    {"data": 4},
                    {"data": 5},
                    {"data": 6}
                ],
                "columnDefs": [
                    {"targets": [5], "orderable": false},
                    {"targets": [6], "orderable": false}
                ]
            });

            // 1. Tambah Data (Membuka Modal)
            $('#add-btn').on('click', function() {
                $('#userForm')[0].reset();
                $('#userModalLabel').text('Tambah Data User');

                $('#user_id').val('');
                $('.invalid-feedback').text('').hide();

                $('#password-wrapper').show();
                $('#password').prop('required', true);

                // HIDE preview
                $('#gambar-preview-wrapper').addClass('hidden');
                $('#gambar-preview').attr('src', '');

                $('#userModal').modal('show');
            });

            // 2. Simpan Data (Tambah dan Edit)
            $('#userForm').on('submit', function(e) {
                // ... (Kode pencegahan default dan persiapan FormData)

                e.preventDefault();

                var formData = new FormData(this);
                formData.append(csrfName, csrfHash);

                $.ajax({
                    url: "<?= site_url('/admin/user/save'); ?>",
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
                            $('#userModal')[0].close();

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
            $('#tabelUser').on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                $('#userForm')[0].reset();
                $('#userModalLabel').text('Ubah Data User');

                $('#password-wrapper').hide();
                $('#password').prop('required', false).val('');

                $('.invalid-feedback').removeClass('d-block').hide();
                $('.form-control').removeClass('is-invalid');

                // Ambil data barang dari controller menggunakan AJAX
                $.ajax({
                    url: "<?= site_url('/admin/user/getUser/'); ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        // Isi form dengan data yang didapatkan
                        $('#user_id').val(data.user_id);
                        $('#nama_lengkap').val(data.nama_lengkap);
                        $('#username').val(data.username);
                        $('#email').val(data.email);
                        $('#role_id').val(data.role_id);
                        // Pastikan nama input sesuai dengan nama kolom di database: gambar
                        // === PREVIEW GAMBAR SAAT EDIT ===
                        if (data.gambar) {
                            $('#gambar-preview')
                                .attr('src', '<?= base_url('uploads/') ?>' + data.gambar);
                            $('#gambar-preview-wrapper').removeClass('hidden');
                        } else {
                            $('#gambar-preview-wrapper').addClass('hidden');
                        }
                        
                        $('#userModal')[0].showModal();

                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data untuk Edit: ' + xhr.responseText);
                    }
                })
            });


            // 4. Hapus data
            $('#tabelUser').on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Anda yakin ingin menghapus data ini?')) {
                    // Lakukan AJAX Delete
                    $.ajax({
                        url: "<?= site_url('admin/user/deleteData'); ?>/" + id,
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