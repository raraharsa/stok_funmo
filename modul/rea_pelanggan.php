<?php
include '../lib/koneksi.php';

$search = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = trim($_POST['search']);
    $sql = "SELECT p.*, c.cust_class_code
            FROM pelanggan p
            INNER JOIN customerclass c ON p.id_customer = c.id_customer
            WHERE p.NamaPelanggan LIKE :search
            ORDER BY p.PelangganID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $sql = "SELECT p.*, c.cust_class_code
            FROM pelanggan p
            INNER JOIN customerclass c ON p.id_customer = c.id_customer
            ORDER BY p.PelangganID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$pel = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pelanggan</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ===== GLOBAL (SAMA DENGAN INPUT PRODUK) ===== */
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    color:#333;
    font-size:13px;
}

.wrapper{
    max-width:1100px;
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
    width:220px;
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
}

td{
    padding:9px 10px;
    border-bottom:1px solid #edf2f7;
    vertical-align:top;
}

tr:hover td{
    background:#f0faff;
}

/* ===== AKSI BUTTON ===== */
.aksi{
    display:flex;
    gap:6px;
}

.btn{
    padding:5px 12px;
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
<h3>Data Pelanggan</h3>

<div class="toolbar">
    <form method="POST" class="search-box">
        <input type="text" name="search" placeholder="Cari nama pelanggan..."
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
    <th>Class</th>
    <th>Alamat</th>
    <th>NPWP</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php $no=1; foreach($pel as $row): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['NamaPelanggan']); ?></td>
    <td><?= htmlspecialchars($row['cust_class_code']); ?></td>
    <td><?= htmlspecialchars($row['Alamat']); ?></td>
    <td><?= htmlspecialchars($row['NPWP']); ?></td>
    <td class="aksi">
        <a href="upd_pelanggan.php?id=<?= $row['PelangganID']; ?>" class="btn edit">Edit</a>
        <button class="btn hapus"
                data-id="<?= $row['PelangganID']; ?>"
                data-nama="<?= htmlspecialchars($row['NamaPelanggan']); ?>">
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
        <h4>Hapus Data</h4>
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
        modalText.innerHTML = `Yakin ingin menghapus <b>${btn.dataset.nama}</b>?`;
        confirmBtn.href = `del_pelanggan.php?id=${btn.dataset.id}`;
        modal.classList.add('show');
    });
});

batalBtn.onclick = ()=> modal.classList.remove('show');
modal.onclick = e => { if(e.target===modal) modal.classList.remove('show'); };
</script>

</body>
</html>
