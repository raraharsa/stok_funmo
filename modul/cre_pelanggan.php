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