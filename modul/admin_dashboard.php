
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:  white

            ;
            color: #5A5A5A;
            display: flex;
        }
        
        .content {
            margin-left: 270px;
            flex-grow: 1;
            padding: 20px;
        }
        .card {
            background: #FFFDF5;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #D4A373;
            border-color: #D4A373;
        }
        .btn-primary:hover {
            background-color: #C48B5F;
            border-color: #C48B5F;
        }
        .nav-tabs .nav-link {
            color: #5A5A5A;
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 2px solid  #C9A86A


            ;
            background: none;
            color:  #C9A86A


            ;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <img style="width: 15%;" src="../asset/img/logofunmo.png" alt="">
            <div>
                <span class="me-3">👤 <?= $currentUser ?></span>
                <a href="?logout=true" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <ul class="nav nav-tabs mb-4" id="dashboardTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#customers">Pelanggan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#products">Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#transactions">Transaksi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#reports">Laporan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#staff">Petugas</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="customers">
                <div class="card p-3">
                    <div class="card-header bg-transparent border-0 fw-bold">Data Pelanggan</div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3">Tambah</button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>


                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="products">
                <div class="card p-3">
                    <div class="card-header bg-transparent border-0 fw-bold">Data Produk</div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3">Tambah</button>
                        <!-- Tabel produk -->
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="transactions">
                <div class="card p-3">
                    <div class="card-header bg-transparent border-0 fw-bold">Transaksi Penjualan</div>
                    <div class="card-body">
                        <!-- Tabel transaksi -->
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="reports">
                <div class="card p-3">
                    <div class="card-header bg-transparent border-0 fw-bold">Laporan Penjualan</div>
                    <div class="card-body">
                        <!-- Konten laporan -->
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="staff">
                <div class="card p-3">
                    <div class="card-header bg-transparent border-0 fw-bold">Akun Petugas</div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3">Tambah</button>
                        <!-- Tabel petugas -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

