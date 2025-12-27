<?php
include '../lib/koneksi.php';

/* ===== AMBIL TRANSAKSI ===== */
$sql = "SELECT p.PenjualanID, p.TanggalPenjualan, pl.NamaPelanggan, p.TotalHarga
        FROM penjualan p
        JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
        ORDER BY p.TanggalPenjualan DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$transaksiList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Transaksi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    padding:30px;
    font-size:13px;
    color:#334155;
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
    margin-bottom:18px;
    color:#0f172a;
    border-left:4px solid #00AEEF;
    padding-left:12px;
}

/* ===== TOOLBAR ===== */
.toolbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:14px;
    gap:12px;
}

.toolbar input{
    padding:9px 12px;
    border-radius:10px;
    border:1px solid #dbe2ea;
    font-size:13px;
    width:260px;
}

.toolbar input:focus{
    outline:none;
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
}

.btn{
    padding:10px 14px;
    background:#00AEEF;
    color:#fff;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    font-size:13px;
    transition:.25s;
    white-space:nowrap;
}

.btn:hover{
    background:#0095cc;
}

/* ===== TABLE ===== */
.table-wrap{
    border:1px solid #e5eaf1;
    border-radius:14px;
    overflow:auto;
    max-height:460px;
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
    text-align:left;
    font-weight:600;
    font-size:13px;
}

td{
    padding:9px;
    border-bottom:1px solid #eef2f7;
    font-size:13px;
    white-space:nowrap;
}

tr:hover{
    background:#f1faff;
}

/* ===== AKSI BUTTON ===== */
.action-btn{
    display:inline-block;
    padding:6px 10px;
    border-radius:8px;
    background:#00AEEF;
    color:#fff;
    text-decoration:none;
    font-size:12px;
    font-weight:500;
    margin:2px;
}

.action-btn:hover{
    background:#0095cc;
}

.action-danger{
    background:#ef4444;
}

.action-danger:hover{
    background:#dc2626;
}

@media(max-width:800px){
    .toolbar{
        flex-direction:column;
        align-items:flex-start;
    }
    .toolbar input{
        width:100%;
    }
}
</style>
</head>

<body>

<div class="wrapper">
<div class="card">

<h3>Daftar Transaksi</h3>

<div class="toolbar">
<input type="text" id="searchTransaksi" placeholder="Cari ID / pelanggan...">
<a href="export_excel2.php" class="btn">Export Excel</a>
</div>

<div class="table-wrap">
<table id="transaksiTable">
<thead>
<tr>
<th>ID</th>
<th>Tanggal</th>
<th>Pelanggan</th>
<th>Total</th>
<th>Detail</th>
<th>Invoice</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php foreach($transaksiList as $t){ ?>
<tr data-search="<?= strtolower($t['PenjualanID'].' '.$t['NamaPelanggan']); ?>">
<td><?= $t['PenjualanID']; ?></td>
<td><?= $t['TanggalPenjualan']; ?></td>
<td><?= $t['NamaPelanggan']; ?></td>
<td>Rp<?= number_format($t['TotalHarga'],0,',','.'); ?></td>

<td>
<a href="detail_transaksi.php?id=<?= $t['PenjualanID']; ?>" class="action-btn">Detail</a>
</td>

<td>
<a href="faktur.php?id=<?= $t['PenjualanID']; ?>" class="action-btn">Invoice</a>
</td>

<td>
<a href="cetak_spb.php?id=<?= $t['PenjualanID']; ?>" class="action-btn">SPB</a>
<a href="del_transaksi.php?PenjualanID=<?= urlencode($t['PenjualanID']); ?>"
   class="action-btn action-danger"
   onclick="return confirm('Yakin ingin menghapus transaksi ini?');">
   Hapus
</a>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

</div>
</div>

<script>
document.getElementById('searchTransaksi').addEventListener('keyup',function(){
    var key = this.value.toLowerCase();
    document.querySelectorAll('#transaksiTable tbody tr').forEach(function(row){
        row.style.display = row.dataset.search.indexOf(key) !== -1 ? '' : 'none';
    });
});
</script>

</body>
</html>
