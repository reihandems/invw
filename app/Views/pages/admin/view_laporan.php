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
                    <legend class="fieldset-legend">Tanggal Awal</legend>
                    <input type="date" class="input w-full md:w-auto" id="tanggal_akhir" name="tanggal_akhir" />
                </fieldset>
                <div class="pb-1">
                    <button id="btnTampilkan" class="btn bg-[#5160FC] text-white w-full md:w-auto">
                        Tampilkan
                    </button>
                </div>
            </div>
            <!-- Filter end -->
        </div>
        <!-- Table -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table class="table responsive nowrap display" id="laporanTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Total Masuk</th>
                        <th>Total Keluar</th>
                        <th>Selisih</th>
                    </tr>
                </thead>
                <tbody>
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
        $(document).ready(function () {

            var laporanTable = $('#laporanTable').DataTable({
                searching: false,
                ordering: false,
                processing: true,
                serverSide: false,
                ajax: {
                    url: "<?= site_url('admin/laporan/data') ?>",
                    type: "POST",
                    data: function () {
                        return {
                            jenis_laporan: $('#jenis_laporan').val(),
                            tanggal_awal: $('#tanggal_awal').val(),
                            tanggal_akhir: $('#tanggal_akhir').val(),
                        };
                    }
                }
            });

            $('#btnTampilkan').on('click', function () {
                if (!$('#jenis_laporan').val()) {
                    alert('Pilih jenis laporan dulu');
                    return;
                }
                laporanTable.ajax.reload();
            });

        });
    </script>
<?= $this->endSection() ?>