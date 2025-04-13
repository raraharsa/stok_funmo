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
        echo "<script>alert('Pelanggan tidak ditemukan!'); window.location.href='rea_pelanggan.php';</script>";
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
        echo "<script>alert('Data Pelanggan berhasil di edit !'); window.location.href='admin_dashboard.php?page=data_pelanggan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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