<?php
include '../lib/koneksi.php';

$sql = "SELECT * FROM kategori ORDER BY id_kategori DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Kategori</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
}
.wrapper{max-width:700px;margin:auto}
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
table{width:100%;border-collapse:collapse}
th{
    background:#00AEEF;
    color:#fff;
    padding:10px;
}
td{
    padding:10px;
    border-bottom:1px solid #e5e7eb;
}
tr:hover td{background:#f0faff}
.btn{
    padding:5px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    color:#fff;
    text-decoration:none;
    cursor:pointer;
}
.btn-edit{background:#22c55e}
.btn-del{background:#ef4444}
.aksi{display:flex;gap:6px}

/* MODAL */
.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.4);
    display:none;
    align-items:center;
    justify-content:center;
}
.modal.show{display:flex}
.modal-box{
    background:#fff;
    padding:20px;
    border-radius:14px;
    width:320px;
}
.modal-box h4{margin-bottom:10px}
.modal-action{display:flex;justify-content:flex-end;gap:8px}
</style>
</head>

<body>
<div class="wrapper">
<div class="card">
<h3>Data Kategori</h3>

<table>
<tr>
    <th width="60">No</th>
    <th>Nama Kategori</th>
    <th width="120">Aksi</th>
</tr>
<?php $no=1; foreach($kategori as $k): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($k['nama_kategori']) ?></td>
    <td class="aksi">
        <a href="upd_kategori.php?id=<?= $k['id_kategori'] ?>" class="btn btn-edit">Edit</a>
        <button class="btn btn-del btn-hapus-kategori"
            data-id="<?= $k['id_kategori'] ?>"
            data-nama="<?= htmlspecialchars($k['nama_kategori']) ?>">
            Hapus
        </button>
    </td>
</tr>
<?php endforeach ?>
</table>
</div>
</div>

<!-- MODAL -->
<div class="modal" id="modalHapus">
<div class="modal-box">
<h4>Hapus Kategori</h4>
<p id="modalText"></p>
<div class="modal-action">
    <button onclick="tutup()">Batal</button>
    <a id="btnHapus" class="btn btn-del">Hapus</a>
</div>
</div>
</div>

<script>
const modal = document.getElementById('modalHapus');
const text  = document.getElementById('modalText');
const btn   = document.getElementById('btnHapus');

document.querySelectorAll('.btn-hapus-kategori').forEach(b=>{
    b.onclick = () => {
        text.innerHTML = `Yakin hapus <b>${b.dataset.nama}</b>?`;
        btn.href = `del_kategori.php?id=${b.dataset.id}`;
        modal.classList.add('show');
    }
});

function tutup(){
    modal.classList.remove('show');
}
</script>
</body>
</html>
