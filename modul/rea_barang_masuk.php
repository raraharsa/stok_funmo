<?php
session_start();
include '../lib/koneksi.php';

if (!isset($_SESSION['nama'])) {
    die("Akses ditolak");
}

/* ===== AMBIL DATA BARANG MASUK ===== */
$sql = "
SELECT 
    bm.id_masuk,
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

$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Barang Masuk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
}

.wrapper{
    max-width:1100px;
    margin:auto;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}

.card h3{
    font-size:18px;
    font-weight:600;
    margin-bottom:18px;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#00AEEF;
    color:#fff;
    padding:10px;
    text-align:left;
}

td{
    padding:10px;
    border-bottom:1px solid #edf2f7;
}

tr:hover td{
    background:#f0faff;
}

.aksi{
    display:flex;
    gap:6px;
}

.btn{
    padding:6px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
    border:none;
    cursor:pointer;
    color:#fff;
}

.btn.edit{
    background:#00AEEF;
}
.btn.edit:hover{ background:#0095cc; }

.btn.hapus{
    background:#ef4444;
}
.btn.hapus:hover{ background:#dc2626; }
</style>
</head>

<body>
<div class="wrapper">
<div class="card">

<h3>Data Barang Masuk</h3>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>User</th>
    <th>Produk</th>
    <th>Jumlah</th>
    <th>Keterangan</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php if(count($data) > 0): ?>
<?php $no=1; foreach($data as $row): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= date('d-m-Y', strtotime($row['tanggal_masuk'])) ?></td>
    <td><?= htmlspecialchars($row['nama_user']) ?></td>
    <td><?= htmlspecialchars($row['NamaProduk']) ?></td>
    <td><?= htmlspecialchars($row['jumlah']) ?></td>
    <td><?= htmlspecialchars($row['keterangan']) ?></td>
    <td class="aksi">
        <a href="upd_barang_masuk.php?id=<?= $row['id_masuk'] ?>" class="btn edit">Edit</a>
        <a href="del_barang_masuk.php?id=<?= $row['id_masuk'] ?>"
           class="btn hapus"
           onclick="return confirm('Yakin hapus data barang masuk ini?')">
           Hapus
        </a>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="7">Data barang masuk belum ada.</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</div>
</div>
</body>
</html>
