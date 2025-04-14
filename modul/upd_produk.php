<?php

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
        echo "<script>alert('Data Produk berhasil di edit !'); window.location.href='admin_dashboard.php?page=data_produk';</script>";
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
<style>
   body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f2f5;
    color: #333;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #20263f;
    font-size: 24px;
    font-weight: 600;
}

.container {
    max-width: 450px;
    margin: auto;
    margin-top: 10%;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.15);
    padding-right: 45px;
}

label {
    font-weight: 600;
    display: block;
    margin-top: 12px;
    color: #20263f;
}

input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

input:focus {
    border-color: #20263f;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    border: none;
    border-radius: 6px;
    background-color: #20263f;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover {
    background-color: #37406b;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background-color: #20263f;
    color: white;
    font-weight: 600;
}

td {
    background-color: #f9f9f9;
}

tr:nth-child(even) td {
    background-color: #eef;
}

.btn-custom {
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 6px;
    display: inline-block;
    margin: 4px;
    background-color: #20263f;
    color: white;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-custom:hover {
    background-color: #37406b;
}


</style>