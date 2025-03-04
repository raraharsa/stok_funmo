<?php 
include '../lib/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM produk WHERE ProdukID = :ProdukID";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ProdukID', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
       
        echo "
        berhasil
    ";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}


?>