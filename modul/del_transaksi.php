<?php
include '../lib/koneksi.php';

if (isset($_GET['PenjualanID'])) {
    $penjualanID = $_GET['PenjualanID'];
    
    try {
        $conn->beginTransaction();
        
        // Hapus detail transaksi terlebih dahulu
        $sqlDetail = "DELETE FROM detailpenjualan WHERE PenjualanID = :penjualanID";
        $stmtDetail = $conn->prepare($sqlDetail);
        $stmtDetail->bindParam(':penjualanID', $penjualanID, PDO::PARAM_INT);
        $stmtDetail->execute();
        
        // Hapus transaksi utama
        $sql = "DELETE FROM penjualan WHERE PenjualanID = :penjualanID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':penjualanID', $penjualanID, PDO::PARAM_INT);
        $stmt->execute();
        
        $conn->commit();
        echo "<script>alert('Transaksi berhasil dihapus.'); window.location.href='admin_dashboard.php?page=data_transaksi';</script>";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "<script>alert('Gagal menghapus transaksi: " . $e->getMessage() . "'); window.location.href='admin_dashboard.php?page=data_transaksi';</script>";
    }
} else {
    echo "<script>alert('ID transaksi tidak ditemukan.'); window.location.href='admin_dashboard.php?page=data_transaksi';</script>";
}
?>
