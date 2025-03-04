<?php
include '../lib/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    try {
        // Query insert data dengan PDO
        $query = "INSERT INTO user (nama, email, password, level) VALUES (:nama, :email, :password, :level)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':level', $level);

        if ($stmt->execute()) {
            echo "<script>alert('Petugas berhasil ditambahkan!');window.location='../admin/user.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan petugas!');window.location='cre_petugas.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Tambah Petugas</h2>
    <form action="cre_petugas.php" method="POST" id="formPetugas>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Petugas</label>
            <input type="text" class="form-control" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <select class="form-control" name="level" required>
                <option value="petugas">Petugas</option>
                
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
