
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
  <title>Dashboard - SMK Bina Citra</title>
  
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
<li><a href="#" id="InputPelanggan"><span class="material-symbols-outlined">person_add</span>Input Pelanggan</a></li>
<li><a href="#" id="DataPelanggan"><span class="material-symbols-outlined">dashboard</span>Data Pelanggan</a></li>
<li><a href="#" id="InputProduk"><span class="material-symbols-outlined">inventory</span>Input Produk</a></li>
<li><a href="#" id="DataProduk"><span class="material-symbols-outlined">list</span>Data Produk</a></li>
<li><a href="#" id="InputTransaksi"><span class="material-symbols-outlined">shopping_cart</span>Input Transaksi</a></li>
<li><a href="#" id="DataTransaksi"><span class="material-symbols-outlined">receipt</span>Data Transaksi</a></li>
<li><a href="#" id="LaporanPenjualan"><span class="material-symbols-outlined">assessment</span>Laporan Penjualan</a></li>
<li><a href="#" id="DataPetugas"><span class="material-symbols-outlined">admin_panel_settings</span>Data Petugas</a></li>

<!-- Account Section -->
<h4>
<span>Account</span>
<div class="menu-separator"></div>
</h4>
<li><a href="#" id="LogoutButton"><span class="material-symbols-outlined">logout</span>Logout</a></li>
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
<p class="intro">Selamat datang <?php echo $_SESSION['nama']; ?>. Selamat Bekerja !!</p>

<div id="messageArea"></div>
</div>

<!-- JavaScript -->
<script src="script.js"></script>
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // Ambil elemen yang jadi tempat tampilan konten dinamis
  const contentArea = document.getElementById("contentArea");

  // ========================
  // Fungsi untuk memuat konten secara otomatis tanpa reload
  // ========================
  const loadContent = async (url) => {
    try {
      // Ambil data dari URL (file PHP)
      const response = await fetch(url);

      // Cek kalau responsenya gak berhasil (error)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      // Ubah response menjadi HTML
      const html = await response.text();
      contentArea.innerHTML = html; // Tampilkan hasilnya di #contentArea
      handleFormSubmission(); // Panggil fungsi form biar bisa ditangkap
    } catch (error) {
      contentArea.innerHTML = `<p class="text-danger">Gagal memuat konten: ${error.message}</p>`;
    }
  };

  // =========================
  // Fungsi untuk menangani submit form
  // =========================
  const handleFormSubmission = () => {
    // Ambil semua form yang ada di halaman dinamis
    const forms = contentArea.querySelectorAll("form");

    // Looping setiap form biar bisa dikasih event submit
    forms.forEach((form) => {
      form.addEventListener("submit", async (event) => {
        event.preventDefault(); // Stop refresh otomatis

        const formData = new FormData(form); // Ambil data dari input form
        const actionUrl = form.action; // Ambil URL tujuan dari action di form

        try {
          // Kirim data ke server pakai metode POST
          const response = await fetch(actionUrl, {
            method: "POST",
            body: formData,
          });

          const result = await response.text(); // Ambil hasil respon
          document.getElementById("messageArea").innerHTML = result; // Tampilkan pesan sukses/gagal

          // Auto reload tabel produk
          if (actionUrl.includes("cre_produk.php")) {
            loadContent("rea_produk.php");
          }
          // Auto reload tabel transaksi
          else if (actionUrl.includes("cre_pelanggan.php")) {
            loadContent("rea_pelanggan.php");
          }

        } catch (error) {
          // Kalau gagal kirim, kasih pesan error
          document.getElementById("messageArea").innerHTML = `<p class="text-danger">Gagal mengirim data: ${error.message}</p>`;
        }
      });
    });
  };

  // =========================
  // Event Klik Sidebar (Menu Klik Buka Halaman)
  // =========================
  document.getElementById("InputPelanggan")?.addEventListener("click", () => loadContent("cre_pelanggan.php"));
  document.getElementById("DataPelanggan")?.addEventListener("click", () => loadContent("rea_pelanggan.php"));
  document.getElementById("InputProduk")?.addEventListener("click", () => loadContent("cre_produk.php"));
  document.getElementById("DataProduk")?.addEventListener("click", () => loadContent("rea_produk.php"));
  document.getElementById("InputTransaksi")?.addEventListener("click", () => loadContent("modul/transaksi.php"));
  document.getElementById("DataTransaksi")?.addEventListener("click", () => loadContent("modul/data_transaksi.php"));
  document.getElementById("LaporanPenjualan")?.addEventListener("click", () => loadContent("modul/laporan.php"));
  document.getElementById("DataPetugas")?.addEventListener("click", () => loadContent("cre_petugas.php"));

  // =========================
  // Fungsi Tombol Logout
  // =========================
  document.getElementById("LogoutButton")?.addEventListener("click", () => {
    if (confirm("Apakah Anda yakin ingin logout?")) {
      window.location.href = "logout.php"; // Arahkan ke halaman logout
    }
  });
});
</script>


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