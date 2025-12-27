<?php
include '../lib/koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['email'])) {
    die("❌ Anda harus login terlebih dahulu.");
}

// Ambil data barang masuk dan relasinya
$query = "
    SELECT 
        bm.tanggal_masuk,
        bm.keterangan,
        u.nama AS nama_user,
        p.NamaProduk,
        dbm.jumlah
    FROM barang_masuk bm
    JOIN user u ON bm.UserID = u.UserID
    JOIN detai_barang_masuk dbm ON bm.id_masuk = dbm.id_masuk
    JOIN produk p ON dbm.ProdukID = p.ProdukID
    ORDER BY bm.tanggal_masuk DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang Masuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #20263f;
            margin-bottom: 20px;
        }

        .table-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #20263f;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eef1f7;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Data Barang Masuk</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Masuk</th>
                    <th>User</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($data) > 0): ?>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                            <td><?= htmlspecialchars($row['nama_user']) ?></td>
                            <td><?= htmlspecialchars($row['NamaProduk']) ?></td>
                            <td><?= htmlspecialchars($row['jumlah']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada data barang masuk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
