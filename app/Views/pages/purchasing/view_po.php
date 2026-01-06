<?= $this->extend('layout/main/purchasing/view_main') ?>
<?= $this->section('content') ?>
    <div class="po-terbaru font-semibold mt-8">
        <dialog id="poDetailModal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box md:w-11/12 md:max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>

                <h3 class="font-bold text-lg">Purchase Order - Detail</h3>

                <hr class="my-5" style="color: var(--secondary-stroke);">

                
                <div id="poHeader" class="grid grid-cols-12 gap-4 mb-6 text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-base-200">
                                <th>SKU</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="poDetailItems">
                            </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-start" id="poNotes"></th>
                                <th colspan="3" class="text-right">Grand Total:</th>
                                <th id="poTotal" class="text-[#5160FC] text-lg">Rp 0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Action -->
                <div class="modal-action" id="btnKirim">
                    <a id="btnCetakPDF" href="#" target="_blank" class="btn btn-outline border border-[#5160FC] text-[#5160FC]">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.26702 14.6797C8.08302 14.6797 7.95902 14.6977 7.89502 14.7157V15.8937C7.97102 15.9117 8.06602 15.9167 8.19702 15.9167C8.67602 15.9167 8.97102 15.6747 8.97102 15.2657C8.97102 14.8997 8.71702 14.6797 8.26702 14.6797ZM11.754 14.6917C11.554 14.6917 11.424 14.7097 11.347 14.7277V17.3377C11.424 17.3557 11.548 17.3557 11.66 17.3557C12.477 17.3617 13.009 16.9117 13.009 15.9597C13.015 15.1297 12.53 14.6917 11.754 14.6917Z" fill="white"/>
                        <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2ZM9.498 16.19C9.189 16.48 8.733 16.61 8.202 16.61C8.09902 16.6119 7.99605 16.6059 7.894 16.592V18.018H7V14.082C7.40347 14.022 7.81112 13.9946 8.219 14C8.776 14 9.172 14.106 9.439 14.319C9.693 14.521 9.865 14.852 9.865 15.242C9.864 15.634 9.734 15.965 9.498 16.19ZM13.305 17.545C12.885 17.894 12.246 18.06 11.465 18.06C10.997 18.06 10.666 18.03 10.441 18V14.083C10.8446 14.0243 11.2521 13.9966 11.66 14C12.417 14 12.909 14.136 13.293 14.426C13.708 14.734 13.968 15.225 13.968 15.93C13.968 16.693 13.689 17.22 13.305 17.545ZM17 14.77H15.468V15.681H16.9V16.415H15.468V18.019H14.562V14.03H17V14.77ZM14 9H13V4L18 9H14Z" fill="#5160FC"/>
                        </svg>
                        Cetak PDF
                    </a>
                </div>
            </div>
        </dialog>


        <!-- PO -->
        <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 pb-5">
            <table id="tabelPO" class="table table-md display nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. PO</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                </tbody>
            </table>
        </div>
        <!-- PO end -->
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
                    "url": "<?= base_url('/purchasing/purchase-order/ajaxlist') ?>",
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

            let currentPOId = null;

            function renderPOHeader(h) {
                let statusClass = 'badge-ghost';
                if(h.status === 'draft') statusClass = 'badge-warning';
                if(h.status === 'sent') statusClass = 'badge-info';
                if(h.status === 'received') statusClass = 'badge-success';
                if(h.status === 'cancelled') statusClass = 'badge-danger';
                
                // Logika tombol kirim: Hanya muncul jika status masih DRAFT
                if (h.status === 'draft') {
                    $('#btnKirim').html(`
                        <button class="btn bg-[#5160FC] text-white btn-eksekusi-kirim" data-id="${h.po_id}">
                            Kirim PO
                        </button>
                    `);
                }
                // Gunakan h.order_date sesuai field DB
                $('#poHeader').html(`
                    <div class="col-span-12">
                        <h1 class="mb-3"> ${h.po_number}</h1>
                        <div class="grid grid-cols-12">
                            <div class="col-span-4">
                                <div class="flex flex-row justify-between mb-1">
                                    <p>Tanggal Dibuat:</p>
                                    <p>${formatTanggal(h.order_date) ?? '-'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12">
                            <div class="col-span-4">
                                <div class="flex flex-row justify-between mb-1">
                                    <p>Tanggal Pengiriman:</p>
                                    <p>${formatTanggal(h.expected_delivery_date)}</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12">
                            <div class="col-span-4">
                                <div class="flex flex-row justify-between mb-1">
                                    <p>Status:</p>
                                    <p><span class="badge badge-outline ${statusClass} w-24">${h.status}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <p class="text-sm text-gray-500 mb-3">Vendor</p>
                        <h2 class="mb-2">${h.nama_supplier ?? '-'}</h2>
                        <p>${h.alamat_supplier}</p>
                        <p>${h.kontak}</p>
                    </div>
                    <div class="col-span-6">
                        <p class="text-sm text-gray-500 mb-3">Kirim Ke</p>
                        <h2 class="mb-2">${h.nama_gudang ?? '-'}</h2>
                        <p>${h.alamat_gudang}</p>
                    </div>
                `);
                $('#poNotes').html(`
                    <span>Catatan: <b>${h.notes}</b></span>
                `)
            }

            function renderPODetail(items) {
                let html = '';
                let grandTotal = 0;
                
                items.forEach(item => {
                    const qty = parseFloat(item.qty) || 0;
                    const price = parseFloat(item.price) || 0; // Pastikan pakai .price
                    const subtotal = qty * price;
                    grandTotal += subtotal;

                    html += `
                        <tr>
                            <td>${item.sku ?? '-'}</td>
                            <td>${item.nama_barang}</td>
                            <td>${qty}</td>
                            <td>Rp ${formatRupiah(price)}</td>
                            <td>Rp ${formatRupiah(subtotal)}</td>
                        </tr>
                    `;
                });
                $('#poDetailItems').html(html || '<tr><td colspan="5" class="text-center">Tidak ada item</td></tr>');
                $('#poTotal').text('Rp ' + formatRupiah(grandTotal));
            }

            $(document).on('click', '.btnDetailPO', function () {
                const poId = $(this).data('id');
                
                // Reset modal content
                $('#poHeader').html('<div class="col-span-12 text-center">Memuat data...</div>');
                $('#poDetailItems').html('');
                $('#poTotal').text('Rp 0');

                $.ajax({
                    url: "<?= site_url('purchasing/purchase-order/detail') ?>/" + poId,
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        console.log("Response Full:", res); // LIHAT DI CONSOLE: Pastikan ada properti 'header'

                        if (res.status === true && res.header) {
                            renderPOHeader(res.header);
                            renderPODetail(res.items || []);
                            
                            const printUrl = "<?= site_url('purchasing/purchase-order/print') ?>/" + res.header.po_id;
                            $('#btnCetakPDF').attr('href', printUrl);
                            // Tampilkan modal
                            const modal = document.getElementById('poDetailModal');
                            if (modal) modal.showModal();
                        } else {
                            alert(res.message || 'Gagal: Data header PO kosong atau tidak valid.');
                        }
                    },
                    error: function (xhr) {
                        console.error("XHR Error:", xhr.responseText);
                        alert('Gagal mengambil data dari server. Cek koneksi atau login Anda.');
                    }
                });
            });

            $(document).on('click', '.btn-eksekusi-kirim', function() {
                const id = $(this).data('id');

                if (!confirm('Apakah Anda yakin ingin mengirim PO ini? Status akan berubah menjadi SENT.')) return;

                $.ajax({
                    url: "<?= site_url('purchasing/purchase-order/update-status-sent') ?>/" + id,
                    type: "POST", // Sebaiknya POST untuk aksi perubahan data
                    data: {
                        // Jika menggunakan CSRF
                        "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status) {
                            alert(res.message);
                            poDetailModal.close(); // Tutup modal detail
                            
                            // Refresh datatable agar status di tabel utama berubah
                            if (typeof tablePO !== 'undefined') {
                                tablePO.ajax.reload();
                            } else {
                                location.reload();
                            }
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan sistem saat mencoba mengirim PO.');
                    }
                });

            })
        })

        function kirimPO(id) {
            if (!confirm('Apakah Anda yakin ingin mengirim PO ini? Status akan berubah menjadi SENT.')) return;

            $.ajax({
                url: "<?= site_url('purchasing/purchase-order/update-status-sent') ?>/" + id,
                type: "POST", // Sebaiknya POST untuk aksi perubahan data
                data: {
                    // Jika menggunakan CSRF
                    "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                },
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        alert(res.message);
                        poDetailModal.close(); // Tutup modal detail
                        
                        // Refresh datatable agar status di tabel utama berubah
                        if (typeof tablePO !== 'undefined') {
                            tablePO.ajax.reload();
                        } else {
                            location.reload();
                        }
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan sistem saat mencoba mengirim PO.');
                }
            });
        }
    </script>
<?= $this->endSection() ?>