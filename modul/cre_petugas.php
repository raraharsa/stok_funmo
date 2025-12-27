<?php
include '../lib/koneksi.php';

/* ===== HANDLE SIMPAN ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $level    = 'Admin';

    if ($nama === '' || $email === '' || $password === '') {
        header("Location: cre_petugas.php?error=empty");
        exit;
    }

    try {
        $query = "INSERT INTO user (nama, email, password, level)
                  VALUES (:nama, :email, :password, :level)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':nama'     => $nama,
            ':email'    => $email,
            ':password' => $password,
            ':level'    => $level
        ]);

        // 🔑 PENTING: redirect setelah POST
        header("Location: cre_petugas.php?success=1");
        exit;

    } catch (PDOException $e) {
        header("Location: cre_petugas.php?error=fail");
        exit;
    }
}

/* ===== DATA ADMIN ===== */
$sql = "SELECT * FROM user WHERE level='Admin' ORDER BY UserID DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$dataAdmin = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
}
.wrapper{
    max-width:1100px;
    margin:auto;
    display:grid;
    grid-template-columns:340px 1fr;
    gap:24px;
}
.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}
.card h3{
    margin-bottom:18px;
    font-size:18px;
    font-weight:600;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}
.form-group{margin-bottom:14px}
label{
    font-size:12px;
    font-weight:600;
    color:#475569;
    margin-bottom:6px;
    display:block;
}
input{
    width:100%;
    padding:10px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
}
input:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
}
.btn{
    width:100%;
    padding:12px;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
}
.btn:hover{background:#0095cc}

.table-wrapper{
    max-height:420px;
    overflow:auto;
}
table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}
th{
    background:#00AEEF;
    color:#fff;
    padding:12px;
    position:sticky;
    top:0;
    text-align:left;
}
td{
    padding:10px;
    border-bottom:1px solid #e5e7eb;
}
tr:hover{background:#f1f7ff}
.action{
    display:flex;
    gap:6px;
}
.btn-edit{
    background:#22c55e;
    padding:6px 10px;
    color:#fff;
    border-radius:8px;
    font-size:12px;
    text-decoration:none;
}
.btn-delete{
    background:#ef4444;
    padding:6px 10px;
    color:#fff;
    border-radius:8px;
    font-size:12px;
    text-decoration:none;
}
@media(max-width:900px){
    .wrapper{grid-template-columns:1fr}
}
</style>
</head>

<body>

<div class="wrapper">

<!-- FORM -->
<div class="card">
<h3>Tambah Admin</h3>
<form method="POST">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <button class="btn">Simpan Admin</button>
</form>
</div>

<!-- TABLE -->
<div class="card">
<h3>Data Admin</h3>
<div class="table-wrapper">
<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Aksi</th>
</tr>
<?php $no=1; foreach($dataAdmin as $row): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td class="action">
        <a href="upd_petugas.php?id=<?= $row['UserID'] ?>" class="btn-edit">Edit</a>
        <a href="del_petugas.php?id=<?= $row['UserID'] ?>"
           class="btn-delete btn-hapus"
           data-nama="<?= htmlspecialchars($row['nama']) ?>">
           Hapus
        </a>
    </td>
</tr>
<?php endforeach ?>
</table>
</div>
</div>

</div>

<!-- ALERT SUCCESS -->
<?php if(isset($_GET['success'])): ?>
<script>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Admin berhasil ditambahkan',
    timer:2000,
    showConfirmButton:false
});
</script>
<?php endif; ?>

<!-- ALERT ERROR -->
<?php if(isset($_GET['error'])): ?>
<script>
Swal.fire({
    icon:'error',
    title:'Gagal',
    text:'Terjadi kesalahan, cek input!'
});
</script>
<?php endif; ?>

<!-- ALERT HAPUS -->
<script>
document.querySelectorAll('.btn-hapus').forEach(btn=>{
    btn.addEventListener('click',function(e){
        e.preventDefault();
        const url = this.href;
        const nama = this.dataset.nama;

        Swal.fire({
            title:'Hapus Admin?',
            html:`Yakin ingin menghapus <b>${nama}</b>?`,
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ef4444',
            cancelButtonColor:'#9ca3af',
            confirmButtonText:'Ya, Hapus',
            cancelButtonText:'Batal'
        }).then((result)=>{
            if(result.isConfirmed){
                window.location = url;
            }
        });
    });
});
</script>

</body>
</html>
