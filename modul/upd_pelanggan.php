<?php
include '../lib/koneksi.php';

/* ===== AMBIL DATA ===== */
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM pelanggan WHERE PelangganID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pelanggan) {
        echo "<script>alert('Data pelanggan tidak ditemukan'); window.location.href='admin_dashboard.php?page=data_pelanggan';</script>";
        exit;
    }
}

/* ===== UPDATE DATA ===== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $NamaPelanggan = trim($_POST['NamaPelanggan']);
    $Alamat        = trim($_POST['Alamat']);
    $NPWP          = trim($_POST['NPWP']);

    if ($NamaPelanggan == '' || $Alamat == '' || $NPWP == '') {
        echo "<script>alert('Semua field wajib diisi');</script>";
    } else {

        $sql = "UPDATE pelanggan SET 
                    NamaPelanggan = :nama,
                    Alamat = :alamat,
                    NPWP = :npwp
                WHERE PelangganID = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nama', $NamaPelanggan);
        $stmt->bindParam(':alamat', $Alamat);
        $stmt->bindParam(':npwp', $NPWP);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                alert('Data pelanggan berhasil diperbarui');
                window.location.href='admin_dashboard.php?page=data_pelanggan';
            </script>";
        } else {
            echo "<script>alert('Gagal memperbarui data');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Pelanggan</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
}

/* ===== WRAPPER ===== */
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
    margin-bottom:16px;
}

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

/* ===== BUTTON ===== */
.btn{
    width:100%;
    padding:12px;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:600;
    font-size:14px;
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

<h3>Edit Pelanggan</h3>

<form method="POST">

    <div class="form-group">
        <label>Nama Pelanggan</label>
        <input type="text" name="NamaPelanggan"
        value="<?php echo htmlspecialchars($pelanggan['NamaPelanggan']); ?>">
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="Alamat"
        value="<?php echo htmlspecialchars($pelanggan['Alamat']); ?>">
    </div>

    <div class="form-group">
        <label>NPWP</label>
        <input type="text" name="NPWP"
        value="<?php echo htmlspecialchars($pelanggan['NPWP']); ?>">
    </div>

    <button class="btn">Simpan Perubahan</button>

</form>

</div>
</div>

</body>
</html>
