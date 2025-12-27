<?php
include '../lib/koneksi.php';

try {
    $sql = "SELECT * FROM kategori ORDER BY id_kategori DESC";
    $stmt = $conn->query($sql);
    $kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Terjadi kesalahan</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Kategori</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ===== GLOBAL (SAMA INPUT PRODUK) ===== */
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
    color:#333;
}

.wrapper{
    max-width:700px;
    margin:auto;
}

/* ===== CARD ===== */
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

/* ===== TABLE ===== */
.table-wrap{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

th{
    background:#00AEEF;
    color:#fff;
    padding:10px;
    text-align:left;
    font-weight:600;
}

td{
    padding:9px 10px;
    border-bottom:1px solid #edf2f7;
}

tr:hover td{
    background:#f0faff;
}

/* ===== EMPTY ===== */
.empty{
    text-align:center;
    padding:14px;
    color:#64748b;
}
</style>
</head>

<body>
<div class="wrapper">

<div class="card">
<h3>Data Kategori</h3>

<div class="table-wrap">
<table>
<thead>
<tr>
    <th style="width:60px">No</th>
    <th>Nama Kategori</th>
</tr>
</thead>
<tbody>
<?php if(!empty($kategori)): ?>
    <?php $no=1; foreach($kategori as $row): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="2" class="empty">Belum ada data kategori</td>
    </tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</div>
</body>
</html>
