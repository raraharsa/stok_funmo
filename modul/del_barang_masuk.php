<?php
include '../lib/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = (int) $_GET['id'];

// ambil data sebelum hapus
$sql = "
    SELECT 
        dbm.ProdukID,
        dbm.jumlah,
        dbm.id_masuk
    FROM detai_barang_masuk dbm
    WHERE dbm.id_detail_masuk = :id
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data barang masuk tidak ditemukan");
}

// kembalikan stok
$sqlStok = "
    UPDATE produk 
    SET Stok = Stok - :jumlah 
    WHERE ProdukID = :produk
";
$stmt = $conn->prepare($sqlStok);
$stmt->bindParam(':jumlah', $data['jumlah'], PDO::PARAM_INT);
$stmt->bindParam(':produk', $data['ProdukID'], PDO::PARAM_INT);
$stmt->execute();

// hapus detail
$sqlDelDetail = "DELETE FROM detai_barang_masuk WHERE id_detail_masuk = :id";
$stmt = $conn->prepare($sqlDelDetail);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// hapus header barang masuk
$sqlDelMasuk = "DELETE FROM barang_masuk WHERE id_masuk = :id_masuk";
$stmt = $conn->prepare($sqlDelMasuk);
$stmt->bindParam(':id_masuk', $data['id_masuk'], PDO::PARAM_INT);
$stmt->execute();

echo "<script>
    alert('Data barang masuk berhasil dihapus');
    window.location.href='admin_dashboard.php?page=rea_barangmasuk';
</script>";
