<?php
include '../lib/koneksi.php';
session_start();

/* ===== CEK LOGIN ===== */
if (!isset($_SESSION['email'])) {
    die("❌ Anda harus login terlebih dahulu.");
}

$pesan = "";
$email = $_SESSION['email'];

/* ===== AMBIL USER ID ===== */
$stmtUser = $conn->prepare("SELECT UserID FROM user WHERE email = :email");
$stmtUser->execute([':email' => $email]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    die("❌ User tidak ditemukan.");
}

$loggedInUserId = $userData['UserID'];

/* ===== PROSES FORM ===== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $keterangan    = $_POST['keterangan'];
    $produk_id     = $_POST['ProdukID'];
    $jumlah        = $_POST['jumlah'];

    try {
        $conn->beginTransaction();

        $stmt1 = $conn->prepare(
            "INSERT INTO barang_masuk (tanggal_masuk, keterangan, UserID)
             VALUES (:tanggal, :keterangan, :userid)"
        );
        $stmt1->execute([
            ':tanggal'    => $tanggal_masuk,
            ':keterangan' => $keterangan,
            ':userid'     => $loggedInUserId
        ]);

        $id_masuk = $conn->lastInsertId();

        $stmt2 = $conn->prepare(
            "INSERT INTO detai_barang_masuk (id_masuk, ProdukID, jumlah)
             VALUES (:id_masuk, :produk_id, :jumlah)"
        );
        $stmt2->execute([
            ':id_masuk'  => $id_masuk,
            ':produk_id'=> $produk_id,
            ':jumlah'   => $jumlah
        ]);

        $stmt3 = $conn->prepare(
            "UPDATE produk SET Stok = Stok + :jumlah WHERE ProdukID = :produk_id"
        );
        $stmt3->execute([
            ':jumlah'   => $jumlah,
            ':produk_id'=> $produk_id
        ]);

        $conn->commit();
        $pesan = "<div class='alert success'>Barang masuk berhasil disimpan</div>";
    } catch (PDOException $e) {
        $conn->rollBack();
        $pesan = "<div class='alert error'>Gagal menyimpan data</div>";
    }
}

/* ===== AMBIL PRODUK ===== */
$stmt = $conn->prepare("SELECT ProdukID, NamaProduk FROM produk ORDER BY NamaProduk ASC");
$stmt->execute();
$produkList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Barang Masuk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ================= GLOBAL ================= */
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    color:#333;
}

/* ================= CONTAINER ================= */
.wrapper{
    max-width:700px;
    margin:auto;
}

/* ================= CARD ================= */
.card{
    background:#fff;
    padding:26px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.07);
}

/* ================= TITLE ================= */
.card h2{
    font-size:22px;
    font-weight:600;
    color:#161a2d;
    margin-bottom:20px;
}

/* ================= ALERT ================= */
.alert{
    padding:10px 14px;
    border-radius:8px;
    font-size:13px;
    margin-bottom:16px;
}
.alert.success{
    background:#e6f6fd;
    color:#00AEEF;
}
.alert.error{
    background:#fdecec;
    color:#d9534f;
}

/* ================= FORM GRID ================= */
.grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

label{
    font-size:13px;
    font-weight:500;
    margin-bottom:4px;
    color:#555;
}

input, select{
    padding:9px 11px;
    border-radius:8px;
    border:1px solid #dcdcdc;
    font-size:13px;
    transition:0.25s;
}

input:focus, select:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 2px rgba(0,174,239,.15);
}

/* ================= BUTTON ================= */
.btn{
    margin-top:20px;
    padding:11px;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
    width:100%;
}

.btn:hover{
    background:#0095cc;
}

/* ================= RESPONSIVE ================= */
@media(max-width:600px){
    .grid{ grid-template-columns:1fr; }
}
</style>
</head>

<body>

<div class="wrapper">
<div class="card">

<h2>Barang Masuk</h2>

<?= $pesan ?>

<form method="POST">

<div class="grid">

<div class="form-group">
<label>Tanggal Masuk</label>
<input type="date" name="tanggal_masuk" required>
</div>

<div class="form-group">
<label>Nama Produk</label>
<select name="ProdukID" required>
<option value="">Pilih Produk</option>
<?php foreach ($produkList as $p): ?>
<option value="<?= $p['ProdukID'] ?>">
<?= htmlspecialchars($p['NamaProduk']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<label>Jumlah</label>
<input type="number" name="jumlah" required>
</div>

<div class="form-group">
<label>Keterangan</label>
<input type="text" name="keterangan" required>
</div>

</div>

<button class="btn">Simpan Barang Masuk</button>

</form>
</div>
</div>

</body>
</html>
