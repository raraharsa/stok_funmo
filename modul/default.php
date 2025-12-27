<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include '../lib/koneksi.php';

if (!isset($conn)) {
    die("Koneksi database tidak tersedia");
}

/* ================= SUMMARY ================= */

/* TOTAL PRODUK */
$totalProduk = $conn->query("SELECT COUNT(*) FROM produk")->fetchColumn();

/* TOTAL TRANSAKSI */
$totalTransaksi = $conn->query("SELECT COUNT(*) FROM penjualan")->fetchColumn();

/* TOTAL PENDAPATAN */
$totalPendapatan = $conn->query("SELECT SUM(TotalHarga) FROM penjualan")->fetchColumn();
if ($totalPendapatan == null) $totalPendapatan = 0;

/* PENDAPATAN HARI INI */
$today = date('Y-m-d');
$pendapatanHariIni = $conn->query("
    SELECT SUM(TotalHarga) FROM penjualan 
    WHERE DATE(TanggalPenjualan) = '$today'
")->fetchColumn();
if ($pendapatanHariIni == null) $pendapatanHariIni = 0;

/* PENDAPATAN BULAN INI */
$bulan = date('Y-m');
$pendapatanBulan = $conn->query("
    SELECT SUM(TotalHarga) FROM penjualan 
    WHERE DATE_FORMAT(TanggalPenjualan,'%Y-%m') = '$bulan'
")->fetchColumn();
if ($pendapatanBulan == null) $pendapatanBulan = 0;

/* RATA-RATA TRANSAKSI */
$rataTransaksi = 0;
if ($totalTransaksi > 0) {
    $rataTransaksi = $totalPendapatan / $totalTransaksi;
}

/* ================= GRAFIK ================= */
$grafik = $conn->query("
    SELECT DATE(TanggalPenjualan) AS tgl, SUM(TotalHarga) total
    FROM penjualan
    GROUP BY DATE(TanggalPenjualan)
    ORDER BY DATE(TanggalPenjualan) ASC
")->fetchAll(PDO::FETCH_ASSOC);

$tanggal = array();
$total   = array();

foreach ($grafik as $g) {
    $tanggal[] = date('d M', strtotime($g['tgl']));
    $total[]   = (int)$g['total'];
}

/* ================= TRANSAKSI TERAKHIR ================= */
$dataLast = $conn->query("
    SELECT PenjualanID, TanggalPenjualan, TotalHarga
    FROM penjualan
    ORDER BY TanggalPenjualan DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
}

.dashboard{
    padding:10px;
}

.dashboard h2{
    font-size:22px;
    font-weight:600;
    margin-bottom:25px;
    color:#0f172a;
}

/* ===== CARDS ===== */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    display:flex;
    gap:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}

.card span{
    font-size:38px;
    color:#00AEEF;
}

.card small{
    font-size:12px;
    color:#64748b;
}

.card h3{
    font-size:22px;
    margin-top:4px;
    color:#0f172a;
}

/* ===== BOX ===== */
.box{
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    margin-bottom:30px;
}

.box h4{
    margin-bottom:15px;
    font-size:16px;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

th{
    background:#e6f6fd;
    padding:12px;
    text-align:left;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:hover{background:#f4fbff}
</style>
</head>

<body>

<div class="dashboard">

<h2>Dashboard Admin – PT Soerya Medika Sentral</h2>

<div class="cards">

    <div class="card">
        <span class="material-symbols-outlined">inventory</span>
        <div>
            <small>Total Produk</small>
            <h3><?= $totalProduk ?></h3>
        </div>
    </div>

    <div class="card">
        <span class="material-symbols-outlined">receipt_long</span>
        <div>
            <small>Total Transaksi</small>
            <h3><?= $totalTransaksi ?></h3>
        </div>
    </div>

    <div class="card">
        <span class="material-symbols-outlined">payments</span>
        <div>
            <small>Total Pendapatan</small>
            <h3>Rp <?= number_format($totalPendapatan,0,',','.') ?></h3>
        </div>
    </div>

    <div class="card">
        <span class="material-symbols-outlined">today</span>
        <div>
            <small>Pendapatan Hari Ini</small>
            <h3>Rp <?= number_format($pendapatanHariIni,0,',','.') ?></h3>
        </div>
    </div>

    <div class="card">
        <span class="material-symbols-outlined">calendar_month</span>
        <div>
            <small>Pendapatan Bulan Ini</small>
            <h3>Rp <?= number_format($pendapatanBulan,0,',','.') ?></h3>
        </div>
    </div>

    <div class="card">
        <span class="material-symbols-outlined">analytics</span>
        <div>
            <small>Rata-rata / Transaksi</small>
            <h3>Rp <?= number_format($rataTransaksi,0,',','.') ?></h3>
        </div>
    </div>

</div>

<div class="box">
    <h4>Grafik Penjualan</h4>
    <canvas id="chartPenjualan" height="90"></canvas>
</div>

<div class="box">
    <h4>Transaksi Terakhir</h4>
    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Total</th>
        </tr>
        <?php foreach($dataLast as $d): ?>
        <tr>
            <td><?= $d['PenjualanID'] ?></td>
            <td><?= date('d-m-Y', strtotime($d['TanggalPenjualan'])) ?></td>
            <td>Rp <?= number_format($d['TotalHarga'],0,',','.') ?></td>
        </tr>
        <?php endforeach ?>
    </table>
</div>

</div>

<script>
new Chart(document.getElementById('chartPenjualan'),{
    type:'line',
    data:{
        labels: <?= json_encode($tanggal) ?>,
        datasets:[{
            data: <?= json_encode($total) ?>,
            borderColor:'#00AEEF',
            backgroundColor:'rgba(0,174,239,.15)',
            fill:true,
            tension:.4,
            pointRadius:4
        }]
    },
    options:{
        plugins:{legend:{display:false}},
        scales:{
            y:{
                ticks:{
                    callback:function(v){
                        return 'Rp ' + v.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>
