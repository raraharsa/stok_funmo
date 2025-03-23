
<?php
session_start(); // Mulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil username dari sesi
$nama = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Admin funmo</title>
  
  <!-- Google Font & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <img src="../asset/img/logofunmo2.png" alt="Logo SMK Bina Citra" style="width: 70%; margin-left: 0px;"  />
      
    </div>

    <ul class="sidebar-links">
      <!-- Main Menu Section -->
      <h4>
      <span>Main Menu</span>
<div class="menu-separator"></div>
</h4>
<li><a href="admin_dashboard.php" ><span class="material-symbols-outlined">dashboard</span>Dashboard</a></li>
<li><a href="?page=pelanggan" ><span class="material-symbols-outlined">person_add</span>Input Pelanggan</a></li>
<li><a href="?page=data_pelanggan" id="DataPelanggan"><span class="material-symbols-outlined">description</span>Data Pelanggan</a></li>
<li><a href="?page=produk" id="InputProduk"><span class="material-symbols-outlined">inventory</span>Input Produk</a></li>
<li><a href="?page=data_produk" id="DataProduk"><span class="material-symbols-outlined">list</span>Data Produk</a></li>

<li><a href="?page=data_transaksi" id="DataTransaksi"><span class="material-symbols-outlined">receipt</span>Data Transaksi</a></li>
<li><a href="?page=laporan" id="LaporanPenjualan"><span class="material-symbols-outlined">assessment</span>Laporan Penjualan</a></li>
<li><a href="?page=petugas" id="DataPetugas"><span class="material-symbols-outlined">admin_panel_settings</span>Data Petugas</a></li>

<!-- Account Section -->
<h4>
<span>Account</span>
<div class="menu-separator"></div>
</h4>
<li><a href="?page=logout" id="LogoutButton"><span class="material-symbols-outlined">logout</span>Logout</a></li>
</ul>

<!-- User Profile Section -->
<div class="user-account">
<div class="user-profile">
<div class="user-detail">
<h3><?php echo $_SESSION['nama']; ?></h3>
<span><?php echo $_SESSION['level']; ?></span>
</div>
</div>
</div>
</aside>

<!-- Content Area -->
<div id="contentArea">
<!-- Konten dinamis akan dimuat di sini -->



<?php
    $page = $_GET['page'];

    switch ($page) {
        case 'pelanggan':
            include "cre_pelanggan.php";
            break;
        case 'produk':
            include "cre_produk.php";
            break;
        case 'data_pelanggan':
            include "rea_pelanggan.php";
            break;
        case 'data_produk':
            include "rea_produk.php";
            break;
        
        case 'data_transaksi':
            include "rea_transaksi.php";
            break;
        case 'laporan':
            include "lap_penjualan.php";
            break;
        case 'petugas':
            include "cre_petugas.php";
            break;
        case 'logout':
            echo "<script>
                    if(confirm('Apakah Anda yakin ingin logout?')) {
                        window.location.href = 'logout.php';
                    }
                  </script>";
            break;
        default:
            include "default.php";
            break;
    }
?>


</body>
</html>





<style>

 /* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", sans-serif;
  background: #f0f4ff;
  margin-left: 260px; /* Sesuaikan dengan lebar sidebar tetap */
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 260px; /* Sidebar dengan lebar tetap */
  background: #161a2d;
  color: #fff;
  padding: 25px 20px;
  overflow-y: auto; /* Tambahkan scroll */
}

/* Custom Scrollbar */
.sidebar::-webkit-scrollbar {
  width: 8px; /* Lebar scrollbar */
}

.sidebar::-webkit-scrollbar-track {
  background: #20263f; /* Warna track scrollbar */
  border-radius: 4px; /* Sudut membulat */
}

.sidebar::-webkit-scrollbar-thumb {
  background: #4f52ba; /* Warna thumb scrollbar */
  border-radius: 4px; /* Sudut membulat */
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background: #6b6fbf; /* Warna thumb saat di-hover */
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 15px;
}

.sidebar-header img {
  width: 42px;
}

.sidebar-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  white-space: nowrap;
}

.sidebar-links h4 {
  margin: 20px 0 10px;
  color: #fff;
  font-size: 0.9rem;
}

.sidebar-links .menu-separator {
  height: 1px;
  background: #4f52ba;
  margin: 5px 0;
}

.sidebar-links {
  list-style: none;
  margin-top: 20px;
}

.sidebar-links li a {
  display: flex;
  align-items: center;
  padding: 10px;
  color: #fff;
  gap: 15px;
  font-size: 0.9rem;
  text-decoration: none;
  border-radius: 4px;
  transition: all 0.3s;
}

.sidebar-links li a:hover {
  background: #fff;
  color: #161a2d;
}

.user-account {
  margin-top: auto;
  padding-top: 20px;
}

.user-profile {
  display: flex;
  align-items: center;
}

.user-detail h3 {
  font-size: 1rem;
  font-weight: 600;
}

.user-detail span {
  font-size: 0.8rem;
  color: #bbb;
}
.intro {
  font-size: 20px;
  text-align: center;
  margin-top: 280px;
}

</style>