<?php
session_start();

if (empty($_SESSION['nama'])) {
    header("Location: ../login.php");
    exit();
}

$nama  = $_SESSION['nama'];
$level = $_SESSION['level'];
$page  = isset($_GET['page']) ? $_GET['page'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:#f4f7fb;
    margin-left:260px;
}

/* ================= SIDEBAR ================= */
.sidebar{
    position:fixed;
    top:0;
    left:0;
    width:260px;
    height:100%;
    background:#ffffff;
    border-right:1px solid #e5e7eb;
    padding:22px 16px;
    overflow-y:auto;
}

/* Logo */
.sidebar-logo{
    display:flex;
    align-items:center;
    gap:10px;
    padding:14px;
    margin-bottom:25px;
    border-radius:14px;
    background:#f0f9ff;
}

.sidebar-logo img{
    width:36px;
    height:36px;
    border-radius:8px;
    background:#00AEEF;
}

.sidebar-logo .title{
    font-size:13px;
    font-weight:600;
    color:#0f172a;
    line-height:1.2;
}

/* Menu */
.sidebar-links{
    list-style:none;
}

.sidebar-links h4{
    font-size:11px;
    color:#9ca3af;
    margin:18px 8px 8px;
    letter-spacing:1px;
}

.menu-separator{
    height:1px;
    background:#e5e7eb;
    margin-bottom:10px;
}

/* Item */
.sidebar-links li a,
.dropdown-toggle{
    display:flex;
    align-items:center;
    gap:14px;
    padding:12px 14px;
    font-size:13px;
    color:#334155;
    text-decoration:none;
    border-radius:14px;
    transition:all .25s;
    cursor:pointer;
}

.sidebar-links li a span,
.dropdown-toggle span{
    font-size:20px;
    color:#64748b;
}

/* Hover */
.sidebar-links li a:hover,
.dropdown-toggle:hover{
    background:#e6f6fd;
    color:#00AEEF;
}

.sidebar-links li a:hover span,
.dropdown-toggle:hover span{
    color:#00AEEF;
}

/* Active */
.sidebar-links li a.active{
    background:#00AEEF;
    color:#fff;
}

.sidebar-links li a.active span{
    color:#fff;
}

/* Dropdown */
.dropdown input{
    display:none;
}

.dropdown-menu{
    display:none;
    flex-direction:column;
    gap:6px;
    padding-left:18px;
}

.dropdown-menu a{
    font-size:12px;
    padding:10px 14px;
    border-radius:12px;
}

.dropdown input:checked ~ .dropdown-menu{
    display:flex;
}

.dropdown-toggle::after{
    content:"›";
    margin-left:auto;
    color:#9ca3af;
    transition:.3s;
}

.dropdown input:checked + .dropdown-toggle::after{
    transform:rotate(90deg);
}

/* User */
.user-account{
    margin-top:30px;
    padding-top:15px;
    border-top:1px solid #e5e7eb;
}

.user-profile{
    display:flex;
    gap:10px;
    align-items:center;
}

.user-avatar{
    width:38px;
    height:38px;
    border-radius:12px;
    background:#00AEEF;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:600;
}

.user-detail h3{
    font-size:13px;
    color:#0f172a;
}

.user-detail span{
    font-size:11px;
    color:#64748b;
}

/* Content */
#contentArea{
    padding:30px;
}
</style>
</head>

<body>

<aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-logo">
        
        <div class="title">
            PT. SOERYA MEDIKA SENTRAL
        </div>
    </div>

    <ul class="sidebar-links">

        <h4>MAIN MENU</h4>
        <div class="menu-separator"></div>

        <li>
            <a href="admin_dashboard.php" class="<?= $page==''?'active':'' ?>">
                <span class="material-symbols-outlined">dashboard</span>Dashboard
            </a>
        </li>

        <li class="dropdown">
            <input type="checkbox" id="menu-entry">
            <label for="menu-entry" class="dropdown-toggle">
                <span class="material-symbols-outlined">inventory</span>Data Entry
            </label>
            <div class="dropdown-menu">
                <a href="?page=produk">Input Barang</a>
                <a href="?page=barangmasuk">Tambah Stok</a>
                <a href="?page=pelanggan">Input Outlet</a>
                <a href="?page=cre_kategori">Jenis Produk</a>
                <a href="?page=cre_cust">Customer Class</a>
            </div>
        </li>

        <li class="dropdown">
            <input type="checkbox" id="menu-transaksi">
            <label for="menu-transaksi" class="dropdown-toggle">
                <span class="material-symbols-outlined">publish</span>Transaksi
            </label>
            <div class="dropdown-menu">
                <a href="?page=cre_transaksi">Input Transaksi</a>
                <a href="?page=data_transaksi">Data Transaksi</a>
            </div>
        </li>

        <li class="dropdown">
            <input type="checkbox" id="menu-data">
            <label for="menu-data" class="dropdown-toggle">
                <span class="material-symbols-outlined">description</span>Data
            </label>
            <div class="dropdown-menu">
                <a href="?page=data_pelanggan">Data Outlet</a>
                <a href="?page=data_produk">Data Barang</a>
                <a href="?page=rea_barangmasuk">Barang Masuk</a>
                <a href="?page=data_kategori">Kategori</a>
            </div>
        </li>

        <li>
            <a href="?page=laporan">
                <span class="material-symbols-outlined">assessment</span>Laporan Penjualan
            </a>
        </li>

        <h4>ACCOUNT</h4>
        <div class="menu-separator"></div>

        <li>
            <a href="?page=petugas">
                <span class="material-symbols-outlined">admin_panel_settings</span>Data Petugas
            </a>
        </li>

        <li>
            <a href="logout.php">
                <span class="material-symbols-outlined">logout</span>Logout
            </a>
        </li>
    </ul>

    <div class="user-account">
        <div class="user-profile">
            <div class="user-avatar"><?= strtoupper(substr($nama,0,1)) ?></div>
            <div class="user-detail">
                <h3><?= htmlspecialchars($nama) ?></h3>
                <span><?= htmlspecialchars($level) ?></span>
            </div>
        </div>
    </div>

</aside>

<div id="contentArea">
<?php
switch($page){
    case 'produk': include "cre_produk.php"; break;
    case 'pelanggan': include "cre_pelanggan.php"; break;
    case 'data_produk': include "rea_produk.php"; break;
    case 'data_pelanggan': include "rea_pelanggan.php"; break;
    case 'cre_transaksi': include "cre_transaksi.php"; break;
    case 'data_transaksi': include "rea_transaksi.php"; break;
    case 'laporan': include "lap_penjualan.php"; break;
    case 'petugas': include "cre_petugas.php"; break;
    case 'cre_kategori': include "cre_kategori.php"; break;
    case 'cre_cust': include "cre_custclass.php"; break;
    case 'barangmasuk': include "barang_masuk.php"; break;
    case 'rea_barangmasuk': include "rea_barang_masuk.php"; break;
    case 'data_kategori': include "rea_kategori.php"; break;
    default: include "default.php"; break;
}
?>
</div>

</body>
</html>
