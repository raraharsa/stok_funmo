<!-- <?php
session_start();
include '../lib/koneksi.php'; 

try {
    // Ambil data terbaru dari database
    $stmt = $conn->prepare("SELECT 
        COUNT(ProdukID) AS total_produk, 
        COALESCE(SUM(Stok), 0) AS total_stok 
        FROM produk");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_produk = $data['total_produk'];
    $total_stok = $data['total_stok'];

    // Ambil total barang keluar
    $stmt = $conn->prepare("SELECT COALESCE(SUM(JumlahProduk), 0) AS total_barang_keluar FROM detailpenjualan");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_barang_keluar = $data['total_barang_keluar'];

    // Ambil jumlah transaksi penjualan
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT PenjualanID) AS jumlah_penjualan FROM detailpenjualan");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlah_penjualan = $data['jumlah_penjualan'];

} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

// Ambil data produk
$sql = "SELECT * FROM produk ORDER BY ProdukID DESC"; // Memastikan data diurutkan dengan benar
$stmt = $conn->prepare($sql);
$stmt->execute();
$pel = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query dalam variabel siswa
?>
 -->



  <p class="intro">Selamat datang <?php echo $_SESSION['nama']; ?>. Selamat Bekerja !!</p>

  <!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: ;
        }
        .dashboard {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }
        .tks {
            font-size: 23px;
            font-weight: 50px; 
            font-family: 'Poppins', sans-serif;
            margin-left: 29px;
            margin-top: 26px;
            margin-bottom: 18px;
            color: grey;
        }
        .card {
            background: white;
            padding: 20px;
            width: 200px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card h6 {
            background: #161a2d;
            color: white;
            padding: 10px;
            margin: -20px -20px 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 14px;
            font-weight: 50px;
        }
        .card p {
            font-size: 40px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        
        .card hr {
            padding: 0;
            margin: 0;
        }
        
        .card1 {
            background: white;
            padding: 20px;
            width: 90%px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: 120px;
            margin-right: 120px;
            margin-top: 50px;
        }
        .card1 h6 {
            background: #161a2d;
            color: white;
            padding: 10px;
            margin: -20px -20px 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 14px;
            font-weight: 50px;
        }
        .card1 p {
            font-size: 40px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        
        .card1 hr {
            padding: 0;
            margin: 0;
        }
        table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Poppins', sans-serif;
    background-color: #f9f9f9;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color:  #161a2d;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
    transition: 0.3s;
}

td {
    font-size: 14px;
}

th {
    font-size: 16px;
}

    </style>
</head>
<body>
    <p class="tks">Dashboard</p>
    <div class="dashboard">
        <div class="card">
            <h6>Nama Barang</h6>
            <p><?php echo $total_produk; ?></p>
            
        </div>
        <div class="card">
            <h6>Stok Barang</h6>
            <p><?php echo $total_stok; ?></p>
            
        </div>
        <div class="card">
            <h6>Barang Keluar</h6>
            <p><?php echo  $total_barang_keluar; ?></p>
            
            
        </div>
        <div class="card">
            <h6>Transaksi</h6>
            <p><?php echo  $jumlah_penjualan; ?></p>
            
            
        </div>
    </div>

    
    <div class="card1">
            <h6>Detail Stok Barang</h6>
            <table>
    <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        
        <th>Stok Produk</th>
        
    </tr>
    <?php
                    $no = 1;
                    foreach ($pel as $row) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['NamaProduk']; ?></td>
                            
                            <td><?php echo $row['Stok']; ?></td>

                            
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
</table>
            
            
        </div>
    
</body>
</html> -->
