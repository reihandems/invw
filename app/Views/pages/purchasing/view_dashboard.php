<?= $this->extend('layout/main/purchasing/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="ringkasan font-semibold mt-8">
        <h5 style="color: var(--secondary-text); margin-bottom: 18px;">Ringkasan</h5>
        <!-- Informasi -->
        <div class="grid grid-cols-12 text-center gap-5">
            <div class="col-span-12 sm:col-span-6 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total PO Hari Ini</p>
                <h1><?= $totalPOToday ?></h1>
            </div>
            <div class="col-span-12 sm:col-span-6 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total PO Bulan Ini</p>
                <h1><?= $totalPOMonth ?></h1>
            </div>
            <div class="col-span-12 sm:col-span-6 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">PO Aktif</p>
                <h1><?= $poActive ?></h1>
            </div>
            <div class="col-span-12 sm:col-span-6 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">PR Menunggu Approval</p>
                <h1><?= $prWaiting ?></h1>
            </div>
        </div>
        <!-- Informasi end -->
    </div>
    <div class="po-terbaru font-semibold mt-8">
        <h5 style="color: var(--secondary-text); margin-bottom: 18px;">PO Terbaru</h5>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 pb-5">
            <table id="tabelPOTerbaru" class="table table-md display nowrap">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Supplier</th>
                        <th>Gudang</th>
                        <th>Status</th>
                        <th>Tanggal</th>
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
            var table = $('#tabelPOTerbaru').DataTable({
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
                    "url": "<?= base_url('/purchasing/dashboard/latest-po') ?>",
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
                    {
                        targets: 4,
                        className: "!text-start"
                    }
                ]
            });
        })
    </script>
<?= $this->endSection() ?>