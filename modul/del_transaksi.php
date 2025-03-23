<?php 
include '../lib/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Ambil detail transaksi untuk mengembalikan stok produk
        $sql = "SELECT ProdukID, JumlahProduk FROM detailpenjualan WHERE PenjualanID = :PenjualanID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':PenjualanID', $id, PDO::PARAM_INT);
        $stmt->execute();
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kembalikan stok produk
        foreach ($details as $detail) {
            $sql = "UPDATE produk SET Stok = Stok + :jumlah WHERE ProdukID = :produkID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':jumlah', $detail['JumlahProduk'], PDO::PARAM_INT);
            $stmt->bindParam(':produkID', $detail['ProdukID'], PDO::PARAM_INT);
            $stmt->execute();
        }

        // Hapus detail transaksi dulu
        $sql = "DELETE FROM detailpenjualan WHERE PenjualanID = :PenjualanID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':PenjualanID', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Hapus transaksi utama
        $sql = "DELETE FROM penjualan WHERE PenjualanID = :PenjualanID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':PenjualanID', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Transaksi berhasil dihapus!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
