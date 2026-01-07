<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info { margin-top: 15px; width: 100%; }
        .info td { vertical-align: top; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th, table.items td { border: 1px solid #000; padding: 5px; }
        .footer { margin-top: 30px; width: 100%; }
        .footer td { text-align: center; width: 33%; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;"><?= $header['nama_gudang'] ?></h2>
        <p style="margin:0;"><?= $header['alamat'] ?></p>
        <h3 style="margin-top:10px; border:1px solid #000; display:inline-block; padding:5px;">SURAT JALAN</h3>
    </div>

    <table class="info">
        <tr>
            <td width="15%">No. Transaksi</td><td>: BK-<?= str_pad($header['keluar_id'], 5, '0', STR_PAD_LEFT) ?></td>
            <td width="15%">Tujuan</td><td>: <?= $header['keterangan'] ?></td>
        </tr>
        <tr>
            <td>Tanggal</td><td>: <?= date('d F Y', strtotime($header['tanggal_keluar'])) ?></td>
            <td>Admin</td><td>: <?= $header['nama_staff'] ?></td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">SKU</th>
                <th>Nama Barang</th>
                <th width="15%">Asal Rak</th>
                <th width="10%">Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($detail as $d): ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $d['sku'] ?></td>
                <td><?= $d['nama_barang'] ?></td>
                <td align="center"><?= $d['kode_rak'] ?></td>
                <td align="center"><?= $d['jumlah'] ?> <?= $d['nama_satuan'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>Penerima,</td>
            <td>Sopir/Kurir,</td>
            <td>Hormat Kami,</td>
        </tr>
        <tr class="signature-space">
            <td></td><td></td><td></td>
        </tr>
        <tr>
            <td>( .................... )</td>
            <td>( .................... )</td>
            <td>( <?= $header['nama_staff'] ?> )</td>
        </tr>
    </table>
</body>
</html>