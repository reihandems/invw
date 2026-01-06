<?= $this->extend('layout/main/gudang/view_main') ?>
<?= $this->section('content') ?>
    <!-- Main Content -->
    <div class="data-barang font-semibold mt-6">
        <div role="tablist" class="tabs tabs-box w-72 mb-5 md:mb-3">
                <a role="tab" class="tab <?= ($tab === 'pr') ? 'tab-active' : '' ?>" href="<?= base_url('gudang/purchase-request') ?>">Purchase Request</a>
                <a role="tab" class="tab <?= ($tab === 'po') ? 'tab-active' : '' ?>" href="<?= base_url('gudang/purchase-order') ?>">Purchase Order</a>
        </div>
        <dialog id="modalTerimaBarang" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-4xl">
                <h3 class="font-bold text-lg mb-4">Proses Penerimaan Barang</h3>
                <hr class="my-3" style="color: var(--secondary-stroke);">
                
                <form id="formBarangMasuk">
                    <input type="hidden" id="in_po_id" name="po_id">
                    <input type="hidden" id="in_warehouse_id" name="warehouse_id">

                    <div class="grid grid-cols-12 gap-4 mb-4">
                        <div class="col-span-12 md:col-span-6">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">No. PO</legend>
                                <input type="text" class="input w-full" id="in_po_number" name="in_po_number" readonly />
                            </fieldset>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Tanggal Masuk</legend>
                                <input type="datetime-local" name="tanggal_masuk" class="input w-full" required value="<?= date('Y-m-d\TH:i') ?>" />
                            </fieldset>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-base-200">
                                    <th>Nama Barang</th>
                                    <th class="text-center">Qty Pesanan (PO)</th>
                                    <th class="text-center w-40">Qty Diterima</th>
                                    <th class="text-center">Rak Penyimpanan</th>
                                </tr>
                            </thead>
                            <tbody id="itemPenerimaan">
                                </tbody>
                        </table>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Keterangan</legend>
                        <textarea class="textarea h-24 w-full" name="keterangan" placeholder="Contoh: Barang diterima dalam kondisi baik, segel utuh."></textarea>
                    </fieldset>

                    <hr class="my-3" style="color: var(--secondary-stroke);">

                    <div class="modal-action">
                        <button type="button" class="btn btn-ghost" onclick="modalTerimaBarang.close()">Batal</button>
                        <button type="submit" class="btn bg-[#5160FC] text-white">Simpan Barang Masuk & Update Stok</button>
                    </div>
                </form>
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
            <table id="tabelPO" class="table responsive nowrap display">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. PO</th>
                        <th>Supplier</th>
                        <th>Gudang Tujuan</th>
                        <th>Estimasi Datang</th>
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
            var table = $('#tabelPO').DataTable({
                // Simpan objek DataTables ke variabel 'table'
                "processing": true,
                "serverSide": false,
                "info": false,
                "responsive": true,
                "ajax": {
                    "url": "<?= base_url('gudang/purchase-order/ajaxlist') ?>",
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
                    {"data": 5}
                ],
                "columnDefs": [
                    {"targets": [5], "orderable": false}
                ]
            });

            $(document).on('click', '.btnProsesMasuk', function() {
                const poId = $(this).data('id');
                const poNum = $(this).data('po');
                const whId = $(this).data('warehouse');

                // Reset Form & Set Header
                $('#in_po_id').val(poId);
                $('#in_po_number').val(poNum);
                $('#in_warehouse_id').val(whId);
                $('#itemPenerimaan').html('<tr><td colspan="3" class="text-center">Loading items...</td></tr>');

                $.get("<?= site_url('gudang/purchase-order/get-racks') ?>/" + whId, function(racks) {
                    let rackOptions = '<option value="" disabled selected>Pilih Rak</option>';
                    racks.forEach(r => {
                        rackOptions += `<option value="${r.rack_id}">${r.kode_rak} | ${r.deskripsi}</option>`;
                    });

                    $.ajax({
                        // Ganti url di JS tombol .btnProsesMasuk menjadi:
                        url: "<?= site_url('gudang/purchase-order/detail-po') ?>/" + poId,
                        type: "GET",
                        dataType: "json",
                        success: function(res) {
                            if (res.status) {
                                let html = '';
                                res.items.forEach(item => {
                                    html += `
                                        <tr>
                                            <td>
                                                <div class="font-bold">${item.nama_barang}</div>
                                                <div class="text-xs opacity-50 text-gray-500 font-bold">SKU: ${item.sku}</div>
                                                <input type="hidden" name="items[${item.barang_id}][barang_id]" value="${item.barang_id}">
                                            </td>
                                            <td class="text-center font-bold text-lg">${item.qty}</td>
                                            <td>
                                                <input type="number" 
                                                    name="items[${item.barang_id}][jumlah]" 
                                                    class="input input-bordered input-sm w-full text-center" 
                                                    value="${item.qty}" 
                                                    min="1" 
                                                    max="${item.qty}">
                                            </td>
                                            <td>
                                                <select name="items[${item.barang_id}][rack_id]" class="select w-full" required>
                                                    ${rackOptions}
                                                </select>
                                            </td>
                                        </tr>
                                    `;
                                });
                                $('#itemPenerimaan').html(html);
                                modalTerimaBarang.showModal();
                            }
                        }
                    });

                });

            });

            $('#formBarangMasuk').on('submit', function(e) {
                e.preventDefault();
                
                const btnSubmit = $(this).find('button[type="submit"]');
                btnSubmit.addClass('loading').attr('disabled', true);

                $.ajax({
                    url: "<?= site_url('gudang/purchase-order/save') ?>",
                    type: "POST",
                    data: $(this).serialize() + "&<?= csrf_token() ?>=<?= csrf_hash() ?>",
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            alert('Berhasil! Stok telah diperbarui.');
                            location.reload();
                        } else {
                            alert('Gagal: ' + res.message);
                        }
                    },
                    complete: function() {
                        btnSubmit.removeClass('loading').attr('disabled', false);
                    }
                });
            });

        });
    </script>
<?= $this->endSection() ?>