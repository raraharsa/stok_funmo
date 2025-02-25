<?php
$host = "localhost";
$username = "root";
$password = "arin12345";
$dbname = "kasir";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     // Tambahkan pesan ini jika ingin memastikan koneksi berhasil
    //  echo "koneksi berhasil";
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
