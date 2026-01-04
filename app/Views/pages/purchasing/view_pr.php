<?= $this->extend('layout/main/purchasing/view_main') ?>
<?= $this->section('content') ?>
    <div class="pr-terbaru font-semibold mt-8">
        <dialog id="modalGeneratePO" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-5xl">
                <h3 class="font-bold text-lg mb-4">Buat Purchase Order</h3>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <!-- PR Info -->
                <div class="grid grid-cols-12 gap-4 mb-4">
                    <div class="col-span-12 md:col-span-4">
                        <p><b>No PR:</b> <span id="poPrNumber">-</span></p>
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <p><b>Tanggal PR:</b> <span id="poPrDate">-</span></p>
                    </div>
                    <div class="col-span-12 md:col-span-4">
                        <p><b>Gudang:</b> <span id="poWarehouse">-</span></p>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Supplier</legend>
                            <select id="supplier_id" class="select select-bordered w-full">
                                <option value="">-- Pilih Supplier --</option>
                                <!-- diisi via server / ajax -->
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Tanggal Pengiriman</legend>
                            <input type="date" id="expected_delivery_date" name="expected_delivery_date" class="input w-full" />
                        </fieldset>
                    </div>
                    <div class="col-span-12">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Catatan</legend>
                            <textarea id="po_notes" name="notes" class="textarea h-24 w-full" placeholder="Masukkan catatan untuk supplier"></textarea>
                            <div class="label">Catatan untuk supplier</div>
                        </fieldset>
                    </div>
                </div>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <!-- Detail Barang -->
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="poItemBody">
                            <!-- render via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Total -->
                <div class="flex justify-end mt-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-xl font-bold" id="poGrandTotal">Rp 0</p>
                    </div>
                </div>

                <hr class="my-3" style="color: var(--secondary-stroke);">

                <!-- Action -->
                <div class="modal-action">
                    <button class="btn btn-ghost" onclick="modalGeneratePO.close()">Batal</button>
                    <button id="btnSubmitPO" class="btn bg-[#5160FC] text-white">
                        Generate PO
                    </button>
                </div>
            </div>
        </dialog>

        <!-- PR -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 pb-5">
            <table id="tabelPR" class="table table-md display nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. PR</th>
                        <th>Gudang</th>
                        <th>Disetujui Pada</th>
                        <th>Aksi</th>
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

        function formatRupiah(angka) {
            return parseInt(angka).toLocaleString('id-ID');
        }

        function formatTanggal(tgl) {
            return new Date(tgl).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        
        $(document).ready(function() {
            // Menampilkan data ke dalam dataTables
            var table = $('#tabelPR').DataTable({
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
                    {"data": 4}
                ],
                "columnDefs": [
                    {"targets": [4], "orderable": false}
                ]
            });

            function renderGeneratePOHeader(h) {
                $('#poPrNumber').text(h.pr_number);
                $('#poPrDate').text(formatTanggal(h.created_at));
                $('#poWarehouse').text(h.nama_gudang);
            }



            function renderGeneratePOItems(items) {
                let html = '';

                items.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${item.sku ?? '-'}</td>
                            <td>${item.nama_barang}</td>
                            <td class="text-center">${item.qty}</td>

                            <td>
                                <input 
                                    type="number"
                                    class="input input-bordered input-sm w-full price-input"
                                    name="harga[]"
                                    data-qty="${item.qty}"
                                    placeholder="Harga"
                                    min="0"
                                >
                                <input type="hidden" name="barang_id[]" value="${item.barang_id}">
                                <input type="hidden" name="qty[]" value="${item.qty}">
                            </td>

                            <td class="text-right subtotal">
                                Rp 0
                            </td>
                        </tr>
                    `;
                });

                $('#poItemBody').html(html);
                hitungTotalPO();
            }

            function renderSupplierOptions(suppliers) {
                let html = '<option value="">-- Pilih Supplier --</option>';

                suppliers.forEach(s => {
                    html += `<option value="${s.supplier_id}">
                        ${s.nama_supplier}
                    </option>`;
                });

                $('#supplier_id').html(html);
            }


            function hitungTotalPO() {
                let grandTotal = 0;

                $('#poItemBody tr').each(function () {
                    const priceInput = $(this).find('.price-input');
                    const harga = parseFloat(priceInput.val()) || 0;
                    const qty = parseFloat(priceInput.data('qty')) || 0;

                    const subtotal = harga * qty;
                    grandTotal += subtotal;

                    $(this).find('.subtotal').text('Rp ' + formatRupiah(subtotal));
                });

                $('#poGrandTotal').text('Rp ' + formatRupiah(grandTotal));
            }

            let currentPrId = null;

            $(document).on('click', '.btnGeneratePO', function () {
                const prId = $(this).data('id');

                currentPrId = prId;

                $.ajax({
                    url: "<?= site_url('purchasing/purchase-request/detail') ?>/" + prId,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        if (!res.status) {
                            alert(res.message);
                            return;
                        }

                        renderGeneratePOHeader(res.header);
                        renderGeneratePOItems(res.items);
                        renderSupplierOptions(res.suppliers);


                        modalGeneratePO.showModal();
                    },
                    error: function () {
                        alert('Gagal mengambil data PR');
                    }
                });
            });
            
            $(document).on('input', '.price-input', function () {
                hitungTotalPO();
            });

            $('#btnSubmitPO').off('click').on('click', function (e) {
                e.preventDefault();
                
                const btn = $(this);
                const originalText = btn.text();

                if (!currentPrId) {
                    alert('PR ID tidak ditemukan');
                    return;
                }

                const supplierId = $('#supplier_id').val();
                if (!supplierId) {
                    alert('Supplier wajib dipilih');
                    return;
                }

                if (!$('#expected_delivery_date').val()) {
                    alert('Tanggal estimasi pengiriman wajib diisi');
                    return;
                }

                // 1. Disable tombol untuk mencegah double click
                btn.prop('disabled', true).text('Sedang memproses...');

                const formData = {
                    [csrfName]: csrfHash, // Pastikan CSRF ikut terkirim
                    pr_id: currentPrId,
                    supplier_id: supplierId,
                    // TAMBAHKAN DUA BARIS INI:
                    expected_delivery_date: $('#expected_delivery_date').val(),
                    notes: $('#po_notes').val(),
                    barang_id: [],
                    qty: [],
                    harga: []
                };

                $('#poItemBody tr').each(function () {
                    formData.barang_id.push($(this).find('input[name="barang_id[]"]').val());
                    formData.qty.push($(this).find('input[name="qty[]"]').val());
                    formData.harga.push($(this).find('.price-input').val());
                });

                $.ajax({
                    url: "<?= site_url('purchasing/purchase-order/store') ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (res) {
                        if (res.status) {
                            alert('PO berhasil dibuat');
                            modalGeneratePO.close();
                            // Reload halaman atau tabel
                            location.reload(); 
                        } else {
                            alert(res.message);
                            // Update CSRF Hash jika server mengirim yang baru
                            if(res.token) csrfHash = res.token; 
                        }
                    },
                    error: function (xhr) {
                        alert('Terjadi kesalahan pada server. Cek console.');
                        console.error(xhr.responseText);
                    },
                    complete: function () {
                        // 2. Kembalikan tombol jika gagal
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });
            
        })
    </script>
<?= $this->endSection() ?>