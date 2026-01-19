<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
<!-- Main Content -->
<div class="data-laporan font-semibold mt-6">

    <div role="tablist" class="tabs tabs-lifted">
        <input type="radio" name="tabs_laporan" role="tab" class="tab" aria-label="Laporan Operasional" checked />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <!-- Existing Laporan Content -->
            <div class="atas flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Laporan</legend>
                    <select id="jenis_laporan" name="jenis_laporan" class="select w-full md:w-auto">
                        <option disabled selected>Pilih Jenis Laporan</option>
                        <option value="barang">Barang Masuk / Keluar</option>
                        <option value="stok">Stok Opname</option>
                        <option value="purchasing">Purchasing</option>
                    </select>
                </fieldset>
                <!-- Filter -->
                <div class="filter-table flex flex-col md:flex-row md:items-end gap-4 w-full md:w-auto">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Tanggal Awal</legend>
                        <input type="date" class="input w-full md:w-auto" id="tanggal_awal" name="tanggal_awal" />
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Tanggal Akhir</legend>
                        <input type="date" class="input w-full md:w-auto" id="tanggal_akhir" name="tanggal_akhir" />
                    </fieldset>
                    <div class="pb-1">
                        <button id="btnTampilkan" class="btn bg-[#5160FC] text-white w-full mb-3 md:mb-0 md:w-auto">
                            Tampilkan
                        </button>
                        <button id="btnExportPDF" class="btn btn-outline border-[#5160FC] text-[#5160FC] w-full md:w-auto md:ml-2" style="display:none;">
                            📄 Export PDF
                        </button>
                    </div>
                </div>
                <!-- Filter end -->
            </div>
            <!-- Table Container (akan di-generate ulang setiap ganti laporan) -->
            <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
                <table class="table responsive nowrap display" id="laporanTable">
                    <thead id="tableHead">
                        <tr>
                            <th colspan="5" class="text-center text-gray-400">
                                Silakan pilih jenis laporan dan tanggal
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="5" class="text-center text-gray-400">
                                Silakan pilih tanggal dan klik <b>Tampilkan</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <input type="radio" name="tabs_laporan" role="tab" class="tab" aria-label="Upload Laporan Manager" />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <!-- New: Manager Report Upload -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Upload -->
                <div class="md:col-span-1">
                    <h3 class="text-lg font-bold mb-4">Upload Laporan Baru</h3>
                    <form id="formUploadLaporan" enctype="multipart/form-data">
                        <fieldset class="fieldset mb-3">
                            <legend class="fieldset-legend">Judul Laporan</legend>
                            <input type="text" name="judul_laporan" class="input w-full" placeholder="Contoh: Laporan Stok Januari" required />
                        </fieldset>
                        <fieldset class="fieldset mb-3">
                            <legend class="fieldset-legend">Jenis Laporan</legend>
                            <select name="jenis_laporan" class="select w-full" required>
                                <option value="stok">Stock Opname</option>
                                <option value="purchasing">Purchasing</option>
                                <option value="barang">Barang Masuk/Keluar</option>
                                <option value="keuangan">Keuangan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </fieldset>
                        <fieldset class="fieldset mb-3">
                            <legend class="fieldset-legend">Periode</legend>
                            <input type="text" name="periode_laporan" class="input w-full" placeholder="Contoh: Januari 2024" required />
                        </fieldset>
                        <fieldset class="fieldset mb-4">
                            <legend class="fieldset-legend">File PDF</legend>
                            <input type="file" name="file_laporan" class="file-input w-full" accept=".pdf" required />
                        </fieldset>
                        <button type="submit" class="btn bg-[#5160FC] text-white w-full">Upload & Kirim</button>
                    </form>
                </div>

                <!-- List Sent Reports -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold mb-4">Riwayat Laporan Terkirim</h3>
                    <div class="overflow-x-auto">
                        <table class="table" id="managerLaporanTable">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Jenis</th>
                                    <th>Periode</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="managerLaporanBody">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br>
</div>
<!-- Main Content end -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // --- LOGIC LAPORAN OPERASIONAL (EXISTING) ---
        let laporanTable = null;
        let currentJenis = null;

        const tableConfigs = {
            'barang': {
                columns: [{
                    title: 'No',
                    width: '5%'
                }, {
                    title: 'Nama Barang'
                }, {
                    title: 'Total Masuk',
                    className: 'text-center'
                }, {
                    title: 'Total Keluar',
                    className: 'text-center'
                }, {
                    title: 'Selisih',
                    className: 'text-center'
                }]
            },
            'stok': {
                columns: [{
                    title: 'No',
                    width: '5%'
                }, {
                    title: 'Nama Barang'
                }, {
                    title: 'Stok Sistem',
                    className: 'text-center'
                }, {
                    title: 'Stok Fisik',
                    className: 'text-center'
                }, {
                    title: 'Selisih',
                    className: 'text-center'
                }]
            },
            'purchasing': {
                columns: [{
                    title: 'No',
                    width: '5%'
                }, {
                    title: 'Supplier'
                }, {
                    title: 'Tanggal Order',
                    className: 'text-center'
                }, {
                    title: 'Status',
                    className: 'text-center'
                }, {
                    title: 'Total Harga',
                    className: 'text-end'
                }]
            }
        };

        function initDataTable(jenis) {
            if (laporanTable) {
                laporanTable.destroy();
                $('#laporanTable').empty();
            }

            const config = tableConfigs[jenis];
            let headerHtml = '<tr>';
            config.columns.forEach(col => {
                headerHtml += `<th class="${col.className || ''}">${col.title}</th>`;
            });
            headerHtml += '</tr>';

            $('#laporanTable').html(`<thead>${headerHtml}</thead><tbody></tbody>`);

            laporanTable = $('#laporanTable').DataTable({
                searching: false,
                ordering: false,
                processing: true,
                serverSide: false,
                language: {
                    emptyTable: "Tidak ada data untuk ditampilkan",
                    processing: "Memuat data..."
                },
                columns: config.columns.map(col => ({
                    className: col.className || ''
                })),
                ajax: {
                    url: "<?= site_url('admin/laporan/data') ?>",
                    type: "POST",
                    data: function() {
                        return {
                            jenis_laporan: jenis,
                            tanggal_awal: $('#tanggal_awal').val(),
                            tanggal_akhir: $('#tanggal_akhir').val(),
                        };
                    }
                }
            });

            currentJenis = jenis;
            $('#btnExportPDF').show();
        }

        $('#jenis_laporan').on('change', function() {
            const jenis = $(this).val();
            if (jenis) initDataTable(jenis);
        });

        $('#btnTampilkan').on('click', function() {
            if (!$('#jenis_laporan').val()) return alert('Pilih jenis laporan dulu');
            if (!$('#tanggal_awal').val() || !$('#tanggal_akhir').val()) return alert('Pilih tanggal awal dan akhir');
            if (laporanTable) laporanTable.ajax.reload();
        });

        $('#btnExportPDF').on('click', function() {
            if (!currentJenis) return;
            const params = new URLSearchParams({
                jenis: currentJenis,
                awal: $('#tanggal_awal').val(),
                akhir: $('#tanggal_akhir').val()
            });
            window.open(`<?= site_url('admin/laporan/export-pdf') ?>?${params}`, '_blank');
        });


        // --- LOGIC LAPORAN MANAGER (NEW) ---
        loadManagerReports();

        $('#formUploadLaporan').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            $.ajax({
                url: '<?= site_url('admin/laporan/upload') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        alert(res.message);
                        $('#formUploadLaporan')[0].reset();
                        loadManagerReports();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Gagal mengupload laporan');
                }
            });
        });

        function loadManagerReports() {
            $.get('<?= site_url('admin/laporan/manager-list') ?>', function(res) {
                const tbody = $('#managerLaporanBody');
                tbody.empty();

                if (res.data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center">Belum ada laporan yang diupload</td></tr>');
                    return;
                }

                res.data.forEach(item => {
                    const date = new Date(item.created_at).toLocaleDateString('id-ID');
                    tbody.append(`
                        <tr>
                            <td>${item.judul}</td>
                            <td><span class="badge badge-outline">${item.jenis_laporan}</span></td>
                            <td>${item.periode}</td>
                            <td>${date}</td>
                            <td>
                                <button class="btn btn-sm btn-error btn-outline btn-delete" data-id="${item.id}">Hapus</button>
                            </td>
                        </tr>
                    `);
                });
            });
        }

        $(document).on('click', '.btn-delete', function() {
            if (!confirm('Yakin ingin menghapus laporan ini?')) return;
            const id = $(this).data('id');
            $.get(`<?= site_url('admin/laporan/delete/') ?>${id}`, function(res) {
                if (res.status === 'success') {
                    loadManagerReports();
                } else {
                    alert(res.message);
                }
            });
        });

    });
</script>
<?= $this->endSection() ?>