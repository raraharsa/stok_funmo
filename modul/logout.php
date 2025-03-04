<?php 
session_start();
if (isset($_SESSION['UserID'])) { // Cek apakah iduser ada di sesi
    session_destroy(); // Hapus sesi
}
header('Location: ../login.php'); // Arahkan kembali ke halaman login setelah logout
exit();
?>