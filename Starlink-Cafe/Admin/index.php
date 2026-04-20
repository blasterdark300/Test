<?php
session_start();
// Proteksi: Cek apakah session ada dan role-nya Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}

include '../config/db.php';

// Ambil jumlah data untuk statistik ringkas menggunakan COUNT(*)
$q_makanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM makanan");
$data_makanan = mysqli_fetch_assoc($q_makanan);
$makanan = $data_makanan['total'];

$q_supplier = mysqli_query($conn, "SELECT COUNT(*) as total FROM supplier");
$data_supplier = mysqli_fetch_assoc($q_supplier);
$supplier = $data_supplier['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .card-hover { transition: 0.3s; border-radius: 15px; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark px-4 shadow-sm">
        <span class="navbar-brand mb-0 h1"><i class="bi bi-cpu-fill me-2"></i>Starlink Admin Panel</span>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 d-none d-sm-inline">Halo, <strong><?= $_SESSION['username']; ?></strong></span>
            <a href="../logout.php" class="btn btn-danger btn-sm rounded-pill px-3">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">Ringkasan Data Master</h2>
                <p class="text-muted">Kelola identitas menu dan data mitra supplier cafe.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-primary text-white h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-uppercase fw-bold mb-0" style="opacity: 0.8;">Data Menu</h6>
                            <i class="bi bi-egg-fried fs-1"></i>
                        </div>
                        <h2 class="display-4 fw-bold"><?= $makanan; ?></h2>
                        <p class="card-text">Total item makanan dan minuman terdaftar.</p>
                        <hr style="opacity: 0.2;">
                        <a href="menu.php" class="btn btn-light btn-sm fw-bold rounded-pill px-3">Kelola Daftar Menu</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-success text-white h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-uppercase fw-bold mb-0" style="opacity: 0.8;">Data Mitra</h6>
                            <i class="bi bi-truck fs-1"></i>
                        </div>
                        <h2 class="display-4 fw-bold"><?= $supplier; ?></h2>
                        <p class="card-text">Jumlah supplier aktif yang bekerjasama.</p>
                        <hr style="opacity: 0.2;">
                        <a href="supplier.php" class="btn btn-light btn-sm fw-bold rounded-pill px-3">Kelola Data Supplier</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="alert alert-info border-0 shadow-sm rounded-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Alur Kerja:</strong> Admin mendaftarkan Nama Menu & Kategori. Manager akan melengkapi <strong>Harga</strong> dan <strong>Stok</strong> melalui panel Manager.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>