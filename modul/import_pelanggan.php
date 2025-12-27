<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

include '../lib/koneksi.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cre_pelanggan.php');
    exit;
}

if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== 0) {
    echo "<script>alert('File tidak valid');window.location='cre_pelanggan.php';</script>";
    exit;
}

$ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['xls','xlsx'])) {
    echo "<script>alert('File harus Excel');window.location='cre_pelanggan.php';</script>";
    exit;
}

try {

    $spreadsheet = IOFactory::load($_FILES['file_excel']['tmp_name']);
    $rows = $spreadsheet->getActiveSheet()->toArray();

    unset($rows[0]); // hapus header

    // prepare insert pelanggan
    $stmtInsert = $conn->prepare("
        INSERT INTO pelanggan 
        (NamaPelanggan, id_customer, Alamat, NPWP)
        VALUES 
        (:NamaPelanggan, :id_customer, :Alamat, :NPWP)
    ");

    // prepare cek customer class
    $stmtClass = $conn->prepare("
        SELECT id_customer 
        FROM customerclass 
        WHERE cust_class_code = ?
    ");

    $berhasil = 0;
    $gagal = 0;

    foreach ($rows as $row) {

       $NamaPelanggan = isset($row[0]) ? trim($row[0]) : '';
$CustomerClass = isset($row[1]) ? trim($row[1]) : '';
$Alamat        = isset($row[2]) ? trim($row[2]) : '';
$NPWP          = isset($row[3]) ? trim($row[3]) : '';


        if ($NamaPelanggan === '' || $CustomerClass === '' || $Alamat === '' || $NPWP === '') {
            $gagal++;
            continue;
        }

        // cari id_customer dari nama class
        $stmtClass->execute([$CustomerClass]);
        $id_customer = $stmtClass->fetchColumn();

        if (!$id_customer) {
            // class tidak ditemukan
            $gagal++;
            continue;
        }

        try {
            $stmtInsert->execute([
                ':NamaPelanggan' => $NamaPelanggan,
                ':id_customer'  => $id_customer,
                ':Alamat'       => $Alamat,
                ':NPWP'         => $NPWP
            ]);
            $berhasil++;
        } catch (PDOException $e) {
            $gagal++;
        }
    }

    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Import Pelanggan</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Import Selesai',
            html: 'Berhasil: <b>{$berhasil}</b><br>Gagal: <b>{$gagal}</b>',
            confirmButtonColor: '#1f2a44'
        }).then(() => location='admin_dashboard.php');
    </script>
    </body>
    </html>";
    exit;

} catch (Exception $e) {
    echo '<pre>'.$e->getMessage().'</pre>';
}
