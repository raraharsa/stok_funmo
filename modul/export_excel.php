<?php
include '../lib/koneksi.php';

// Header supaya browser tahu ini file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

date_default_timezone_set('Asia/Jakarta');

// Ambil data penjualan seperti di laporan
$sql = "SELECT DATE(p.TanggalPenjualan) as TanggalPenjualan, 
               COUNT(p.PenjualanID) AS JumlahTransaksi, 
               SUM(p.TotalHarga) AS TotalPenjualan
        FROM penjualan p
        GROUP BY DATE(p.TanggalPenjualan)
        ORDER BY DATE(p.TanggalPenjualan) ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buat tabel HTML untuk Excel
echo "<table border='1'>";
echo "<tr>
        <th>Tanggal</th>
        <th>Jumlah Transaksi</th>
        <th>Total Penjualan</th>
      </tr>";

if (!empty($laporan)) {
    foreach ($laporan as $data) {
        echo "<tr>
                <td>{$data['TanggalPenjualan']}</td>
                <td>{$data['JumlahTransaksi']}</td>
                <td>{$data['TotalPenjualan']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
}

echo "</table>";
?>
