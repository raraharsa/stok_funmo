<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

include '../lib/koneksi.php';
include '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

/* ================= CEK METHOD ================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_produk.php');
    exit;
}

/* ================= CEK FILE ================= */
if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== 0) {
    echo "<script>alert('File tidak valid');window.location='admin_dashboard.php';</script>";
    exit;
}

$allowed = ['xls', 'xlsx'];
$ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    echo "<script>alert('File harus Excel (.xls / .xlsx)');window.location='admin_dashboard.php';</script>";
    exit;
}

try {

    /* ================= BACA EXCEL ================= */
    $spreadsheet = IOFactory::load($_FILES['file_excel']['tmp_name']);
    $rows = $spreadsheet->getActiveSheet()->toArray();

    unset($rows[0]); // hapus header

    /* ================= PREPARE SQL ================= */
    $sql = "INSERT INTO produk
            (NamaProduk, id_kategori, kemasan, Harga, Stok, batch, ed, satuan)
            VALUES
            (:NamaProduk, :id_kategori, :kemasan, :Harga, :Stok, :batch, :ed, :satuan)";

    $stmt = $conn->prepare($sql);

    $berhasil = 0;
    $gagal = 0;

    /* ================= LOOP DATA ================= */
    foreach ($rows as $row) {

$NamaProduk  = isset($row[0]) ? trim($row[0]) : '';
$id_kategori = isset($row[1]) ? trim($row[1]) : '';
$kemasan     = isset($row[2]) ? trim($row[2]) : '';
$Harga       = isset($row[3]) ? trim($row[3]) : '';
$Stok        = isset($row[4]) ? trim($row[4]) : '';
$batch       = isset($row[5]) ? trim($row[5]) : '';
$ed          = isset($row[6]) ? trim($row[6]) : '';
$satuan      = isset($row[7]) ? trim($row[7]) : '';


        if (
            $NamaProduk === '' || $id_kategori === '' || $kemasan === '' ||
            !is_numeric($Harga) || !is_numeric($Stok) ||
            $batch === '' || $ed === '' || $satuan === ''
        ) {
            $gagal++;
            continue;
        }

        try {
            $stmt->execute([
                ':NamaProduk'  => $NamaProduk,
                ':id_kategori' => $id_kategori,
                ':kemasan'     => $kemasan,
                ':Harga'       => $Harga,
                ':Stok'        => $Stok,
                ':batch'       => $batch,
                ':ed'          => $ed,
                ':satuan'      => $satuan
            ]);
            $berhasil++;
        } catch (PDOException $e) {
            $gagal++;
        }
    }

    /* ================= OUTPUT SWEETALERT ================= */
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Import Produk</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Import Selesai',
            html: 'Berhasil: <b>{$berhasil}</b><br>Gagal: <b>{$gagal}</b>',
            confirmButtonColor: '#1f2a44'
        }).then(() => {
            window.location = 'admin_dashboard.php';
        });
        </script>
    </body>
    </html>";
    exit;

} catch (Exception $e) {
    echo "<script>alert('Gagal membaca file Excel');window.location='create_produk.php';</script>";
    exit;
}
