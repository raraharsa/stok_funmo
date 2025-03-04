<?php
// Sertakan file koneksi database
include '../lib/koneksi.php';

// Periksa apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data pelanggan berdasarkan ID
    $sql = "SELECT * FROM pelanggan WHERE PelangganID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan, redirect ke halaman utama
    if (!$pelanggan) {
        echo "<script>alert('Pelanggan tidak ditemukan!'); window.location.href='read_pelanggan.php';</script>";
        exit();
    }
}

// Jika form dikirim, update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['NamaPelanggan'];
    $alamat = $_POST['Alamat'];
    $telepon = $_POST['NomorTelepon'];

    $sql = "UPDATE pelanggan SET NamaPelanggan = :nama, Alamat = :alamat, NomorTelepon = :telepon WHERE PelangganID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':telepon', $telepon);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='rea_pelanggan.php';</script>";
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
    <title>Edit Pelanggan</title>
    
</head>
<body>

<div class="container">
    <h2>Edit Pelanggan</h2>
    <form method="POST">
        <label>Nama Pelanggan:</label>
        <input type="text" name="NamaPelanggan" value="<?php echo htmlspecialchars($pelanggan['NamaPelanggan']); ?>" required>

        <label>Alamat:</label>
        <input type="text" name="Alamat" value="<?php echo htmlspecialchars($pelanggan['Alamat']); ?>" required>

        <label>Nomor Telepon:</label>
        <input type="text" name="NomorTelepon" value="<?php echo htmlspecialchars($pelanggan['NomorTelepon']); ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
