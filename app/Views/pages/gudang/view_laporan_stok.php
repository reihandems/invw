<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <?php if (empty($stock)): ?>
            <div class="badge badge-soft badge-warning w-full mb-3">Belum ada data stok</div>
        <?php endif; ?>
        <?php foreach($stock as $s) : ?>
            <?php if($s['jumlah_stok'] < 5) : ?>
                <div class="badge badge-soft badge-error w-full mb-3">Stok barang (SKU : <?= $s['sku'] ?> / <?= $s['nama_barang'] ?>) sudah menipis</div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="atas flex flex-col md:flex-row justify-between mb-3">
            <!-- Tombol -->
            <a href="<?= site_url('gudang/laporan-stok/export-pdf') ?>" target="_blank" class="btn border border-[#5160FC] text-[#5160FC]">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.26702 14.6797C8.08302 14.6797 7.95902 14.6977 7.89502 14.7157V15.8937C7.97102 15.9117 8.06602 15.9167 8.19702 15.9167C8.67602 15.9167 8.97102 15.6747 8.97102 15.2657C8.97102 14.8997 8.71702 14.6797 8.26702 14.6797ZM11.754 14.6917C11.554 14.6917 11.424 14.7097 11.347 14.7277V17.3377C11.424 17.3557 11.548 17.3557 11.66 17.3557C12.477 17.3617 13.009 16.9117 13.009 15.9597C13.015 15.1297 12.53 14.6917 11.754 14.6917Z" fill="white"/>
                <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2ZM9.498 16.19C9.189 16.48 8.733 16.61 8.202 16.61C8.09902 16.6119 7.99605 16.6059 7.894 16.592V18.018H7V14.082C7.40347 14.022 7.81112 13.9946 8.219 14C8.776 14 9.172 14.106 9.439 14.319C9.693 14.521 9.865 14.852 9.865 15.242C9.864 15.634 9.734 15.965 9.498 16.19ZM13.305 17.545C12.885 17.894 12.246 18.06 11.465 18.06C10.997 18.06 10.666 18.03 10.441 18V14.083C10.8446 14.0243 11.2521 13.9966 11.66 14C12.417 14 12.909 14.136 13.293 14.426C13.708 14.734 13.968 15.225 13.968 15.93C13.968 16.693 13.689 17.22 13.305 17.545ZM17 14.77H15.468V15.681H16.9V16.415H15.468V18.019H14.562V14.03H17V14.77ZM14 9H13V4L18 9H14Z" fill="#5160FC"/>
                </svg>
                Cetak PDF
            </a>
            <!-- Tombol End -->
        </div>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelPR" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>SKU / Barang</th>
                        <th>Kategori</th>
                        <th>Gudang</th>
                        <th>Rak</th>
                        <th>Stok Saat Ini</th>
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
            var table = $('#tabelPR').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('gudang/laporan-stok/ajaxlist') ?>",
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
                    // {"targets": [5], "orderable": false}
                ]
            });
        });
    </script>
<?= $this->endSection() ?>