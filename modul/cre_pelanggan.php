<?php
include '../lib/koneksi.php';

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NamaPelanggan = $_POST['NamaPelanggan'];
    $Alamat = $_POST['Alamat'];
    $NomorTelepon = $_POST['NomorTelepon'];

    // Validasi input
    if (empty($NamaPelanggan) || empty($Alamat) || empty($NomorTelepon)) {
        echo "<p class='text-danger'>Semua field harus diisi!</p>";
    } else {
        try {
            // Query untuk memasukkan data ke tabel pelanggan
            $sql = "INSERT INTO pelanggan (NamaPelanggan, Alamat, NomorTelepon) VALUES (:NamaPelanggan, :Alamat, :NomorTelepon)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':NamaPelanggan' => $NamaPelanggan,
                ':Alamat' => $Alamat,
                ':NomorTelepon' => $NomorTelepon
            ]);

            echo "<p class='text-success'>Data pelanggan berhasil ditambahkan!</p>";
            header("Location: admin_dashboard.php"); // Redirect setelah simpan
           exit; // Stop eksekusi biar nggak jalan terus
  
        } catch (PDOException $e) {
            echo "<p class='text-danger'>Terjadi kesalahan: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pelanggan</title>
</head>
<body>
    <div class="container">
        <h2>Input Pelanggan</h2>
        <form method="POST" action="cre_pelanggan.php" id="formPelanggan">
            <div>
                <label for="NamaPelanggan">Nama Pelanggan</label>
                <input type="text" name="NamaPelanggan" id="NamaPelanggan" required>
            </div>
            <div>
                <label for="Alamat">Alamat</label>
                <input type="text" name="Alamat" id="Alamat" required>
            </div>
            <div>
                <label for="NomorTelepon">Nomor Telepon</label>
                <input type="text" name="NomorTelepon" id="NomorTelepon" required>
            </div>
            
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
