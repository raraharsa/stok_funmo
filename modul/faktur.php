<?php
// Fungsi terbilang
function terbilang($angka) {
    $angka = abs($angka);
    $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
    
    if ($angka < 12) {
        return $baca[$angka];
    } elseif ($angka < 20) {
        return $baca[$angka - 10] . " belas";
    } elseif ($angka < 100) {
        return $baca[intval($angka / 10)] . " puluh " . $baca[$angka % 10];
    } elseif ($angka < 200) {
        return "seratus " . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        return $baca[intval($angka / 100)] . " ratus " . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        return "seribu " . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        return terbilang(intval($angka / 1000)) . " ribu " . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        return terbilang(intval($angka / 1000000)) . " juta " . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
        return terbilang(intval($angka / 1000000000)) . " milyar " . terbilang($angka % 1000000000);
    }

    return "";
}



include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil nama user yang login
$userId = $_SESSION['UserID']; // Pastikan kamu sudah menyimpan ID user saat login
$sqlUser = "SELECT nama FROM user WHERE UserID = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Ambil data penjualan
$sql = "SELECT p.PenjualanID, p.TanggalPenjualan, p.PaketID, p.TotalHarga,
               pl.NamaPelanggan, pl.Alamat
        FROM penjualan p
        JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
        WHERE p.PenjualanID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$penjualan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$penjualan) {
    die("Data tidak ditemukan.");
}

// Ambil detail barang
$sql = "SELECT pr.NamaProduk, pr.Satuan, pr.Batch, pr.ed, dp.JumlahProduk, pr.Harga,
               dp.Diskon, 
               (dp.JumlahProduk * pr.Harga) AS SubTotal,
               (dp.JumlahProduk * pr.Harga) * (dp.Diskon / 100) AS TotalDiskon,
               ((dp.JumlahProduk * pr.Harga) - ((dp.JumlahProduk * pr.Harga) * (dp.Diskon / 100))) AS HargaSetelahDiskon
        FROM detailpenjualan dp
        JOIN produk pr ON dp.ProdukID = pr.ProdukID
        WHERE dp.PenjualanID = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Perhitungan
// Inisialisasi totalHarga untuk menghitung DPP
$totalHarga = 0;
foreach ($detail as $item) {
    $totalHarga += $item['SubTotal'] - $item['TotalDiskon']; // Harga setelah diskon per produk
}

// DPP adalah total harga setelah diskon
$dpp = $totalHarga; 

// PPN dihitung berdasarkan DPP (11% PPN)
$ppn = $dpp * 0.11; 

// Materai (opsional)
$materai = 0; // Bisa disesuaikan jika ada biaya materai

// Grand Total
$grandTotal = $dpp + $ppn + $materai;



?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Faktur Penjualan</title>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Oswald', sans-serif;

 margin: 40px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #999; padding: 3px; font-size: 13px; }
    th { background-color: #eee; }
    .no-border td { border: none; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .mt-20 { margin-top: 0px;  width: 30px; }
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
  margin-top: 17px;
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
  
  margin-bottom: 5px;
}

.tabel-info-penyerahan th,
.tabel-info-penyerahan td {
  border: 1px solid #000;
  padding: 4px;
  text-align: center;
}
.ter{
  padding-top: 2px;
}
tr.highlight {
  background-color:rgba(16, 212, 238, 1);
}
 @media print {
      table tr td, table tr th {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
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
        
        <p>
          PT SOERYA MEDIKA SENTRAL <br>
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

  <h4 style="text-align:center;">INVOICE</h4>
<table class="tabel-info-penyerahan">
  <thead>
    <tr>
      <th>Salesman</th>
      <th>Tanggal Faktur</th>
      <th>No Faktur</th>
      <th>Tanggal Jatuh Tempo</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Kantor</strong></td>
      <td><strong><?= date('d-m-Y', strtotime($penjualan['TanggalPenjualan'])) ?></strong></td>
      <td><strong><?= str_pad($penjualan['PenjualanID'], 6, '0', STR_PAD_LEFT) ?></td>
      <td><strong><?= date('d-m-Y', strtotime($penjualan['TanggalPenjualan'] . ' +21 days')) ?></td>
    </tr>
  </tbody>
</table>



  
<table>
    <thead>
      <tr class="highlight">
        <th>No</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>No.Batch</th>
        <th>Expired Date</th>
        <th>Jumlah</th>
        <th>Harga <br>Unit</th>
        <th>Diskon (%)</th>
        <th>Jumlah <br>(Netto)</th>
        
      </tr>
    </thead>
    <tbody>
  <?php foreach ($detail as $i => $row): ?>
  <tr>
    <td><?= $i + 1 ?></td>
    <td><?= $row['NamaProduk'] ?></td>
    <td><?= $row['Satuan'] ?></td>
    <td><?= $row['Batch'] ?></td>
    <td><?= $row['ed'] ?></td>
    <td><?= $row['JumlahProduk'] ?></td>
    <td class="text-right"><?= number_format($row['Harga'], 0, ',', '.') ?></td>
    <td class="text-right"><?= $row['Diskon'] ?>%</td>
    
    <td class="text-right"><?= number_format($row['HargaSetelahDiskon'], 0, ',', '.') ?></td>
   
  </tr>
  <?php endforeach; ?>
</tbody>

</table>


  <table class="mt-20" style="width: 300px; float: right; margin-left: 40px;">
    <tr>
      <td>DPP</td>
      <td class="text-right">Rp <?= number_format($dpp, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td>PPN 11%</td>
      <td class="text-right">Rp <?= number_format($ppn, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td>Materai</td>
      <td class="text-right">Rp <?= number_format($materai, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <th>Total Tagihan</th>
      <th class="text-right">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
    </tr>

  </table>
<div style="clear: both; text-align: right; margin-top: 25px; width: 300px; float: right; margin-left: 20px;">
  <i>ID PAKET : <?= !empty($penjualan['PaketID']) ? $penjualan['PaketID'] : '-' ?></i>
</div>

    <tr>
  <td colspan="4"></td>
  <td colspan="3" class="ter">
    <strong><em><?= ucfirst(terbilang($grandTotal)) ?> rupiah</em></strong>
  </td>
</tr>




  <div style="overflow: hidden; margin-top:20px;">

  <!-- kiri -->
  <div style="border: 1px solid black; width: 310px; height: 130px; padding: 10px; float: left;">
    Penerima:<br><br><br><br>
  </div>

  <!-- kanan -->
  <div style="float: right; text-align: center; ">
    Hormat Kami,<br><br><br><br>
    <u><strong>(Surya Efendi)</strong></u>
    <br>
    Direktur
    
    
  </div>

</div>



<div>
  <i>Nb. Pembayaran bisa dilakukan melalui Transfer Bank Jatim Cab.Jember No Rek 0031000629 An Soerya Medika Sentral .PT</i>
</div>


  
  
  
  <script>
    window.print();
  </script>

</body>

</html>
