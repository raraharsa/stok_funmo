
<?php 
include '../lib/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM user WHERE UserID = :UserID";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':UserID', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Transaksi berhasil dihapus!'); window.location.href='admin_dashboard.php?page=petugas';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
    
}


?>