# 📦 Stok Funmo

Website stok barang untuk keperluan uji kompetensi. Proyek ini bertujuan untuk membantu pencatatan barang masuk dan keluar dengan antarmuka yang sederhana dan mudah digunakan.

## ✨ Fitur Utama

- Login (admin & petugas)
- Tambah/Edit/Hapus Data Barang
- Barang Masuk & Barang Keluar
- Laporan Stok
- Detail Penjualan

## 🛠️ Teknologi yang Digunakan

- PHP
- MySQL
- Bootstrap

## 🚀 Cara Instalasi

Berikut langkah-langkah untuk menjalankan proyek **Stok Funmo** secara lokal:

1. **Pastikan AppServ sudah terpasang di komputer.**  
   Kalau belum, bisa diunduh di [https://www.appserv.org](https://www.appserv.org).

2. **Clone atau download project ini dari GitHub.**  
   Jika menggunakan Git:

   ```bash
   git clone https://github.com/raraharsa/stok_funmo.git
   ```

   Atau bisa juga langsung download file ZIP dari repositori GitHub dan ekstrak.

3. **Pindahkan folder project ke direktori `www` milik AppServ.**  
   Biasanya ada di:

   ```
   C:\AppServ\www\
   ```

   Sehingga menjadi:

   ```
   C:\AppServ\www\stok_funmo
   ```

4. **Import file database ke MySQL.**

   - Buka `phpMyAdmin` melalui browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Buat database baru, misalnya: `stok_funmo`
   - Klik database tersebut, lalu pilih tab **Import**
   - Pilih file SQL yang ada di dalam folder `stok_funmo` ( `kasir.sql`) dan klik **Go**

5. **Sesuaikan konfigurasi koneksi database jika diperlukan.**  
   Buka file `koneksi.php` pastikan nama database, user, dan password sesuai:

6. **Akses proyek melalui browser.**  
   Buka browser dan akses:
   ```
   http://localhost/stok_funmo
   ```

## 🔐 Akun Demo Login

### Admin

- Email: **arini@gmail.com**
- Password: **1234**

### Petugas

- Email: **nia@gmail.com**
- Password: **1234**

## 📋 Fitur-Fitur Utama Dashboard Admin

### 👥 Data Pelanggan

- Klik **Data Pelanggan** di sidebar untuk melihat daftar pelanggan.
- Untuk mengedit atau menghapus data, gunakan tombol pada kolom **Aksi**.
- Klik **Simpan Perubahan** setelah melakukan pengeditan.

### 📦 Input Produk

- Klik **Input Produk** di sidebar.
- Masukkan nama produk, harga, dan stok.
- Klik **Simpan** untuk menambahkan produk baru.

### 🗃️ Data Produk

- Klik **Data Produk** di sidebar untuk melihat semua produk.
- Produk dengan stok kurang dari 5 akan diberi tanda **Stok Menipis**.
- Anda dapat mengedit atau menghapus produk dari daftar ini.

### 💰 Data Transaksi

- Klik **Data Transaksi** untuk melihat riwayat transaksi.
- Detail struk transaksi dapat dilihat dan dihapus jika diperlukan.

### 📈 Laporan Penjualan Harian

- Klik **Laporan Penjualan** di sidebar.
- Jika transaksi dihapus, laporan terkait juga otomatis ikut terhapus.
- Admin juga dapat menghapus laporan secara manual.

### 🧑‍💼 Data Petugas

- Klik **Data Petugas** untuk menambah petugas baru.
- Masukkan data yang diperlukan, lalu klik **Tambah**.
- Data petugas dapat diedit atau dihapus.

### 🚪 Logout

- Klik **Logout** untuk keluar dari dashboard admin.

---

## ✍️ Panduan Penggunaan Dashboard Petugas

### 🧾 Input Pelanggan

- Klik menu **Input Pelanggan** di sidebar.
- Isi data pelanggan sesuai form yang tersedia.
- Klik **Simpan** untuk menambahkan pelanggan baru.

### 💳 Input Transaksi

- Klik menu **Input Transaksi** di sidebar.
- Pilih pelanggan dari daftar yang tersedia.
- Jika pelanggan belum ada, tambahkan terlebih dahulu melalui menu **Input Pelanggan**.
- Pilih produk yang akan dibeli dan masukkan jumlah yang diinginkan.
- Klik **Simpan Transaksi** untuk menyelesaikan proses.

### 🔍 Melihat Data Pelanggan

- Klik menu **Data Pelanggan** di sidebar.
- Gunakan fitur pencarian untuk menemukan data pelanggan dengan cepat.

### 📑 Melihat Data Transaksi & Detail

- Klik menu **Data Transaksi** di sidebar.
- Di halaman ini, Anda bisa melihat daftar transaksi dan juga detail dari masing-masing transaksi.

## 👩‍💻 Pengembang

**Arini Putri Mahira**

- ✉️ Email: [aputrimahira@gmail.com](mailto:aputrimahira@gmail.com)
- 💻 GitHub: [raraharsa](https://github.com/raraharsa)
