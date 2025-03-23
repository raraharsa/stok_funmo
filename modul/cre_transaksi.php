<?php
include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu yang kamu pakai

// Ambil daftar produk dari database
$sql = "SELECT * FROM produk ORDER BY NamaProduk ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$produkList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil daftar pelanggan dari database
$sql = "SELECT PelangganID, NamaPelanggan FROM pelanggan ORDER BY NamaPelanggan ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$pelangganList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pelangganID = $_POST['PelangganID'];
    $tanggalPenjualan = date('Y-m-d');

    // Cek apakah PelangganID ada di database
    $cekPelanggan = $conn->prepare("SELECT * FROM pelanggan WHERE PelangganID = ?");
    $cekPelanggan->execute([$pelangganID]);
    
    if ($cekPelanggan->rowCount() == 0) {
        die("Error: PelangganID tidak ditemukan di database.");
    }

    // Simpan data ke tabel penjualan
    $sql = "INSERT INTO penjualan (TanggalPenjualan, PelangganID, TotalHarga) VALUES (?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$tanggalPenjualan, $pelangganID]);
    
    // Ambil ID transaksi yang baru saja dibuat
    $penjualanID = $conn->lastInsertId();
    
    $totalHarga = 0;
    
    if (!empty($_POST['ProdukID'])) {
        foreach ($_POST['ProdukID'] as $index => $produkID) {
            if (!isset($_POST['JumlahProduk'][$index]) || $_POST['JumlahProduk'][$index] < 1) {
                continue;
            }
            $jumlah = $_POST['JumlahProduk'][$index];
            
            // Ambil harga produk
            $sql = "SELECT Harga, Stok FROM produk WHERE ProdukID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$produkID]);
            $produk = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$produk) {
                die("Produk dengan ID $produkID tidak ditemukan.");
            }
            
            if ($jumlah > $produk['Stok']) {
                die("Error: Stok tidak mencukupi untuk produk dengan ID $produkID.");
            }
            
            $subtotal = $produk['Harga'] * $jumlah;
            
            // Simpan ke tabel detailpenjualan
            $sql = "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$penjualanID, $produkID, $jumlah, $subtotal]);
            
            // Kurangi stok produk
            $sql = "UPDATE produk SET Stok = Stok - ? WHERE ProdukID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$jumlah, $produkID]);
            
            $totalHarga += $subtotal;
        }
        
        // Update total harga di tabel penjualan
        $sql = "UPDATE penjualan SET TotalHarga = ? WHERE PenjualanID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$totalHarga, $penjualanID]);
    } else {
        die("Error: Tidak ada produk yang dipilih.");
    }
    
    echo '<div class="success-message"> Transaksi berhasil!</div>';
}

?>


<body>
    <div class="container">
        <h2>Form Transaksi</h2>
        <form method="POST">
            <label for="PelangganID">Pelanggan:</label>
            <select name="PelangganID" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php foreach ($pelangganList as $pelanggan) { ?>
                    <option value="<?php echo $pelanggan['PelangganID']; ?>">
                        <?php echo $pelanggan['NamaPelanggan']; ?>
                    </option>
                <?php } ?>
            </select>
            
            <h3>Pilih Produk</h3>
            <div id="produk-container">
                <?php foreach ($produkList as $produk) { ?>
                    <div>
                        <label>
                            <input type="checkbox" name="ProdukID[<?php echo $produk['ProdukID']; ?>]" value="<?php echo $produk['ProdukID']; ?>">
                            <?php echo $produk['NamaProduk']; ?> - Rp. <?php echo number_format($produk['Harga'], 0, ',', '.'); ?>


                        </label>
                        <input type="number" name="JumlahProduk[<?php echo $produk['ProdukID']; ?>]" min="1" max="<?php echo $produk['Stok']; ?>" class="form-control" placeholder="Jumlah (Maks: <?php echo $produk['Stok']; ?>)">

                    
                    </div>
                <?php } ?>
            </div>
            
            <button type="submit">Simpan Transaksi</button>
        </form>
    </div>
</body>
<style>
    body {
    background-color: #f4f4f9;
    font-family: Arial, sans-serif;
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
    width: 900px;
}

h2 {
    text-align: center;
    color: #444;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
}

select, .form-control {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}

button {
    background-color: #161a2d;
    color: white;
    padding: 10px;
    width: 100%;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
}

button:hover {
    background-color: #0056b3;
}

#produk-container {
    margin-top: 15px;
}

#produk-container div {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px;
    background: #f8f8f8;
    border-radius: 5px;
    margin-top: 5px;
}

input[type="number"] {
    width: 70px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #c3e6cb;
    text-align: center;
    margin-top: 10px;
    font-weight: bold;
}

</style>