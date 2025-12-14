<?= $this->extend('layout/main/admin/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="ringkasan font-semibold mt-8">
        <h5 style="color: var(--secondary-text); margin-bottom: 18px;">Ringkasan</h5>
        <!-- Informasi -->
        <div class="grid grid-rows-1 grid-cols-12 text-center gap-5">
            <div class="col-span-12 sm:col-span-4 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total Barang</p>
                <h1>173</h1>
            </div>
            <div class="col-span-12 sm:col-span-4 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total Supplier</p>
                <h1>3</h1>
            </div>
            <div class="col-span-12 sm:col-span-4 bg-base-100 rounded-md" style="padding: 20px; border: 1px solid var(--secondary-stroke);">
                <p class="text-xs" style="color: var(--secondary-text);">Total User</p>
                <h1>50</h1>
            </div>
        </div>
        <!-- Informasi end -->
    </div>
    <div class="log font-semibold mt-8">
        <h5 style="color: var(--secondary-text); margin-bottom: 18px;">Log Aktivitas Sistem</h5>
        <!-- Modal -->
        <dialog id="barangModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold modal-title" id="barangModalLabel">Form Barang</h3>
            <hr class="my-3" style="color: var(--secondary-stroke);">
            <form id="barangForm">
                <input type="hidden" name="id" id="id">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama Barang</legend>
                    <input type="text" class="input w-full" id="namaBarang" name="namaBarang" placeholder="Masukkan nama barang" required/>
                    <div class="invalid-feedback" id="namaBarang-error"></div>
                </fieldset>
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
                    id="hargaBarang"
                    name="hargaBarang"
                    />
                    <p class="validator-hint">Minimal Rp. 1.000</p>
                    <div class="invalid-feedback" id="hargaBarang-error"></div>
                </fieldset>
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
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 pb-5">
            <table id="tabelActivityLog" class="table table-md display nowrap">
                <thead>
                    <tr>
                        <th>Nama User</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>Referensi</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
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
            var table = $('#tabelActivityLog').DataTable({
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
                    "url": "<?= base_url('/admin/dashboard/activityloglist') ?>",
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
                ]
            });
        })
    </script>
<?= $this->endSection() ?>