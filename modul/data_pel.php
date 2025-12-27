<?php

include '../lib/koneksi.php';

// Ambil data pelanggan dengan pencarian
$search = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = trim($_POST['search']);
    $sql = "SELECT * FROM pelanggan WHERE NamaPelanggan LIKE :search ORDER BY PelangganID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
} else {
    $sql = "SELECT * FROM pelanggan ORDER BY PelangganID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$pel = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<!-- Form Pencarian -->
<form method="POST">
    <input type="text" name="search" placeholder="Cari pelanggan..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Cari</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Customer Class</th>
        <th>Alamat</th>
        <th>Nomor Telepon</th>
    </tr>
    <?php
    if (!empty($pel)) {
        $no = 1;
        foreach ($pel as $row) { ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $row['NamaPelanggan']; ?></td>
                <td><?php echo $row['cust_class_code']; ?></td>
                <td><?php echo $row['Alamat']; ?></td>
                <td><?php echo $row['NomorTelepon']; ?></td>
            </tr>
        <?php
            $no++;
        }
    } else {
        echo "<tr><td colspan='4' style='text-align:center;'>Data tidak ditemukan</td></tr>";
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

/* Form Pencarian */
form {
    text-align: center;
    margin-bottom: 20px;
}

input[type="text"] {
    padding: 10px;
    width: 250px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

input[type="text"]:focus {
    border-color: #20263f;
    outline: none;
}

button {
    padding: 10px 14px;
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
</style>
