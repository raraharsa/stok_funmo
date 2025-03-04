<?php
// koneksi.php berisi kode untuk menghubungkan dengan database
include '../lib/koneksi.php';

// Ambil data siswa
$sql = "SELECT * FROM produk ORDER BY ProdukID DESC"; // Memastikan data diurutkan dengan benar
$stmt = $conn->prepare($sql);
$stmt->execute();
$pel = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query dalam variabel siswa
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    
</head>
<body>

<h2>Data Produk</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Harga Produk</th>
        <th>Stok Produk</th>
    </tr>
    <?php
                    $no = 1;
                    foreach ($pel as $row) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['NamaProduk']; ?></td>
                            <td><?php echo $row['Harga']; ?></td>
                            <td><?php echo $row['Stok']; ?></td>

                            <td>
                                <a href="upd_produk.php?id=<?php echo $row['ProdukID']; ?>" class="btn-custom">Edit</a>
                                <a href="del_produk.php?id=<?php echo $row['ProdukID']; ?>" class="btn-custom">Hapus</a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
</table>

</body>
</html>

