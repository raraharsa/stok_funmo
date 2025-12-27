<?php
include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* ===== PELANGGAN ===== */
$stmt = $conn->prepare("SELECT PelangganID, NamaPelanggan FROM pelanggan ORDER BY NamaPelanggan ASC");
$stmt->execute();
$pelangganList = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== PRODUK ===== */
$stmt = $conn->prepare("SELECT * FROM produk ORDER BY NamaProduk ASC");
$stmt->execute();
$produkList = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== SIMPAN ===== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pelangganID = $_POST['PelangganID'];
    $paketID = isset($_POST['PaketID']) ? $_POST['PaketID'] : null;
    $tanggal = date('Y-m-d');

    $stmt = $conn->prepare(
        "INSERT INTO penjualan (TanggalPenjualan, PelangganID, PaketID, TotalHarga)
         VALUES (?, ?, ?, 0)"
    );
    $stmt->execute(array($tanggal, $pelangganID, $paketID));
    $penjualanID = $conn->lastInsertId();

    $total = 0;

    if (!empty($_POST['ProdukID'])) {
        foreach ($_POST['ProdukID'] as $produkID => $v) {

            $jumlah = isset($_POST['JumlahProduk'][$produkID]) ? $_POST['JumlahProduk'][$produkID] : 0;
            $diskon = isset($_POST['Diskon'][$produkID]) ? $_POST['Diskon'][$produkID] : 0;
            if ($jumlah < 1) continue;

            $p = $conn->prepare("SELECT Harga, Stok FROM produk WHERE ProdukID=?");
            $p->execute(array($produkID));
            $produk = $p->fetch(PDO::FETCH_ASSOC);

            if ($jumlah > $produk['Stok']) die("Stok tidak cukup");

            $harga = $produk['Harga'] * $jumlah;
            $subtotal = $harga - ($harga * $diskon / 100);

            $conn->prepare(
                "INSERT INTO detailpenjualan
                (PenjualanID, ProdukID, JumlahProduk, Subtotal, Diskon)
                VALUES (?,?,?,?,?)"
            )->execute(array($penjualanID,$produkID,$jumlah,$subtotal,$diskon));

            $conn->prepare(
                "UPDATE produk SET Stok = Stok - ? WHERE ProdukID=?"
            )->execute(array($jumlah,$produkID));

            $total += $subtotal;
        }

        $conn->prepare(
            "UPDATE penjualan SET TotalHarga=? WHERE PenjualanID=?"
        )->execute(array($total,$penjualanID));

        $msg = "Transaksi berhasil disimpan";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Transaksi Penjualan</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
}

.wrapper{
    max-width:1200px;
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

label{
    font-size:12px;
    font-weight:600;
    color:#475569;
    margin-bottom:6px;
    display:block;
}

input,select{
    padding:10px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
    width:100%;
}

input:focus,select:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.toolbar{
    margin:18px 0;
}

.table-wrap{
    border:1px solid #e5eaf1;
    border-radius:14px;
    overflow:auto;
    max-height:420px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    position:sticky;
    top:0;
    background:#00AEEF;
    color:#fff;
    padding:10px;
    font-weight:600;
}

td{
    padding:8px;
    border-bottom:1px solid #eef2f7;
}

tr:hover{
    background:#f1faff;
}

td input[type=number]{
    width:70px;
    padding:6px;
    border-radius:8px;
}

.btn{
    margin-top:22px;
    padding:14px;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:14px;
    font-weight:600;
    cursor:pointer;
    width:100%;
}

.btn:hover{
    background:#0095cc;
}

.success{
    background:#e6f7ff;
    color:#0369a1;
    padding:12px;
    border-radius:12px;
    margin-bottom:15px;
}

@media(max-width:800px){
    .grid{grid-template-columns:1fr}
}
</style>
</head>

<body>
<div class="wrapper">
<div class="card">

<h3>Transaksi Penjualan</h3>

<?php if(!empty($msg)): ?>
<div class="success"><?php echo $msg; ?></div>
<?php endif; ?>

<form method="POST">

<div class="grid">
<div>
<label>Pelanggan</label>
<select name="PelangganID" id="pelanggan" required>
<option value="">Pilih pelanggan</option>
<?php foreach($pelangganList as $p){ ?>
<option value="<?php echo $p['PelangganID']; ?>">
<?php echo $p['NamaPelanggan']; ?>
</option>
<?php } ?>
</select>
</div>

<div>
<label>ID Paket (Opsional)</label>
<input type="text" name="PaketID">
</div>
</div>

<div class="toolbar">
<input type="text" id="searchProduk" placeholder="Cari produk...">
</div>

<div class="table-wrap">
<table id="produkTable">
<thead>
<tr>
<th></th>
<th>Produk</th>
<th>Stok</th>
<th>Harga</th>
<th>Qty</th>
<th>Disc %</th>
</tr>
</thead>
<tbody>
<?php foreach($produkList as $p){ ?>
<tr data-nama="<?php echo strtolower($p['NamaProduk']); ?>">
<td><input type="checkbox" name="ProdukID[<?php echo $p['ProdukID']; ?>]"></td>
<td><?php echo $p['NamaProduk']; ?></td>
<td><?php echo $p['Stok']; ?></td>
<td>Rp<?php echo number_format($p['Harga'],0,',','.'); ?></td>
<td><input type="number" name="JumlahProduk[<?php echo $p['ProdukID']; ?>]" min="1" max="<?php echo $p['Stok']; ?>" disabled></td>
<td><input type="number" name="Diskon[<?php echo $p['ProdukID']; ?>]" min="0" max="100" disabled></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<button class="btn">Simpan Transaksi</button>

</form>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$('#pelanggan').select2({placeholder:'Cari pelanggan...'});

document.getElementById('searchProduk').addEventListener('keyup',function(){
    var k=this.value.toLowerCase();
    document.querySelectorAll('#produkTable tbody tr').forEach(function(r){
        r.style.display=r.dataset.nama.indexOf(k)!==-1?'':'none';
    });
});

document.querySelectorAll('#produkTable input[type=checkbox]').forEach(function(cb){
    cb.addEventListener('change',function(){
        cb.closest('tr').querySelectorAll('input[type=number]').forEach(function(i){
            i.disabled=!cb.checked;
            if(!cb.checked)i.value='';
        });
    });
});
</script>

</body>
</html>
