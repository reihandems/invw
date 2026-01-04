<?= $this->extend('layout/main/manager/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <dialog id="detailPrModal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg" id="d_pr_number">Detail Purchase Request</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                <div class="grid grid-cols-12 gap-4 mt-4 text-sm">
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Tanggal:</b> <span id="d_created_at"></span></p>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Status:</b> <span id="d_status"></span></p>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Gudang:</b> <span id="d_warehouse"></span></p>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <p><b>Pengaju:</b> <span id="d_created_by"></span></p>
                    </div>
                </div>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <div class="mt-4">
                <p class="text-sm font-semibold mb-1">Catatan</p>
                <p class="text-sm bg-base-200 p-3 rounded" id="d_notes"></p>
                </div>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <div class="mt-6">
                <p class="font-semibold mb-2">Detail Barang</p>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="detailItemsTable"></tbody>
                    </table>
                </div>
                </div>

                <hr class="my-5" style="color: var(--secondary-stroke);">
                <div class="grid grid-cols-12 gap-3">
                    <?php foreach ($pr as $row) : ?>
                        <div class="col-span-6">
                            <button id="btnReject" class="btn bg-white text-red-500 w-full btn-reject" data-pr-id="<?= $row['pr_id'] ?>">Tolak</button>
                        </div>
                        <div class="col-span-6">
                            <button id="btnApprove" class="btn bg-[#5160FC] text-white w-full btn-approve" data-pr-id="<?= $row['pr_id'] ?>">Setujui</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </dialog>

        <?php if (session()->getFlashdata('success')): ?>
                    <div class="toast toast-top toast-center">
                        <div class="alert alert-success alert-soft border-shaded-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4"/>
                            </svg>
                            <span><?= session()->getFlashdata('success') ?></span>
                        </div>
                    </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
                    <div class="toast toast-top toast-center">
                        <div class="alert alert-success alert-soft border-shaded-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4"/>
                            </svg>
                            <span><?= session()->getFlashdata('error') ?></span>
                        </div>
                    </div>
        <?php endif; ?>
        <!-- Log -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
            <table id="tabelPR" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. PR</th>
                        <th>Gudang</th>
                        <th>Dibuat Oleh</th>
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
            var table = $('#tabelPR').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('manager/purchase-request/ajaxlist') ?>",
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
                    {"targets": [5], "orderable": false}
                ]
            });

            function approvePR(prId) {
                $.ajax({
                    url: '<?= base_url("manager/purchase-request/approve") ?>',
                    type: 'POST',
                    data: {
                        pr_id: prId,
                        <?= csrf_token() ?>: csrfHash
                    },
                    dataType: 'json',
                    success: function (res) {
                        csrfHash = res.token;

                        if (res.status) {
                            alert(res.message);
                            table.ajax.reload(null, false);
                        } else {
                            alert(res.message);
                        }
                    }
                });
            }

            function rejectPR(prId) {
                $.ajax({
                    url: '<?= base_url("manager/purchase-request/reject") ?>',
                    type: 'POST',
                    data: {
                        pr_id: prId,
                        <?= csrf_token() ?>: csrfHash
                    },
                    dataType: 'json',
                    success: function (res) {
                        csrfHash = res.token;

                        if (res.status) {
                            alert(res.message);
                            table.ajax.reload(null, false);
                        } else {
                            alert(res.message);
                        }
                    }
                });
            }


            $(document).on('click', '.btn-approve', function () {
                const prId = $(this).data('pr-id');

                console.log('Approve PR:', prId);

                $.ajax({
                    url: '<?= base_url("manager/purchase-request/approve") ?>',
                    type: 'POST',
                    data: { pr_id: prId },
                    dataType: 'json',
                    success: function (res) {
                        alert(res.message);
                        location.reload();
                    }
                });
            });

            $(document).on('click', '.btn-reject', function () {
                const prId = $(this).data('pr-id');

                console.log('Reject PR:', prId);

                $.ajax({
                    url: '<?= base_url("manager/purchase-request/reject") ?>',
                    type: 'POST',
                    data: {
                        pr_id: prId,
                        <?= csrf_token() ?>: csrfHash
                    },
                    dataType: 'json',
                    success: function (res) {
                        csrfHash = res.token;

                        if (res.status) {
                            alert(res.message);
                            table.ajax.reload(null, false);
                        } else {
                            alert(res.message);
                        }
                    }
                });
            });


        })

        function openDetailPR(prId) {
            fetch(`/manager/purchase-request/detail/${prId}`)
                .then(res => res.json())
                .then(res => {
                if (!res.status) return alert('Gagal ambil data');

                const h = res.header;
                document.getElementById('d_pr_number').textContent = h.pr_number;
                document.getElementById('d_created_at').textContent = h.created_at;
                document.getElementById('d_status').textContent = h.status;
                document.getElementById('d_warehouse').textContent = h.nama_gudang;
                document.getElementById('d_created_by').textContent = h.created_by;
                document.getElementById('d_notes').textContent = h.notes ?? '-';

                const tbody = document.getElementById('detailItemsTable');
                tbody.innerHTML = '';

                res.items.forEach((item, i) => {
                    tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${item.nama_barang}</td>
                        <td>${item.qty}</td>
                    </tr>
                    `;
                });

                detailPrModal.showModal();
                });
        }
    </script>
<?= $this->endSection() ?>