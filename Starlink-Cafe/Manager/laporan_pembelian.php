<?php
session_start();
// Proteksi Halaman sesuai Use Case (Aktor Manager)
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Manager") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// Logika pengambilan data dari database (Mendukung Analisis Data)
// Kita gunakan JOIN agar laporan lengkap (menampilkan nama supplier dan nama makanan)
$query = "SELECT p.*, s.nama_supplier, m.nama_makanan 
          FROM pembelian p
          JOIN supplier s ON p.id_supplier = s.id_supplier
          JOIN makanan m ON p.id_makanan = m.id_makanan
          ORDER BY p.tanggal DESC";
$result = mysqli_query($conn, $query);

// Menghitung total pengeluaran untuk ringkasan (Analisis Data)
$total_pengeluaran = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pembelian");
$row_total = mysqli_fetch_assoc($total_pengeluaran);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian - Starlink Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card-report { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table thead { background-color: #0dcaf0; color: white; }
        @media print {
            .btn, .navbar, .no-print { display: none !important; }
            body { background-color: white; padding: 0; }
            .card-report { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-info px-4 no-print shadow-sm mb-4">
        <a class="navbar-brand text-dark fw-bold" href="index.php">
            <i class="bi bi-arrow-left-circle me-2"></i>Kembali ke Dashboard
        </a>
        <span class="navbar-text text-dark">
            Manager: <strong><?= $_SESSION['username']; ?></strong>
        </span>
    </nav>

    <div class="container mb-5">
        <div class="row mb-4 no-print">
            <div class="col-md-12 text-center">
                <h2 class="fw-bold">LAPORAN PEMBELIAN BARANG</h2>
                <p class="text-muted">Starlink Cafe - Periode April 2026</p>
            </div>
        </div>

        <div class="row mb-4 no-print">
            <div class="col-md-4">
                <div class="card card-report bg-white p-3 text-center border-start border-info border-5">
                    <h6 class="text-muted small uppercase">Total Pengeluaran</h6>
                    <h4 class="fw-bold text-info">Rp <?= number_format($row_total['total'] ?? 0, 0, ',', '.'); ?></h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-report bg-white p-3 text-center border-start border-success border-5">
                    <h6 class="text-muted small">Total Transaksi</h6>
                    <h4 class="fw-bold text-success"><?= mysqli_num_rows($result); ?> Kali</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-report bg-white p-3 text-center">
                    <button class="btn btn-outline-dark h-100" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Cetak Laporan PDF
                    </button>
                </div>
            </div>
        </div>

        <div class="card card-report overflow-hidden">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Supplier</th>
                            <th>Item Makanan</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end pe-4">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="ps-4"><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $row['nama_supplier']; ?></span></td>
                                <td><strong><?= $row['nama_makanan']; ?></strong></td>
                                <td class="text-center"><?= $row['jumlah']; ?> Unit</td>
                                <td class="text-end pe-4 fw-bold">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted italic">Belum ada data transaksi pembelian.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-end no-print">
            <small class="text-muted small">Dicetak pada: <?= date('d/m/Y H:i'); ?></small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>