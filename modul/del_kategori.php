<?php
include '../lib/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    echo "<script>
        alert('ID kategori tidak valid');
        history.back();
    </script>";
    exit;
}

/* CEK APAKAH KATEGORI DIPAKAI PRODUK */
$cek = $conn->prepare(
    "SELECT COUNT(*) FROM produk WHERE id_kategori = ?"
);
$cek->execute([$id]);
$jumlah = $cek->fetchColumn();

if ($jumlah > 0) {
    echo "<script>
        alert('Kategori tidak bisa dihapus karena masih digunakan oleh produk!');
        location='admin_dashboard.php?page=data_kategori';
    </script>";
    exit;
}

/* HAPUS KATEGORI */
$hapus = $conn->prepare(
    "DELETE FROM kategori WHERE id_kategori = ?"
);
$hapus->execute([$id]);

if ($hapus->rowCount() > 0) {
    echo "<script>
        alert('Kategori berhasil dihapus');
        location='admin_dashboard.php?page=data_kategori';
    </script>";
} else {
    echo "<script>
        alert('Gagal: data kategori tidak ditemukan');
        history.back();
    </script>";
}
