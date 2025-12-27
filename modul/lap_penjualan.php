<?php
include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* =============================
   FILTER TANGGAL
============================= */
$tgl_awal  = '';
$tgl_akhir = '';

$where = '';
$params = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tgl_awal  = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];

    if ($tgl_awal != '' && $tgl_akhir != '') {
        $where = " WHERE DATE(TanggalPenjualan) BETWEEN :awal AND :akhir ";
        $params[':awal']  = $tgl_awal;
        $params[':akhir'] = $tgl_akhir;
    }
}

/* =============================
   SUMMARY
============================= */
$sqlSummary = "
SELECT 
    COUNT(PenjualanID) AS total_transaksi,
    SUM(TotalHarga) AS total_penjualan,
    AVG(TotalHarga) AS rata_transaksi
FROM penjualan
" . $where;

$stmt = $conn->prepare($sqlSummary);
$stmt->execute($params);
$summary = $stmt->fetch(PDO::FETCH_ASSOC);

/* =============================
   LAPORAN HARIAN
============================= */
$sql = "
SELECT 
    DATE(TanggalPenjualan) AS tanggal,
    COUNT(PenjualanID) AS jumlah_transaksi,
    SUM(TotalHarga) AS total_penjualan
FROM penjualan
" . $where . "
GROUP BY DATE(TanggalPenjualan)
ORDER BY DATE(TanggalPenjualan) ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =============================
   PRODUK TERLARIS
============================= */
$sqlProduk = "
SELECT 
    pr.NamaProduk,
    SUM(dp.JumlahProduk) AS total_qty
FROM detailpenjualan dp
JOIN produk pr ON dp.ProdukID = pr.ProdukID
JOIN penjualan pj ON dp.PenjualanID = pj.PenjualanID
" . ($where ? str_replace('TanggalPenjualan','pj.TanggalPenjualan',$where) : '') . "
GROUP BY pr.ProdukID
ORDER BY total_qty DESC
LIMIT 5";

$stmt = $conn->prepare($sqlProduk);
$stmt->execute($params);
$produkTerlaris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Penjualan</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:Poppins,sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
}

.wrapper{
    max-width:1100px;
    margin:auto;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    margin-bottom:24px;
}

h2{
    margin:0 0 16px;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}

.summary{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
}

.box{
    background:#f0faff;
    border-radius:12px;
    padding:16px;
}

.box h4{
    margin:0;
    font-size:12px;
    color:#475569;
}

.box p{
    margin:6px 0 0;
    font-size:18px;
    font-weight:600;
    color:#0f172a;
}

form{
    display:flex;
    gap:10px;
    margin-bottom:16px;
}

input,button{
    padding:8px 12px;
    border-radius:8px;
    border:1px solid #dbe2ea;
}

button{
    background:#00AEEF;
    color:#fff;
    border:none;
    font-weight:600;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#00AEEF;
    color:#fff;
    padding:10px;
    text-align:left;
}

td{
    padding:9px 10px;
    border-bottom:1px solid #edf2f7;
}

.note{
    font-size:12px;
    color:#64748b;
    margin-top:12px;
}
</style>
</head>

<body>
<div class="wrapper">

<div class="card">
<h2>Ringkasan Penjualan</h2>

<div class="summary">
<div class="box">
<h4>Total Transaksi</h4>
<p><?php echo number_format($summary['total_transaksi']); ?></p>
</div>
<div class="box">
<h4>Total Penjualan</h4>
<p>Rp<?php echo number_format($summary['total_penjualan'],0,',','.'); ?></p>
</div>
<div class="box">
<h4>Rata-rata Transaksi</h4>
<p>Rp<?php echo number_format($summary['rata_transaksi'],0,',','.'); ?></p>
</div>
</div>
</div>

<div class="card">
<h2>Filter Tanggal</h2>
<form method="POST">
<input type="date" name="tgl_awal" value="<?php echo $tgl_awal; ?>">
<input type="date" name="tgl_akhir" value="<?php echo $tgl_akhir; ?>">
<button>Filter</button>
</form>
</div>

<div class="card">
<h2>Laporan Harian</h2>
<table>
<tr>
<th>Tanggal</th>
<th>Jumlah Transaksi</th>
<th>Total Penjualan</th>
</tr>
<?php foreach($laporan as $r){ ?>
<tr>
<td><?php echo $r['tanggal']; ?></td>
<td><?php echo $r['jumlah_transaksi']; ?></td>
<td>Rp<?php echo number_format($r['total_penjualan'],0,',','.'); ?></td>
</tr>
<?php } ?>
</table>
</div>

<div class="card">
<h2>Produk Terlaris</h2>
<table>
<tr>
<th>Produk</th>
<th>Total Terjual</th>
</tr>
<?php foreach($produkTerlaris as $p){ ?>
<tr>
<td><?php echo $p['NamaProduk']; ?></td>
<td><?php echo $p['total_qty']; ?></td>
</tr>
<?php } ?>
</table>

<div class="note">
Catatan: Laporan ini digunakan untuk evaluasi penjualan, pemantauan performa produk, 
serta pengambilan keputusan stok dan strategi penjualan.
</div>
</div>

</div>
</body>
</html>
