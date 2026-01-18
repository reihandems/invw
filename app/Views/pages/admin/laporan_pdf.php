<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #5160FC;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #333;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .header .periode {
            color: #666;
            font-size: 12px;
            margin-top: 8px;
        }

        .info-box {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 3px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #5160FC;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #4150DC;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .total-row {
            background-color: #e8ebff !important;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 9px;
            color: #999;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-warning {
            background: #FFC107;
            color: #000;
        }

        .badge-info {
            background: #2196F3;
            color: #fff;
        }

        .badge-success {
            background: #4CAF50;
            color: #fff;
        }

        .badge-error {
            background: #F44336;
            color: #fff;
        }

        .badge-ghost {
            background: #9E9E9E;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1><?= esc($title) ?></h1>
        <div class="periode">Periode: <?= esc($periode) ?></div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <p><strong>Tanggal Cetak:</strong> <?= date('d/m/Y H:i') ?> WIB</p>
        <p><strong>Dicetak oleh:</strong> <?= session('user_nama') ?></p>
    </div>

    <!-- Table Content -->
    <?php if ($jenis === 'barang'): ?>
        <!-- LAPORAN BARANG MASUK/KELUAR -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Barang</th>
                    <th width="15%" class="text-center">Total Masuk</th>
                    <th width="15%" class="text-center">Total Keluar</th>
                    <th width="15%" class="text-center">Selisih</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalMasuk = 0;
                $totalKeluar = 0;
                foreach ($rows as $r):
                    $selisih = $r['total_masuk'] - $r['total_keluar'];
                    $totalMasuk += $r['total_masuk'];
                    $totalKeluar += $r['total_keluar'];
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($r['nama_barang']) ?></td>
                        <td class="text-center"><?= number_format($r['total_masuk'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= number_format($r['total_keluar'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= number_format($selisih, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                            Tidak ada data untuk periode yang dipilih
                        </td>
                    </tr>
                <?php else: ?>
                    <tr class="total-row">
                        <td colspan="2" class="text-end"><strong>TOTAL</strong></td>
                        <td class="text-center"><strong><?= number_format($totalMasuk, 0, ',', '.') ?></strong></td>
                        <td class="text-center"><strong><?= number_format($totalKeluar, 0, ',', '.') ?></strong></td>
                        <td class="text-center"><strong><?= number_format($totalMasuk - $totalKeluar, 0, ',', '.') ?></strong></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($jenis === 'stok'): ?>
        <!-- LAPORAN STOK OPNAME -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Barang</th>
                    <th width="15%" class="text-center">Stok Sistem</th>
                    <th width="15%" class="text-center">Stok Fisik</th>
                    <th width="15%" class="text-center">Selisih</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($rows as $r):
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($r['nama_barang']) ?></td>
                        <td class="text-center"><?= number_format($r['stok_sistem'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= number_format($r['stok_fisik'], 0, ',', '.') ?></td>
                        <td class="text-center" style="<?= $r['selisih'] < 0 ? 'color: red;' : '' ?>">
                            <?= number_format($r['selisih'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                            Tidak ada data untuk periode yang dipilih
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($jenis === 'purchasing'): ?>
        <!-- LAPORAN PURCHASING -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Supplier</th>
                    <th width="15%" class="text-center">Tanggal Order</th>
                    <th width="12%" class="text-center">Status</th>
                    <th width="18%" class="text-end">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $grandTotal = 0;
                foreach ($rows as $r):
                    $grandTotal += $r['total_harga'];

                    // Badge class
                    $badgeClass = 'badge-ghost';
                    if ($r['status'] === 'draft') $badgeClass = 'badge-warning';
                    if ($r['status'] === 'sent') $badgeClass = 'badge-info';
                    if ($r['status'] === 'received') $badgeClass = 'badge-success';
                    if ($r['status'] === 'cancelled') $badgeClass = 'badge-error';
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($r['nama_supplier']) ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($r['tanggal_order'])) ?></td>
                        <td class="text-center">
                            <span class="badge <?= $badgeClass ?>"><?= strtoupper($r['status']) ?></span>
                        </td>
                        <td class="text-end">Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px; color: #999;">
                            Tidak ada data untuk periode yang dipilih
                        </td>
                    </tr>
                <?php else: ?>
                    <tr class="total-row">
                        <td colspan="4" class="text-end"><strong>GRAND TOTAL</strong></td>
                        <td class="text-end"><strong>Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem INVW</p>
        <p>Dicetak pada: <?= date('d F Y, H:i:s') ?> WIB</p>
    </div>
</body>

</html>