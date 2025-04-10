<?php
include '../lib/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$sql = "SELECT DATE(p.TanggalPenjualan) as TanggalPenjualan, 
               COUNT(p.PenjualanID) AS JumlahTransaksi, 
               SUM(p.TotalHarga) AS TotalPenjualan
        FROM penjualan p
        GROUP BY DATE(p.TanggalPenjualan)
        ORDER BY DATE(p.TanggalPenjualan) ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
            justify-content: center;
            gap: 10px;
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
</head>
<body>
    <div class="container">
        <h2>Laporan Penjualan</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan)) {
                    foreach ($laporan as $data) { ?>
                        <tr>
                            <td><?php echo $data['TanggalPenjualan']; ?></td>
                            <td><?php echo $data['JumlahTransaksi']; ?></td>
                            <td>Rp<?php echo number_format($data['TotalPenjualan'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">Tidak ada data penjualan.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>



