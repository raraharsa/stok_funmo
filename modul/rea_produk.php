<?php
include '../lib/koneksi.php';

$search = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['search'])) {
    $search = trim($_POST['search']);
    $sql = "SELECT p.*, k.nama_kategori
            FROM produk p
            JOIN kategori k ON p.id_kategori = k.id_kategori
            WHERE p.NamaProduk LIKE :search
            ORDER BY p.ProdukID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $sql = "SELECT p.*, k.nama_kategori
            FROM produk p
            JOIN kategori k ON p.id_kategori = k.id_kategori
            ORDER BY p.ProdukID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Produk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
}
.wrapper{max-width:1200px;margin:auto}
.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}
.card h3{
    font-size:18px;
    margin-bottom:18px;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}
.toolbar{display:flex;justify-content:flex-end;margin-bottom:12px}
.search-box{display:flex;gap:8px}
.search-box input{
    padding:8px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
}
.search-box button{
    padding:8px 16px;
    border:none;
    border-radius:10px;
    background:#00AEEF;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}
table{width:100%;border-collapse:collapse}
th{
    background:#00AEEF;
    color:#fff;
    padding:10px;
}
td{
    padding:9px 10px;
    border-bottom:1px solid #edf2f7;
    white-space:nowrap;
}
tr:hover td{background:#f0faff}
.badge-danger{
    background:#ef4444;
    color:#fff;
    padding:3px 10px;
    border-radius:12px;
    font-size:12px;
}
.aksi{display:flex;gap:6px}
.btn{
    padding:5px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    border:none;
    cursor:pointer;
    color:#fff;
    text-decoration:none;
}
.btn.edit{background:#00AEEF}
.btn.hapus{background:#ef4444}

/* MODAL */
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.35);
    display:none;
    align-items:center;
    justify-content:center;
}
.modal.show{display:flex}
.modal-box{
    background:#fff;
    width:340px;
    padding:20px;
    border-radius:14px;
}
.modal-action{
    display:flex;
    justify-content:flex-end;
    gap:8px;
    margin-top:16px;
}
.btn.batal{background:#94a3b8}
</style>
</head>

<body>
<div class="wrapper">
<div class="card">

<h3>Data Produk</h3>

<div class="toolbar">
    <form method="POST" class="search-box">
        <input type="text" name="search" placeholder="Cari nama produk..."
               value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>
</div>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Batch</th>
    <th>Expire</th>
    <th>Satuan</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php $no=1; foreach($data as $row): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['NamaProduk']) ?></td>
    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
    <td>Rp<?= number_format($row['Harga'],0,',','.') ?></td>
    <td>
        <?php if($row['Stok'] <= 5): ?>
            <span class="badge-danger"><?= $row['Stok'] ?> menipis</span>
        <?php else: ?>
            <?= $row['Stok'] ?>
        <?php endif ?>
    </td>
    <td><?= $row['batch'] ?></td>
    <td><?= $row['ed'] ?></td>
    <td><?= $row['Satuan'] ?></td>
    <td class="aksi">
        <a href="upd_produk.php?id=<?= $row['ProdukID'] ?>" class="btn edit">Edit</a>
        <button class="btn hapus btn-hapus-produk"
                data-id="<?= $row['ProdukID'] ?>"
                data-nama="<?= htmlspecialchars($row['NamaProduk']) ?>">
            Hapus
        </button>
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>

</div>
</div>

<!-- MODAL -->
<div class="modal" id="modalHapus">
<div class="modal-box">
    <h4>Hapus Produk</h4>
    <p id="modalText"></p>
    <div class="modal-action">
        <button class="btn batal">Batal</button>
        <a class="btn hapus" id="confirmHapus">Hapus</a>
    </div>
</div>
</div>

<script>
const modal = document.getElementById('modalHapus');
const modalText = document.getElementById('modalText');
const confirmBtn = document.getElementById('confirmHapus');
const batalBtn = document.querySelector('.btn.batal');

document.querySelectorAll('.btn-hapus-produk').forEach(btn=>{
    btn.onclick = () => {
        modalText.innerHTML = `Yakin hapus produk <b>${btn.dataset.nama}</b>?`;
        confirmBtn.href = `del_produk.php?id=${btn.dataset.id}`;
        modal.classList.add('show');
    };
});

batalBtn.onclick = () => modal.classList.remove('show');
modal.onclick = e => { if(e.target === modal) modal.classList.remove('show'); };
</script>

</body>
</html>
