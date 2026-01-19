<?= $this->extend('layout/main/manager/view_main') ?>
<?= $this->section('content') ?>

<div class="data-laporan font-semibold mt-6">
    <div class="atas flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Daftar Laporan</legend>
            <p class="text-sm text-gray-500">Berikut adalah daftar laporan yang dikirim oleh Admin.</p>
        </fieldset>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 px-5 py-0">
        <table class="table responsive nowrap display" id="laporanTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Laporan</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Periode</th>
                    <th class="text-center">Tanggal Upload</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Data loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Load Data
        $.get('<?= site_url('manager/laporan/list') ?>', function(res) {
            const tbody = $('#tableBody');
            tbody.empty();

            if (res.data.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-gray-400">Belum ada laporan yang tersedia</td></tr>');
                return;
            }

            let no = 1;
            res.data.forEach(item => {
                const date = new Date(item.created_at).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });

                tbody.append(`
                    <tr>
                        <td>${no++}</td>
                        <td class="font-bold">${item.judul}</td>
                        <td class="text-center"><span class="badge badge-outline">${item.jenis_laporan}</span></td>
                        <td class="text-center">${item.periode}</td>
                        <td class="text-center text-gray-500 text-sm">${date}</td>
                        <td class="text-center">
                            <a href="<?= site_url('manager/laporan/download/') ?>${item.id}" target="_blank" class="btn btn-sm bg-[#5160FC] text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
</svg>
                            Download
                            </a>
                        </td>
                    </tr>
                `);
            });

            // Initialize DataTable if needed (optional for simple list)
            $('#laporanTable').DataTable({
                searching: true,
                ordering: true,
                info: true,
                language: {
                    emptyTable: "Tidak ada data",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data"
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>