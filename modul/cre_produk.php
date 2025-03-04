<?php
include '../lib/koneksi.php';

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NamaProduk = $_POST['NamaProduk'];
    $Harga = $_POST['Harga'];
    $Stok = $_POST['Stok'];

    // Validasi input
    if (empty($NamaProduk) || empty($Harga) || empty($Stok)) {
        echo "<p class='text-danger'>Semua field harus diisi!</p>";
    } else {
        try {
            // Query untuk memasukkan data ke tabel pelanggan
            $sql = "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES (:NamaProduk, :Harga, :Stok)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':NamaProduk' => $NamaProduk,
                ':Harga' => $Harga,
                ':Stok' => $Stok
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
    <title>Form Produk</title>
</head>
<body>
    <div class="container">
        <h2>Input Produk</h2>
        <form method="POST" action="cre_produk.php" id="formProduk">
            <div>
                <label for="NamaProduk">Nama Produk</label>
                <input type="text" name="NamaProduk" id="NamaProduk" required>
            </div>
            <div>
                <label for="Harga">Harga Produk</label>
                <input type="number" step="0.01" name="Harga" id="Harga" required>
            </div>
            <div>
                <label for="Stok">Stok Produk</label>
                <input type="number" name="Stok" id="Stok" required>
            </div>
            
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
