<?php
include '../lib/koneksi.php';

$search = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search'])) {
    $search = trim($_POST['search']);
    $sql = "SELECT produk.*, kategori.nama_kategori 
            FROM produk 
            JOIN kategori ON produk.id_kategori = kategori.id_kategori 
            WHERE produk.NamaProduk LIKE :search 
            ORDER BY produk.ProdukID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $sql = "SELECT produk.*, kategori.nama_kategori 
            FROM produk 
            JOIN kategori ON produk.id_kategori = kategori.id_kategori 
            ORDER BY produk.ProdukID DESC";
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
/* ===== GLOBAL (SAMA INPUT PRODUK) ===== */
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
    color:#333;
}

.wrapper{
    max-width:1200px;
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

/* ===== TOOLBAR ===== */
.toolbar{
    display:flex;
    justify-content:flex-end;
    margin-bottom:12px;
}

.search-box{
    display:flex;
    gap:8px;
}

.search-box input{
    padding:8px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
    width:240px;
}

.search-box input:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
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

.search-box button:hover{
    background:#0095cc;
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
    position:sticky;
    top:0;
}

td{
    padding:9px 10px;
    border-bottom:1px solid #edf2f7;
    white-space:nowrap;
}

tr:hover td{
    background:#f0faff;
}

/* ===== BADGE ===== */
.badge-danger{
    background:#ef4444;
    color:#fff;
    padding:3px 10px;
    border-radius:12px;
    font-size:12px;
    font-weight:600;
}

/* ===== BUTTON ===== */
.aksi{
    display:flex;
    gap:6px;
}

.btn{
    padding:5px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    border:none;
    cursor:pointer;
    text-decoration:none;
    color:#fff;
}

.btn.edit{
    background:#00AEEF;
}
.btn.edit:hover{
    background:#0095cc;
}

.btn.hapus{
    background:#ef4444;
}
.btn.hapus:hover{
    background:#dc2626;
}

/* ===== MODAL ===== */
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.35);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:999;
}

.modal.show{display:flex}

.modal-box{
    background:#fff;
    width:340px;
    padding:20px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.modal-box h4{
    margin-bottom:10px;
    color:#0f172a;
}

.modal-action{
    display:flex;
    justify-content:flex-end;
    gap:8px;
    margin-top:16px;
}

.btn.batal{
    background:#94a3b8;
}
.btn.batal:hover{
    background:#64748b;
}
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

<div class="table-wrap">
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
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['NamaProduk']); ?></td>
    <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
    <td>Rp<?= number_format($row['Harga'],0,',','.'); ?></td>
    <td>
        <?php if($row['Stok'] <= 5): ?>
            <span class="badge-danger"><?= $row['Stok']; ?> menipis</span>
        <?php else: ?>
            <?= $row['Stok']; ?>
        <?php endif; ?>
    </td>
    <td><?= $row['batch']; ?></td>
    <td><?= $row['ed']; ?></td>
    <td><?= $row['Satuan']; ?></td>
    <td class="aksi">
        <a href="upd_produk.php?id=<?= $row['ProdukID']; ?>" class="btn edit">Edit</a>
        <button class="btn hapus"
                data-id="<?= $row['ProdukID']; ?>"
                data-nama="<?= htmlspecialchars($row['NamaProduk']); ?>">
            Hapus
        </button>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

</div>

<!-- MODAL -->
<div class="modal" id="modalHapus">
    <div class="modal-box">
        <h4>Hapus Produk</h4>
        <p id="modalText"></p>
        <div class="modal-action">
            <button class="btn batal">Batal</button>
            <a href="#" class="btn hapus" id="confirmHapus">Hapus</a>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('modalHapus');
const modalText = document.getElementById('modalText');
const confirmBtn = document.getElementById('confirmHapus');
const batalBtn = document.querySelector('.btn.batal');

document.querySelectorAll('.btn.hapus').forEach(btn=>{
    btn.addEventListener('click',()=>{
        modalText.innerHTML = `Yakin hapus produk <b>${btn.dataset.nama}</b>?`;
        confirmBtn.href = `del_produk.php?id=${btn.dataset.id}`;
        modal.classList.add('show');
    });
});

batalBtn.onclick = ()=> modal.classList.remove('show');
modal.onclick = e => { if(e.target === modal) modal.classList.remove('show'); };
</script>

</body>
</html>
