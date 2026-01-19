<?= $this->extend('layout/main/gudang/view_main') ?>

<?= $this->section('content') ?>
<!-- Main Content -->
<div class="grid grid-cols-12 gap-4">
    <!-- Stats Section -->
    <div class="col-span-6">
        <div class="stats stats-vertical lg:stats-horizontal shadow w-full bg-white">
            <div class="stat">
                <div class="stat-title">Total Varian Barang</div>
                <div class="stat-value"><?= number_format($stats['total_items']) ?></div>
                <div class="stat-desc">Di Lokasi Gudang</div>
            </div>
        </div>
    </div>
    <div class="col-span-6">
        <div class="stats stats-vertical lg:stats-horizontal shadow w-full bg-white">
            <div class="stat">
                <div class="stat-title">Total Stok Fisik</div>
                <div class="stat-value"><?= number_format($stats['total_stock']) ?></div>
                <div class="stat-desc">Unit Barang</div>
            </div>
        </div>
    </div>
    <div class="col-span-6">
        <div class="stats stats-vertical lg:stats-horizontal shadow w-full bg-white">
            <div class="stat">
                <div class="stat-title">Masuk Bulan Ini</div>
                <div class="stat-value text-success"><?= number_format($stats['masuk_bulan_ini']) ?></div>
                <div class="stat-desc">Transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-span-6">
        <div class="stats stats-vertical lg:stats-horizontal shadow w-full bg-white">
            <div class="stat">
                <div class="stat-title">Keluar Bulan Ini</div>
                <div class="stat-value text-error"><?= number_format($stats['keluar_bulan_ini']) ?></div>
                <div class="stat-desc">Transaksi</div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="col-span-12">
        <div class="card bg-base-100 shadow-md">
            <div class="card-body">
                <h2 class="card-title">Statistik Transaksi Bulanan (<?= date('Y') ?>)</h2>
                <canvas id="transaksiChart"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Main Content end -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('transaksiChart');
    const chartData = {
        masuk: <?= json_encode($chart['masuk']) ?>,
        keluar: <?= json_encode($chart['keluar']) ?>
    };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                    label: 'Barang Masuk',
                    data: chartData.masuk,
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                },
                {
                    label: 'Barang Keluar',
                    data: chartData.keluar,
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>