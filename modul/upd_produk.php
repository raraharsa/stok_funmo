<?php
// Sertakan file koneksi database
include '../lib/koneksi.php';

// Periksa apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data pelanggan berdasarkan ID
    $sql = "SELECT * FROM produk WHERE ProdukID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produk = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan, redirect ke halaman utama
    if (!$produk) {
        echo "<script>alert('Produk tidak ditemukan!')";
        exit();
    }
}

// Jika form dikirim, update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['NamaProduk'];
    $alamat = $_POST['Harga'];
    $telepon = $_POST['Stok'];

    $sql = "UPDATE produk SET NamaProduk = :nama, Harga = :harga, Stok = :stok WHERE ProdukID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':harga', $alamat);
    $stmt->bindParam(':stok', $telepon);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!')";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    
</head>
<body>

<div class="container">
    <h2>Edit Produk</h2>
    <form method="POST">
        <label>Nama Produk:</label>
        <input type="text" name="NamaProduk" value="<?php echo htmlspecialchars($produk['NamaProduk']); ?>" required>

        <label>Harga Produk:</label>
        <input type="text" name="Harga" value="<?php echo htmlspecialchars($produk['Harga']); ?>" required>

        <label>Stok Produk:</label>
        <input type="text" name="Stok" value="<?php echo htmlspecialchars($produk['Stok']); ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
