<?php 
include '../lib/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM pelangan WHERE PelangganID = :PelangganID";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':PelangganID', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
       
        echo "
        berhasil
    ";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}


?>