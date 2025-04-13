<?php
include '../lib/koneksi.php';

// Ambil daftar transaksi
$sql = "SELECT p.PenjualanID, p.TanggalPenjualan, pl.NamaPelanggan, p.TotalHarga 
        FROM penjualan p
        JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
        ORDER BY p.TanggalPenjualan DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$transaksiList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container">
        <h2>Daftar Transaksi</h2>
       
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total Harga</th>
                    <th>Detail</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transaksiList as $transaksi) { ?>
                    <tr>
                        <td><?php echo $transaksi['PenjualanID']; ?></td>
                        <td><?php echo $transaksi['TanggalPenjualan']; ?></td>
                        <td><?php echo $transaksi['NamaPelanggan']; ?></td>
                        <td>Rp. <?php echo number_format($transaksi['TotalHarga'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="detail_transaksi.php?id=<?php echo $transaksi['PenjualanID']; ?>">Lihat Detail</a>
                        </td>
                        <td>
                        

    <a href="del_transaksi.php?PenjualanID=<?php echo urlencode($transaksi['PenjualanID']); ?>" 
       class="btn-custom" 
       onclick="return confirm('Yakin ingin menghapus transaksi ini?');">Hapus</a>
</td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

<style>
    body {
        background-color: #f4f4f9;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        margin: 0;
        color: #333;
    }

    .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 1000px;
    }

    h2 {
        text-align: center;
        color: #444;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: white;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

th {
    background-color: #20263f;
    color: white;
    font-weight: 700;
}

td {
    background-color: #f9f9f9;
}

tr:nth-child(even) td {
    background-color: #eef;
}

.btn-custom {
    text-decoration: none;
    padding: 10px 14px;
    border-radius: 6px;
    display: inline-block;
    margin: 4px;
    background-color: #20263f;
    color: white;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-custom:hover {
    background-color: #37406b;
}
</style>
