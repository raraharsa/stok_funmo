<?php
include '../lib/koneksi.php';

/* ===== AMBIL ID ===== */
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php?page=data_produk");
    exit;
}

$id = (int) $_GET['id'];

/* ===== AMBIL DATA PRODUK ===== */
$sql = "SELECT * FROM produk WHERE ProdukID = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    echo "<script>
        alert('Produk tidak ditemukan');
        window.location='admin_dashboard.php?page=data_produk';
    </script>";
    exit;
}

/* ===== PROSES UPDATE ===== */
$success = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $NamaProduk = trim($_POST['NamaProduk']);
    $Harga      = trim($_POST['Harga']);
    $Stok       = trim($_POST['Stok']);

    if ($NamaProduk == '' || $Harga == '' || $Stok == '') {
        $errorMsg = 'Semua field wajib diisi';
    } elseif (!is_numeric($Harga) || !is_numeric($Stok)) {
        $errorMsg = 'Harga dan stok harus berupa angka';
    } else {
        $sql = "UPDATE produk 
                SET NamaProduk = :nama,
                    Harga = :harga,
                    Stok = :stok
                WHERE ProdukID = :id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nama'  => $NamaProduk,
            ':harga' => $Harga,
            ':stok'  => $Stok,
            ':id'    => $id
        ]);

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    color:#333;
}

.wrapper{
    max-width:860px;
    margin:auto;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:26px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}

.card h3{
    font-size:20px;
    font-weight:600;
    margin-bottom:20px;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px 18px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

label{
    font-size:12px;
    font-weight:600;
    margin-bottom:6px;
    color:#475569;
}

input{
    padding:10px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
    transition:0.25s;
}

input:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
}

.btn{
    margin-top:22px;
    padding:12px;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:600;
    font-size:14px;
    cursor:pointer;
    width:100%;
    transition:0.25s;
}

.btn:hover{
    background:#0095cc;
}

@media(max-width:700px){
    .grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>

<div class="wrapper">

<div class="card">
<h3>Edit Produk</h3>

<form method="POST">
<div class="grid">

<div class="form-group">
<label>Nama Produk</label>
<input type="text" name="NamaProduk" value="<?= htmlspecialchars($produk['NamaProduk']) ?>">
</div>

<div class="form-group">
<label>Harga</label>
<input type="number" name="Harga" value="<?= htmlspecialchars($produk['Harga']) ?>">
</div>

<div class="form-group">
<label>Stok</label>
<input type="number" name="Stok" value="<?= htmlspecialchars($produk['Stok']) ?>">
</div>

</div>

<button class="btn">Simpan Perubahan</button>
</form>
</div>

</div>

<?php if($success){ ?>
<script>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Data produk berhasil diperbarui'
}).then(()=>{
    window.location='admin_dashboard.php?page=data_produk';
});
</script>
<?php } ?>

<?php if($errorMsg!=''){ ?>
<script>
Swal.fire({
    icon:'error',
    title:'Gagal',
    text:'<?= $errorMsg ?>'
});
</script>
<?php } ?>

</body>
</html>
