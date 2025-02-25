<?php
include '../lib/koneksi.php';

// Query untuk mendapatkan data absensi
$sql = "SELECT absensi.id, tb_siswa.nama, tb_siswa.kelas, absensi.tanggal, absensi.status 
        FROM absensi 
        JOIN tb_siswa ON absensi.id_siswa = tb_siswa.id";
$data = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Data Absensi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tema Navy */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
        }

        .container {
            margin-top: 50px;
        }

        h2 {
            color: #003366; /* Navy Color */
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            background-color: #003366; /* Navy color */
            color: white;
        }

        .table td {
            text-align: center;
        }

        /* Tombol Edit dan Hapus */
        .btn-custom {
            color: white;
            background-color: #003366;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-custom:hover {
            background-color: #002244;
        }

        .btn-danger {
            background-color: #d9534f;
            
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Data Absensi</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Absensi</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($data) > 0): ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['kelas']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <!-- Tombol Edit dan Hapus dengan tema navy -->
                            <a href="modul/editsen.php?id=<?= $row['id'] ?>" class="btn-custom">Edit</a>
                            <a href="modul/delsen.php?id=<?= $row['id'] ?>" class=" btn  btn btn-danger">Hapus</a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data absensi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
