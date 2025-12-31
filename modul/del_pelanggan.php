<?php
include '../lib/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare(
    "DELETE FROM pelanggan WHERE PelangganID = ?"
);
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    echo "<script>
        alert('Data berhasil dihapus');
        location='admin_dashboard.php?page=data_pelanggan';
    </script>";
} else {
    echo "<script>
        alert('GAGAL: data tidak ditemukan di database');
        history.back();
    </script>";
}
