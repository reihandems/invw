<?= $this->extend('layout/main/purchasing/view_main') ?>
<?= $this->section('content') ?>
    <div class="po-terbaru font-semibold mt-8">
        <!-- PR -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 pb-5">
            <table id="tabelPO" class="table table-md display nowrap">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Gudang</th>
                        <th>No. PR</th>
                        <th>Supplier</th>
                        <th>Purchasing</th>
                        <th>Tanggal Order</th>
                        <th>Status</th>
                        <th>Jumlah Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                </tbody>
            </table>
        </div>
        <!-- PR end -->
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
            var table = $('#tabelPO').DataTable({
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
                    "url": "<?= base_url('/purchasing/purchase-request/ajaxlist') ?>",
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
                    {"data": 6},
                    {"data": 7}
                ],
                "columnDefs": [
                    {"targets": [4], "orderable": false},
                    {"targets": [7], "orderable": false}
                ]
            });
        })
    </script>
<?= $this->endSection() ?>