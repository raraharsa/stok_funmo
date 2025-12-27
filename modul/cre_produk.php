<?php
include '../lib/koneksi.php';

/* ===== Ambil kategori ===== */
$sqlKategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$stmtKategori = $conn->prepare($sqlKategori);
$stmtKategori->execute();
$kategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

/* ===== Flag ===== */
$success = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $NamaProduk  = trim($_POST['NamaProduk']);
    $id_kategori = trim($_POST['id_kategori']);
    $Kemasan     = trim($_POST['Kemasan']);
    $Harga       = trim($_POST['Harga']);
    $Stok        = trim($_POST['Stok']);
    $batch       = trim($_POST['batch']);
    $ed          = trim($_POST['ed']);
    $satuan      = trim($_POST['satuan']);

    if (
        $NamaProduk == '' || $id_kategori == '' || $Kemasan == '' ||
        $Harga == '' || $Stok == '' || $batch == '' || $ed == '' || $satuan == ''
    ) {
        $errorMsg = 'Semua field wajib diisi.';
    } elseif (!is_numeric($Harga) || !is_numeric($Stok)) {
        $errorMsg = 'Harga dan stok harus angka.';
    } else {
        try {
            $sql = "INSERT INTO produk
            (NamaProduk, id_kategori, kemasan, Harga, Stok, batch, ed, satuan)
            VALUES
            (:NamaProduk, :id_kategori, :Kemasan, :Harga, :Stok, :batch, :ed, :satuan)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':NamaProduk'=>$NamaProduk,
                ':id_kategori'=>$id_kategori,
                ':Kemasan'=>$Kemasan,
                ':Harga'=>$Harga,
                ':Stok'=>$Stok,
                ':batch'=>$batch,
                ':ed'=>$ed,
                ':satuan'=>$satuan
            ]);
            $success = true;
        } catch (PDOException $e) {
            $errorMsg = 'Gagal menyimpan data.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Produk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ===== GLOBAL ===== */
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

/* ===== CARD ===== */
.card{
    background:#fff;
    border-radius:16px;
    padding:26px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    margin-bottom:26px;
}

.card h3{
    font-size:20px;
    font-weight:600;
    margin-bottom:20px;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}

/* ===== FORM GRID ===== */
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

input,select{
    padding:10px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
    transition:0.25s;
}

input:focus,select:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
}

/* ===== BUTTON ===== */
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

/* ===== IMPORT ===== */
.import-box{
    display:flex;
    gap:14px;
    align-items:center;
}

.import-box input{
    flex:1;
}

/* ===== RESPONSIVE ===== */
@media(max-width:700px){
    .grid{
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>
<div class="wrapper">

<!-- ===== INPUT MANUAL ===== -->
<div class="card">
<h3>Tambah Produk</h3>

<form method="POST" id="formProduk">
<div class="grid">

<div class="form-group">
<label>Nama Produk</label>
<input type="text" name="NamaProduk" id="NamaProduk">
</div>

<div class="form-group">
<label>Kategori</label>
<select name="id_kategori" id="id_kategori">
<option value="">Pilih Kategori</option>
<?php foreach($kategori as $k){ ?>
<option value="<?= $k['id_kategori'] ?>">
<?= htmlspecialchars($k['nama_kategori']) ?>
</option>
<?php } ?>
</select>
</div>

<div class="form-group">
<label>Kemasan</label>
<input type="text" name="Kemasan" id="Kemasan">
</div>

<div class="form-group">
<label>Satuan</label>
<select name="satuan" id="satuan">
<option value="">Pilih Satuan</option>
<option value="pcs">pcs</option>
<option value="box">box</option>
</select>
</div>

<div class="form-group">
<label>Harga</label>
<input type="number" name="Harga" id="Harga">
</div>

<div class="form-group">
<label>Stok</label>
<input type="number" name="Stok" id="Stok">
</div>

<div class="form-group">
<label>Batch</label>
<input type="text" name="batch" id="batch">
</div>

<div class="form-group">
<label>Expire Date</label>
<input type="date" name="ed" id="ed">
</div>

</div>
<button class="btn">Simpan Produk</button>
</form>
</div>

<!-- ===== IMPORT ===== -->
<div class="card">
<h3>Import Produk (Excel)</h3>
<form method="POST" action="import_produk.php" enctype="multipart/form-data">
<div class="import-box">
<input type="file" name="file_excel" accept=".xls,.xlsx" required>
<button class="btn" style="width:190px;">Import</button>
</div>
</form>
</div>

</div>

<!-- ===== ALERT ===== -->
<?php if($success){ ?>
<script>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Produk berhasil ditambahkan'
}).then(()=>location='admin_dashboard.php');
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
