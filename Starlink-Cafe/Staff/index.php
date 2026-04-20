<?php
session_start();
// Proteksi Halaman sesuai Use Case (Aktor Staff)
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// Menghitung ringkasan untuk dashboard (Mendukung Use Case: Input Pembelian)
$query_makanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM makanan");
$total_makanan = mysqli_fetch_assoc($query_makanan);

$query_stok_kritis = mysqli_query($conn, "SELECT COUNT(*) as total FROM makanan WHERE stok < 10");
$stok_kritis = mysqli_fetch_assoc($query_stok_kritis);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Staff - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-staff { background-color: #6c757d; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .welcome-card { border: none; border-radius: 15px; background: linear-gradient(45deg, #ffffff, #f1f1f1); }
        .feature-card { transition: all 0.3s ease; border: none; border-radius: 12px; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .stat-icon { font-size: 2.5rem; opacity: 0.3; position: absolute; right: 15px; top: 15px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-staff px-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-person-badge-fill me-2"></i>STARLINK STAFF</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Sesi: <strong><?= $_SESSION['username']; ?></strong></span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm rounded-pill"><i class="bi bi-power"></i> Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card feature-card bg-white p-3 shadow-sm mb-3">
                    <i class="bi bi-box-seam stat-icon"></i>
                    <h6 class="text-muted">Total Katalog Makanan</h6>
                    <h2 class="fw-bold"><?= $total_makanan['total']; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card bg-white p-3 shadow-sm mb-3 border-start border-danger border-5">
                    <i class="bi bi-exclamation-octagon stat-icon text-danger"></i>
                    <h6 class="text-muted">Stok Butuh Restock</h6>
                    <h2 class="fw-bold text-danger"><?= $stok_kritis['total']; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card bg-white p-3 shadow-sm mb-3 border-start border-primary border-5">
                    <i class="bi bi-cart-plus stat-icon text-primary"></i>
                    <h6 class="text-muted">Aksi Cepat</h6>
                    <a href="pembelian.php" class="btn btn-primary btn-sm mt-1">Input Transaksi</a>
                </div>
            </div>
        </div>

        <div class="card welcome-card p-5 shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-6 fw-bold text-dark">Manajemen Pembelian</h1>
                    <p class="lead text-secondary">Selamat datang di modul operasional. Di sini Anda dapat melakukan pencatatan barang masuk dari supplier sesuai tugas **Aktor Staff** di sistem.</p>
                    <div class="mt-4">
                        <a href="pembelian.php" class="btn btn-dark btn-lg px-4 me-2 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Input Pembelian Baru
                        </a>
                        <a href="riwayat_stok.php" class="btn btn-outline-dark btn-lg px-4">
                            <i class="bi bi-journal-text me-2"></i>Lihat Stok
                        </a>
                    </div>
                </div>
                <div class="col-md-4 text-center d-none d-md-block">
                    <i class="bi bi-clipboard2-check text-secondary" style="font-size: 8rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5 class="text-muted mb-3 fw-bold"><i class="bi bi-list-task me-2"></i>Tugas Anda Hari Ini:</h5>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded bg-white">
                    <h6 class="fw-bold"><i class="bi bi-check2-square me-2 text-success"></i>Pilih Makanan</h6>
                    <small class="text-muted">Memilih item yang akan dipesan dari supplier.</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded bg-white">
                    <h6 class="fw-bold"><i class="bi bi-check2-square me-2 text-success"></i>Hitung Total</h6>
                    <small class="text-muted">Sistem akan otomatis menghitung kalkulasi biaya.</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded bg-white">
                    <h6 class="fw-bold"><i class="bi bi-check2-square me-2 text-success"></i>Simpan Transaksi</h6>
                    <small class="text-muted">Menyimpan bukti ke dalam database sistem.</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>