<?php
include '../lib/koneksi.php';

/* =============================
   CEK ID
============================= */
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = (int) $_GET['id'];

/* =============================
   AMBIL DATA
============================= */
$sql = "
    SELECT 
        dbm.id_detail_masuk,
        dbm.jumlah,
        dbm.id_masuk,
        bm.tanggal_masuk,
        bm.keterangan,
        p.NamaProduk
    FROM detai_barang_masuk dbm
    JOIN barang_masuk bm ON dbm.id_masuk = bm.id_masuk
    JOIN produk p ON dbm.ProdukID = p.ProdukID
    WHERE dbm.id_detail_masuk = :id
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data barang masuk tidak ditemukan");
}

/* =============================
   UPDATE DATA
============================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jumlah = (int) $_POST['jumlah'];
    $ket    = trim($_POST['keterangan']);
    $id_masuk = (int) $data['id_masuk'];

    try {

        /* UPDATE DETAIL */
        $sql1 = "
            UPDATE detai_barang_masuk
            SET jumlah = :jumlah
            WHERE id_detail_masuk = :id
        ";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        /* UPDATE BARANG MASUK */
        $sql2 = "
            UPDATE barang_masuk
            SET keterangan = :ket
            WHERE id_masuk = :id_masuk
        ";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':ket', $ket);
        $stmt2->bindParam(':id_masuk', $id_masuk, PDO::PARAM_INT);
        $stmt2->execute();

        echo "
        <script>
            alert('✅ Data barang masuk berhasil diperbarui');
            window.location.href='admin_dashboard.php?page=rea_barangmasuk';
        </script>
        ";
        exit;

    } catch (PDOException $e) {
        echo "
        <script>
            alert('❌ Gagal update data');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Barang Masuk</title>

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
}
.card{
    max-width:460px;
    margin:auto;
    background:#fff;
    padding:26px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}
.card h3{
    margin-bottom:18px;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}
label{
    font-size:13px;
    font-weight:600;
    margin-top:12px;
    display:block;
}
input,textarea{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    margin-top:5px;
}
textarea{resize:none}
button{
    margin-top:22px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;
    background:#00AEEF;
    color:#fff;
    font-weight:600;
    cursor:pointer;
}
button:hover{background:#0095cc}
</style>
</head>

<body>

<div class="card">
<h3>Edit Barang Masuk</h3>

<form method="POST">

<label>Produk</label>
<input type="text" value="<?php echo htmlspecialchars($data['NamaProduk']); ?>" readonly>

<label>Jumlah</label>
<input type="number" name="jumlah" value="<?php echo $data['jumlah']; ?>" required>

<label>Keterangan</label>
<textarea name="keterangan"><?php echo htmlspecialchars($data['keterangan']); ?></textarea>

<button type="submit">Simpan Perubahan</button>

</form>
</div>

</body>
</html>
