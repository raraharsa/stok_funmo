<?php 
include '../lib/koneksi.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Hapus detail transaksi terlebih dahulu
    $sql = "DELETE FROM detailpenjualan WHERE PenjualanID IN (SELECT PenjualanID FROM penjualan WHERE PelangganID = :PelangganID)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':PelangganID', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Hapus transaksi terkait pelanggan
    $sql = "DELETE FROM penjualan WHERE PelangganID = :PelangganID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':PelangganID', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Hapus pelanggan
    $sql = "DELETE FROM pelanggan WHERE PelangganID = :PelangganID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':PelangganID', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Data Pelanggan berhasil di hapus !'); window.location.href='admin_dashboard.php?page=data_pelanggan';</script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}



?>