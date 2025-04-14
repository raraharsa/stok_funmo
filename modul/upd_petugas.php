<?php

include '../lib/koneksi.php';

// Periksa apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data user berdasarkan ID
    $sql = "SELECT * FROM user WHERE UserID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan, redirect ke halaman utama
    if (!$user) {
        echo "<script>alert('User tidak ditemukan!'); window.location.href='index.php';</script>";
        exit();
    }
}

// Jika form dikirim, update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "UPDATE user SET nama = :nama, email = :email, password = :password WHERE UserID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='admin_dashboard.php?page=petugas';</script>";
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
    <title>Edit Petugas</title>
</head>
<body>

<div class="container">
    <h2>Edit User</h2>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        
        <label>Password:</label>
        <input type="text" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>

        
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
    padding-right:45px;
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
</style>
