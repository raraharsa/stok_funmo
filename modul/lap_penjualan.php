<?php
include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu yang kamu pakai
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

$sql = "SELECT p.TanggalPenjualan, COUNT(p.PenjualanID) AS JumlahTransaksi, SUM(p.TotalHarga) AS TotalPenjualan
        FROM penjualan p
        WHERE p.TanggalPenjualan BETWEEN ? AND ?
        GROUP BY p.TanggalPenjualan
        ORDER BY p.TanggalPenjualan ASC";

$stmt = $conn->prepare($sql);
$stmt->execute([$startDate, $endDate]);
$laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container">
        <h2>Laporan Penjualan</h2>
        
        <form method="GET">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" name="start_date" value="<?php echo $startDate; ?>">
            
            <label for="end_date">Sampai Tanggal:</label>
            <input type="date" name="end_date" value="<?php echo $endDate; ?>">
            
            <button type="submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laporan as $data) { ?>
                    <tr>
                        <td><?php echo $data['TanggalPenjualan']; ?></td>
                        <td><?php echo $data['JumlahTransaksi']; ?></td>
                        <td>Rp<?php echo number_format($data['TotalPenjualan'], 0, ',', '.'); ?></td>
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

    form {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        margin-top: 20px;
    }

    input, button {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
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
