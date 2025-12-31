<?php
include '../lib/koneksi.php';

if(!isset($_GET['id'])) die('ID tidak ditemukan');
$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM kategori WHERE id_kategori=:id");
$stmt->bindParam(':id',$id,PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$data) die('Data tidak ditemukan');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $nama = trim($_POST['nama_kategori']);
    if($nama!=''){
        $up = $conn->prepare("UPDATE kategori SET nama_kategori=:n WHERE id_kategori=:id");
        $up->execute([':n'=>$nama,':id'=>$id]);
        echo "<script>alert('Kategori berhasil diupdate');location='admin_dashboard.php?page=data_kategori'</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Kategori</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>
body{font-family:Poppins;background:#f4f7fb;padding:30px}
.card{max-width:400px;margin:auto;background:#fff;padding:25px;border-radius:16px}
label{font-size:12px;font-weight:600}
input{width:100%;padding:10px;margin-top:6px;border-radius:8px;border:1px solid #ccc}
button{margin-top:20px;width:100%;padding:12px;border:none;border-radius:10px;background:#00AEEF;color:#fff;font-weight:600}
</style>
</head>
<body>
<div class="card">
<h3>Edit Kategori</h3>
<form method="POST">
<label>Nama Kategori</label>
<input type="text" name="nama_kategori" value="<?= htmlspecialchars($data['nama_kategori']) ?>" required>
<button>Simpan Perubahan</button>
</form>
</div>
</body>
</html>
