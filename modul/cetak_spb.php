<?php
include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = isset($_GET['id']) ? $_GET['id'] : 0;


// Ambil data transaksi
$sql = "SELECT p.TanggalPenjualan, p.PenjualanID, pl.NamaPelanggan, pl.Alamat
        FROM penjualan p
        JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
        WHERE p.PenjualanID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$penjualan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$penjualan) {
    die("Transaksi tidak ditemukan.");
}

// Ambil detail barang
$sql = "SELECT pr.NamaProduk, dp.JumlahProduk, pr.Satuan, pr.ed, pr.Batch
        FROM detailpenjualan dp
        JOIN produk pr ON dp.ProdukID = pr.ProdukID
        WHERE dp.PenjualanID = ?";


$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$detail = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Penyerahan Barang</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      color: #333;
    }
    h4 {
      text-align: center;
      margin-bottom: 5px;
      margin-top: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #999;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #eee;
    }
    .info {
      margin-bottom: 20px;
    }
   .signature {
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
}

.signature div {
  text-align: center;
  width: 30%;
}

 .header-container {
  width: 100%;
  padding: 10px 0;
}

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.logo-col {
  display: flex;
  align-items: flex-start;
}

.logo-col img {
  max-width: 100px;
  margin-right: 10px;
}

.company-info {
  display: flex;
  flex-direction: column;
  justify-content: center;
  line-height: 1.3;
  font-size: 13px;
}

.company-name {
  font-size: 15px;
  font-weight: bold;
}

.customer-info {
  text-align: right;
  font-size: 13px;
  line-height: 1.5;
}

.tabel-info-penyerahan {
  border-collapse: collapse;
  margin-top: 10px;
  width: 50%;
  font-size: 10px;
  height: 20px;
  padding: 0;
  float: right; /* Pindah ke kanan */
  border: 1px solid #000;
}

.tabel-info-penyerahan th,
.tabel-info-penyerahan td {
  border: 1px solid #000;
  padding: 4px;
  text-align: left;
}

  </style>
</head>
<body>





<div class="header-container">
  <div class="header-row">
    <!-- Logo dan Info Perusahaan -->
    <div class="logo-col">
      <img src="../asset/img/SMS logo.png" alt="Logo">
      <div class="company-info">
        <div class="company-name">PT SOERYA MEDIKA SENTRAL</div>
        <p>
          Jl. Rasamala Blok B No.06 Baratan Patrang Jember<br>
          Telp: 082343438180 – 081252970508<br>
          Email: soeryamedikasentral032021@gmail.com
        </p>
      </div>
    </div>

    <!-- Info Pelanggan -->
    <div class="customer-info">
      Kepada Yth:<br>
      <strong><?= $penjualan['NamaPelanggan'] ?></strong><br>
      <?= $penjualan['Alamat'] ?><br>
    </div>
  </div>
</div>

<h4>SURAT PENYERAHAN BARANG</h4>

<?php
$nomorSurat = "25" . str_pad($penjualan['PenjualanID'], 4, "0", STR_PAD_LEFT); 
?>

<table class="tabel-info-penyerahan">
  <thead>
    <tr>
      <th>No. Surat Penyerahan Barang</th>
      <th>Tanggal Penyerahan</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong><?= $nomorSurat ?></strong></td>
      <td><strong><?= date("d-M-y", strtotime($penjualan['TanggalPenjualan'])) ?></strong></td>
    </tr>
  </tbody>
</table>







<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Produk</th>
      <th>Jumlah</th>
      <th>Satuan</th>
      <th>ED</th>
      <th>Batch</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($detail as $i => $item): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= $item['NamaProduk'] ?></td>
      <td><?= $item['JumlahProduk'] ?></td>
      <td><?= $item['Satuan'] ?></td>
      <td><?= $item['ed'] ?></td>
      <td><?= $item['Batch'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<div class="signature">
  <div></div> <!-- Elemen kosong di kiri -->
  <div>
    Penerima<br><br><br><br>
    (____________________)
  </div>
</div>

<script>
  window.print();
</script>

</body>
</html>
