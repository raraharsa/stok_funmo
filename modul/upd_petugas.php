<?php
include '../lib/koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak valid'); history.back();</script>";
    exit;
}

$id = (int)$_GET['id'];

/* === AMBIL DATA USER === */
$sql = "SELECT * FROM user WHERE UserID = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('User tidak ditemukan'); history.back();</script>";
    exit;
}

/* === UPDATE DATA === */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($nama == '' || $email == '' || $password == '') {
        echo "<script>alert('Semua field wajib diisi');</script>";
    } else {

        $sql = "UPDATE user 
                SET nama = :nama, email = :email, password = :password 
                WHERE UserID = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                alert('Data petugas berhasil diperbarui');
                window.location='admin_dashboard.php?page=petugas';
            </script>";
        } else {
            echo "<script>alert('Gagal menyimpan perubahan');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
}

.wrapper{
    max-width:600px;
    margin:auto;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:26px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.card h3{
    font-size:20px;
    font-weight:600;
    margin-bottom:20px;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}

/* FORM */
.form-group{
    display:flex;
    flex-direction:column;
    margin-bottom:16px;
}

label{
    font-size:12px;
    font-weight:600;
    margin-bottom:6px;
    color:#475569;
}

input{
    padding:11px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
}

input:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
}

/* BUTTON */
.btn{
    margin-top:10px;
    padding:12px;
    width:100%;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:600;
    cursor:pointer;
}

.btn:hover{
    background:#0095cc;
}
</style>
</head>

<body>

<div class="wrapper">
<div class="card">

<h3>Edit Data Petugas</h3>

<form method="POST">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="text" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">
    </div>

    <button class="btn">Simpan Perubahan</button>
</form>

</div>
</div>

</body>
</html>
