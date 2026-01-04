<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { bg-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; padding: 10px; }
        .footer { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PURCHASE ORDER</h2>
        <p>Nomor: <?= $header['po_number'] ?></p>
    </div>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%;">
                <strong>Supplier:</strong><br>
                <?= $header['nama_supplier'] ?><br>
                <?= $header['alamat_supplier'] ?><br>
                <?= $header['kontak'] ?>
            </td>
            <td style="width: 50%;">
                <strong>Kirim Ke:</strong><br>
                <?= $header['nama_gudang'] ?><br>
                <?= $header['alamat_gudang'] ?>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?= $item['sku'] ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['qty'] ?> <?= $item['nama_satuan'] ?></td>
                <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($item['qty'] * $item['price'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        Grand Total: Rp <?= number_format($header['total'], 0, ',', '.') ?>
    </div>

    <div class="footer">
        <p>Catatan: <?= $header['notes'] ?? '-' ?></p>
        <p>Estimasi Kedatangan: <?= $header['expected_delivery_date'] ?></p>
    </div>
</body>
</html>