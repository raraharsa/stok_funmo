<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../lib/koneksi.php';

/* ===== Ambil customer class ===== */
$stmt = $conn->prepare("SELECT * FROM customerclass ORDER BY cust_class_code ASC");
$stmt->execute();
$custclass = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== Flag ===== */
$success = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['file_excel'])) {

    $NamaPelanggan = trim($_POST['NamaPelanggan']);
    $id_customer   = trim($_POST['id_customer']);
    $Alamat        = trim($_POST['Alamat']);
    $NPWP          = trim($_POST['NPWP']);

    if ($NamaPelanggan == '' || $id_customer == '' || $Alamat == '' || $NPWP == '') {
        $errorMsg = 'Semua field wajib diisi.';
    } else {
        try {
            $sql = "INSERT INTO pelanggan
                    (NamaPelanggan, id_customer, Alamat, NPWP)
                    VALUES
                    (:NamaPelanggan, :id_customer, :Alamat, :NPWP)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':NamaPelanggan' => $NamaPelanggan,
                ':id_customer'   => $id_customer,
                ':Alamat'        => $Alamat,
                ':NPWP'          => $NPWP
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
<title>Input Outlet</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ===== GLOBAL (SAMA DENGAN INPUT PRODUK) ===== */
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
<h3>Tambah Outlet</h3>

<form method="POST" id="formPelanggan">
<div class="grid">

<div class="form-group">
<label>Nama Outlet</label>
<input type="text" name="NamaPelanggan" id="NamaPelanggan">
</div>

<div class="form-group">
<label>Customer Class</label>
<select name="id_customer" id="id_customer">
<option value="">Pilih Customer Class</option>
<?php foreach($custclass as $c){ ?>
<option value="<?= $c['id_customer'] ?>">
<?= htmlspecialchars($c['cust_class_code']) ?>
</option>
<?php } ?>
</select>
</div>

<div class="form-group">
<label>Alamat</label>
<input type="text" name="Alamat" id="Alamat">
</div>

<div class="form-group">
<label>NPWP</label>
<input type="text" name="NPWP" id="NPWP" placeholder="00.000.000.0-000.000">
</div>

</div>
<button class="btn">Simpan Outlet</button>
</form>
</div>

<!-- ===== IMPORT ===== -->
<div class="card">
<h3>Import Outlet (Excel)</h3>
<form method="POST" action="import_pelanggan.php" enctype="multipart/form-data">
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
    text:'Outlet berhasil ditambahkan'
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
