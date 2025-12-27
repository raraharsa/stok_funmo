<?php
include '../lib/koneksi.php';

/* ===== Flag ===== */
$success = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cust_class_code = trim($_POST['cust_class_code']);

    if ($cust_class_code == '') {
        $errorMsg = 'Customer Class wajib diisi.';
    } else {
        try {
            $sql = "INSERT INTO customerclass (cust_class_code) VALUES (:cust_class_code)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':cust_class_code' => $cust_class_code
            ]);
            $success = true;
        } catch (PDOException $e) {
            $errorMsg = 'Gagal menambahkan Customer Class.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Customer Class</title>
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
    max-width:600px;
    margin:auto;
}

/* ===== CARD ===== */
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

/* ===== FORM ===== */
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
</style>
</head>

<body>
<div class="wrapper">

<div class="card">
<h3>Tambah Customer Class</h3>

<form method="POST">
<div class="form-group">
    <label>Customer Class</label>
    <input type="text" name="cust_class_code" placeholder="Contoh: Puskesmas / Rumah Sakit">
</div>

<button class="btn">Simpan Customer Class</button>
</form>
</div>

</div>

<!-- ===== ALERT ===== -->
<?php if($success){ ?>
<script>
Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Customer Class berhasil ditambahkan'
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
