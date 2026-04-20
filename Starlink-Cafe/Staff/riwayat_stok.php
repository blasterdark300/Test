<?php
session_start();
// Proteksi Halaman: Pastikan hanya Staff yang bisa akses
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// Ambil data stok makanan dari database sesuai alur Sequence Diagram
$query = "SELECT * FROM makanan ORDER BY nama_makanan ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Stok - Starlink Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-staff { background-color: #6c757d; }
        .stok-kritis { background-color: #fff3cd; color: #856404; font-weight: bold; }
        .stok-aman { color: #198754; }
        .card-table { border: none; border-radius: 15px; overflow: hidden; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-staff px-4 shadow-sm mb-4">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-arrow-left-circle me-2"></i>Kembali ke Dashboard
        </a>
        <span class="navbar-text text-white">
            Petugas: <strong><?= $_SESSION['username']; ?></strong>
        </span>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h3 class="fw-bold"><i class="bi bi-box-seam me-2"></i>Daftar Stok Bahan & Makanan</h3>
                <p class="text-muted">Pantau ketersediaan stok sebelum melakukan Input Pembelian.</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="pembelian.php" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Input Pembelian
                </a>
            </div>
        </div>

        <div class="card card-table shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nama Item</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Jumlah Stok</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <?php 
                            // Logika indikator warna untuk stok rendah
                            $is_kritis = ($row['stok'] < 10); 
                            $row_class = $is_kritis ? 'stok-kritis' : '';
                        ?>
                        <tr class="<?= $row_class; ?>">
                            <td class="ps-4"><?= $row['id_makanan']; ?></td>
                            <td><strong><?= $row['nama_makanan']; ?></strong></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td class="text-center"><?= $row['stok']; ?></td>
                            <td class="text-center">
                                <?php if($is_kritis): ?>
                                    <span class="badge bg-danger"><i class="bi bi-arrow-down-circle"></i> Stok Rendah</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <div class="alert alert-light border small text-muted">
                <strong>Catatan:</strong> Status <span class="badge bg-danger">Stok Rendah</span> muncul jika jumlah item kurang dari 10 unit. Segera hubungi Admin atau buat laporan pembelian jika stok kritis.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>