<?php
// koneksi.php berisi kode untuk menghubungkan dengan database
include '../lib/koneksi.php';

// Ambil data siswa
$sql = "SELECT * FROM pelanggan ORDER BY PelangganID DESC"; // Memastikan data diurutkan dengan benar
$stmt = $conn->prepare($sql);
$stmt->execute();
$pel = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query dalam variabel siswa
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
    
</head>
<body>

<h2>Data Pelanggan</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Nomor Telepon</th>
        
    </tr>
    <?php
                    $no = 1;
                    foreach ($pel as $row) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['NamaPelanggan']; ?></td>
                            <td><?php echo $row['Alamat']; ?></td>
                            <td><?php echo $row['NomorTelepon']; ?></td>

                           
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
</table>

</body>
</html>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f2f5;
    color: #333;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #20263f;
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 20px;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    gap: 20px;
    max-width: 1200px;
    margin: auto;
}

.form-container {
    width: 400px;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
}

label {
    font-weight: 600;
    display: block;
    margin-top: 12px;
    color: #20263f;
    font-size: 14px;
}

input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

input:focus, select:focus {
    border-color: #20263f;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    border: none;
    border-radius: 6px;
    background-color: #20263f;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover {
    background-color: #37406b;
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