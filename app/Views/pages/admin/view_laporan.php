<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
<!-- Main Content -->
<div class="data-laporan font-semibold mt-6">
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

    <br><br>
</div>
<!-- Main Content end -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let laporanTable = null;
        let currentJenis = null;

        // 🎨 Definisi struktur tabel untuk setiap jenis laporan
        const tableConfigs = {
            'barang': {
                columns: [{
                        title: 'No',
                        width: '5%'
                    },
                    {
                        title: 'Nama Barang'
                    },
                    {
                        title: 'Total Masuk',
                        className: 'text-center'
                    },
                    {
                        title: 'Total Keluar',
                        className: 'text-center'
                    },
                    {
                        title: 'Selisih',
                        className: 'text-center'
                    }
                ]
            },
            'stok': {
                columns: [{
                        title: 'No',
                        width: '5%'
                    },
                    {
                        title: 'Nama Barang'
                    },
                    {
                        title: 'Stok Sistem',
                        className: 'text-center'
                    },
                    {
                        title: 'Stok Fisik',
                        className: 'text-center'
                    },
                    {
                        title: 'Selisih',
                        className: 'text-center'
                    }
                ]
            },
            'purchasing': {
                columns: [{
                        title: 'No',
                        width: '5%'
                    },
                    {
                        title: 'Supplier'
                    },
                    {
                        title: 'Tanggal Order',
                        className: 'text-center'
                    },
                    {
                        title: 'Status',
                        className: 'text-center'
                    },
                    {
                        title: 'Total Harga',
                        className: 'text-end'
                    }
                ]
            }
        };

        // 🔄 Fungsi untuk re-initialize DataTable
        function initDataTable(jenis) {
            // Destroy table lama jika ada
            if (laporanTable) {
                laporanTable.destroy();
                $('#laporanTable').empty();
            }

            // Rebuild table structure
            const config = tableConfigs[jenis];
            let headerHtml = '<tr>';
            config.columns.forEach(col => {
                headerHtml += `<th class="${col.className || ''}">${col.title}</th>`;
            });
            headerHtml += '</tr>';

            $('#laporanTable').html(`
                    <thead>${headerHtml}</thead>
                    <tbody></tbody>
                `);

            // Initialize DataTable dengan config baru
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

        // 🎯 Event: Ganti jenis laporan
        $('#jenis_laporan').on('change', function() {
            const jenis = $(this).val();
            if (jenis) {
                initDataTable(jenis);
            }
        });

        // 🔘 Event: Klik Tampilkan
        $('#btnTampilkan').on('click', function() {
            if (!$('#jenis_laporan').val()) {
                alert('Pilih jenis laporan dulu');
                return;
            }
            if (!$('#tanggal_awal').val() || !$('#tanggal_akhir').val()) {
                alert('Pilih tanggal awal dan akhir');
                return;
            }

            if (laporanTable) {
                laporanTable.ajax.reload();
            }
        });

        // 📄 Event: Export PDF (opsional)
        $('#btnExportPDF').on('click', function() {
            if (!currentJenis) return;

            const params = new URLSearchParams({
                jenis: currentJenis,
                awal: $('#tanggal_awal').val(),
                akhir: $('#tanggal_akhir').val()
            });

            window.open(`<?= site_url('admin/laporan/export-pdf') ?>?${params}`, '_blank');
        });

    });
</script>
<?= $this->endSection() ?>