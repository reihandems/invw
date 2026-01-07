<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 30px; }
        .badge { font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <div class="header">
        <h2><?= $title ?></h2>
        <p>Dicetak pada: <?= $date ?></p>
        <p>Gudang: <?= session('user_gudang') ?? 'Semua Gudang' ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Gudang</th>
                <th>Rak</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($stocks as $s): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $s['sku'] ?></td>
                <td><?= $s['nama_barang'] ?></td>
                <td class="text-center"><?= $s['nama_gudang'] ?></td>
                <td class="text-center"><?= $s['kode_rak'] ?></td>
                <td class="text-center"><strong><?= $s['jumlah_stok'] ?></strong> <?= $s['nama_satuan'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>