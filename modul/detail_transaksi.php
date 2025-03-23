<?php
include '../lib/koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$penjualanID = $_GET['id'];

// Ambil data transaksi utama
$sql = "SELECT p.PenjualanID, p.TanggalPenjualan, pl.NamaPelanggan, p.TotalHarga
        FROM penjualan p
        JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
        WHERE p.PenjualanID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$penjualanID]);
$transaksi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Ambil detail produk dalam transaksi ini
$sql = "SELECT dp.JumlahProduk, dp.Subtotal, pr.NamaProduk, pr.Harga
        FROM detailpenjualan dp
        JOIN produk pr ON dp.ProdukID = pr.ProdukID
        WHERE dp.PenjualanID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$penjualanID]);
$detailProduk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container">
        <img class="struk" src="../asset/img/logofunmo.png" alt="">
        <p><strong>ID Transaksi:</strong> <?php echo $transaksi['PenjualanID']; ?></p>
        <p><strong>Tanggal:</strong> <?php echo $transaksi['TanggalPenjualan']; ?></p>
        <p><strong>Pelanggan:</strong> <?php echo $transaksi['NamaPelanggan']; ?></p>
        <p><strong>Total Harga:</strong> Rp<?php echo number_format($transaksi['TotalHarga'], 0, ',', '.'); ?></p>

        <h3>Produk yang Dibeli</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detailProduk as $produk) { ?>
                    <tr>
                        <td><?php echo $produk['NamaProduk']; ?></td>
                        <td>Rp<?php echo number_format($produk['Harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $produk['JumlahProduk']; ?></td>
                        <td>Rp<?php echo number_format($produk['Subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        
    </div>
</body>

<style>
    body {
    background-color: #f4f4f9;
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    color: #333;
    
}

.container {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 700px;
    
}

h2 {
    color: #444;
    margin-bottom: 10px;
}

p {
    font-size: 14px;
    margin: 5px 0;
}

h3 {
    margin-top: 20px;
    font-size: 16px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 14px;
}

th, td {
    border-bottom: 1px dashed #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #161a2d;
    color: white;
    text-align: center;
}

td {
    text-align: center;
}

tr:last-child td {
    border-bottom: none;
    font-weight: bold;
}

.back-btn {
    display: block;
    margin-top: 15px;
    padding: 10px;
    background-color: #161a2d;
    color: white;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
}

.back-btn:hover {
    background-color: #0056b3;
}

.struk {
    width: 140px;
    display: block;
    margin: 10px auto;
}
</style>
