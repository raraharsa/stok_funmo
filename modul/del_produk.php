
<?php 
include '../lib/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus transaksi terkait di detailpenjualan
    $sql = "DELETE FROM detailpenjualan WHERE ProdukID = :ProdukID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ProdukID', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Hapus produk setelah semua transaksi terkait dihapus
    $sql = "DELETE FROM produk WHERE ProdukID = :ProdukID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ProdukID', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location.href='admin_dashboard.php?page=data_produk';</script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}


?>