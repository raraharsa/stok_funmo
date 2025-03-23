<?php
include '../lib/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    try {
        // Query insert data dengan PDO
        $query = "INSERT INTO user (nama, email, password, level) VALUES (:nama, :email, :password, :level)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':level', $level);

        if ($stmt->execute()) {
            echo "<script>alert('Data Petugas berhasil di tambahkan !'); window.location.href='admin_dashboard.php?page=petugas';</script>";
            // header("Location: admin_dashboard.php"); // Redirect setelah simpan
           exit; // Stop eksekusi biar nggak jalan terus
        } else {
            echo "<script>alert('Gagal menambahkan petugas!');window.location='cre_petugas.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Ambil data siswa
$sql = "SELECT * FROM user ORDER BY UserID DESC"; // Memastikan data diurutkan dengan benar
$stmt = $conn->prepare($sql);
$stmt->execute();
$pel = $stmt->fetchAll(PDO::FETCH_ASSOC); // Menyimpan hasil query dalam variabel siswa
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas</title>
    
</head>
<body>
<div class="container ">
  <div class="row align-items-start">
    <div class="col">
    <div >
    <h2>Tambah Petugas</h2>
    <form action="cre_petugas.php" method="POST" id="formPetugas">
        <div class="mb-3">
            <label for="nama" >Nama Petugas</label>
            <input type="text"  name="nama" required>
        </div>
        <div class="mb-3">
            <label for="email" >Email</label>
            <input type="email"  name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" >Password</label>
            <input type="password"  name="password" required>
        </div>
        <div class="mb-3">
            <label for="level" >Level</label>
            <select  name="level" required>
                <option value="petugas">Petugas</option>
                
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        
    </form>
</div>
    </div>
    <div class="col">
    <h2>Data Petugas</h2>

<table class="table-bordered">
    <tr>
        <th>ID</th>
        <th>Nama Petugas</th>
        <th>Email Petugas</th>
        <th>Password</th>
        <th>Aksi</th>
    </tr>
    <?php
                    $no = 1;
                    foreach ($pel as $row) { ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['password']; ?></td>

                            <td>
                                <a href="upd_petugas.php?id=<?php echo $row['UserID']; ?>" class="btn-custom">Edit</a>
                                <a href="del_petugas.php?id=<?php echo $row['UserID']; ?>" class="btn-custom">Hapus</a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
</table>
    </div>
  </div>
</div>


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
    margin-top: 40px;
}

.container {
    display: flex;
    justify-content: center;
    gap: 20px;
    max-width: 100%;
    margin: auto;
}

.form-container, .table-container {
    flex: 1;
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