<?= $this->extend('layout/main/manager/view_main') ?>

<?= $this->section('content') ?>
<!-- Main Content -->
<div class="grid grid-cols-12 gap-4">
    <!-- Stats Section -->
    <div class="col-span-6">
        <div class="stats bg-white shadow w-full">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="stat-title">Total Barang</div>
                <div class="stat-value text-primary"><?= number_format($stats['total_products']) ?></div>
                <div class="stat-desc">Items in catalog</div>
            </div>
        </div>
    </div>
    <div class="col-span-6">
        <div class="stats bg-white shadow w-full">
            <div class="stat">
                <div class="stat-figure text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="stat-title">Total Supplier</div>
                <div class="stat-value text-secondary"><?= number_format($stats['total_suppliers']) ?></div>
                <div class="stat-desc">Partner Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-span-6">
        <div class="stats bg-white shadow w-full">
            <div class="stat">
                <div class="stat-figure text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="stat-title">PO Aktif</div>
                <div class="stat-value text-accent"><?= number_format($stats['active_pos']) ?></div>
                <div class="stat-desc">In progress</div>
            </div>
        </div>
    </div>
    <div class="col-span-6">
        <div class="stats bg-white shadow w-full">
            <div class="stat">
                <div class="stat-figure text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-title">PO Hari Ini</div>
                <div class="stat-value text-info"><?= number_format($stats['today_pos']) ?></div>
                <div class="stat-desc"><?= date('d M Y') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="col-span-12 mt-5">
    <div class="card bg-base-100 shadow-md">
        <div class="card-body">
            <h2 class="card-title">Transaction Activity (6 Bulan Terakhir)</h2>
            <div class="w-full h-96">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Main Content end -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const chartData = <?= json_encode($chartData) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                    text: 'Incoming vs Outgoing'
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>