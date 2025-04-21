<?php
include '../lib/koneksi.php';

// Atur header supaya browser download sebagai file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=transaksi_penjualan.xls");

// Query data transaksi
$sql = "SELECT p.PenjualanID, p.TanggalPenjualan, pl.NamaPelanggan, p.TotalHarga 
        FROM penjualan p
        JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
        ORDER BY p.TanggalPenjualan DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buat tampilan tabel Excel
echo "<table border='1'>";
echo "<tr>
        <th>ID Transaksi</th>
        <th>Tanggal</th>
        <th>Nama Pelanggan</th>
        <th>Total Harga</th>
      </tr>";

foreach ($data as $row) {
    echo "<tr>";
    echo "<td>{$row['PenjualanID']}</td>";
    echo "<td>{$row['TanggalPenjualan']}</td>";
    echo "<td>{$row['NamaPelanggan']}</td>";
    echo "<td>" . (int)$row['TotalHarga'] . "</td>"; // angka murni, biar bisa di-SUM
    echo "</tr>";
}

echo "</table>";
?>
