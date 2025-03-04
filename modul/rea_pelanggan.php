<?php
// koneksi.php berisi kode untuk menghubungkan dengan database
include '../lib/koneksi.php';

// Ambil data siswa
$sql = "SELECT * FROM pelanggan ORDER BY PelangganID DESC"; // Memastikan data diurutkan dengan benar
$stmt = $conn->prepare($sql);
$stmt->execute();
$pel = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query dalam variabel siswa
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
    
</head>
<body>

<h2>Data Pelanggan</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Nomor Telepon</th>
    </tr>
    <?php
                    $no = 1;
                    foreach ($pel as $row) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['NamaPelanggan']; ?></td>
                            <td><?php echo $row['Alamat']; ?></td>
                            <td><?php echo $row['NomorTelepon']; ?></td>

                            <td>
                                <a href="upd_pelanggan.php?id=<?php echo $row['PelangganID']; ?>" class="btn-custom">Edit</a>
                                <a href="del_pelanggan.php?id=<?php echo $row['PelangganID']; ?>" class="btn-custom">Hapus</a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
</table>

</body>
</html>

