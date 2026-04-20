<?php
session_start();
// Proteksi Halaman
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Manager") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// --- ANALISIS DATA ---
// 1. Total Dana Keluar
$res_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pembelian"));

// 2. Monitoring Stok Kritis (Under 10)
$res_stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM makanan WHERE stok < 10"));

// 3. NOTIFIKASI MENU BARU (Mengecek menu yang status_check-nya masih 0)
$res_notif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as baru FROM makanan WHERE status_check = 0"));
$jumlah_baru = $res_notif['baru'];

// 4. Data untuk Grafik (Analisis Pembelian 7 Hari Terakhir)
$grafik_pembelian = mysqli_query($conn, "SELECT tanggal_pembelian, SUM(total_harga) as total 
                                         FROM pembelian 
                                         GROUP BY tanggal_pembelian 
                                         ORDER BY tanggal_pembelian DESC LIMIT 7");
$labels = [];
$data_values = [];
while($row = mysqli_fetch_assoc($grafik_pembelian)){
    $labels[] = date('d M', strtotime($row['tanggal_pembelian']));
    $data_values[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .navbar-manager { background-color: #0dcaf0; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-menu { transition: 0.3s; border-radius: 12px; }
        .btn-menu:hover { transform: scale(1.02); }
        .animate-pulse { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-manager px-4 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand text-dark fw-bold" href="#"><i class="bi bi-graph-up-arrow me-2"></i>STARLINK MANAGER</a>
            <div class="d-flex align-items-center">
                <span class="me-3 text-dark">Welcome, <strong><?= $_SESSION['username']; ?></strong></span>
                <a href="../logout.php" class="btn btn-dark btn-sm rounded-pill px-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <?php if($jumlah_baru > 0): ?>
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center animate-pulse" role="alert">
            <i class="bi bi-bell-fill fs-4 me-3"></i>
            <div>
                <strong>Perhatian Manager!</strong> Ada <b><?= $jumlah_baru; ?></b> menu baru yang belum memiliki harga & stok.
                <a href="laporan_stok.php" class="alert-link ms-2 text-decoration-none">Update Sekarang &rarr;</a>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-custom p-3 bg-white border-start border-info border-5">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Total Pengeluaran</small>
                            <h3 class="fw-bold mb-0">Rp <?= number_format($res_total['total'] ?? 0, 0, ',', '.'); ?></h3>
                        </div>
                        <i class="bi bi-cash-stack fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3 bg-white border-start border-danger border-5">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Stok Kritis</small>
                            <h3 class="fw-bold mb-0 text-danger"><?= $res_stok['jml']; ?> <small class="fs-6 text-muted">Item</small></h3>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3 bg-white border-start border-success border-5">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted text-uppercase fw-bold">Status Sistem</small>
                            <h3 class="fw-bold mb-0 text-success">Aktif</h3>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-custom p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart-line me-2"></i>Analisis Tren Pembelian (7 Hari Terakhir)</h5>
                    <canvas id="pembelianChart" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-custom p-4 h-100 bg-white">
                    <h5 class="fw-bold mb-3"><i class="bi bi-gear-fill me-2"></i>Manajemen Kontrol</h5>
                    <div class="d-grid gap-3">
                        <a href="laporan_pembelian.php" class="btn btn-outline-primary btn-menu py-3 text-start px-3">
                            <i class="bi bi-journal-text me-2"></i> Laporan Riwayat Pembelian
                        </a>
                        <a href="laporan_stok.php" class="btn <?= ($jumlah_baru > 0) ? 'btn-danger' : 'btn-outline-info'; ?> btn-menu py-3 text-start px-3">
                            <i class="bi bi-box-seam me-2"></i> Kelola & Update Stok
                            <?php if($jumlah_baru > 0): ?>
                                <span class="badge bg-white text-danger float-end"><?= $jumlah_baru; ?> Baru</span>
                            <?php endif; ?>
                        </a>
                        <div class="p-3 bg-light rounded-3 mt-2">
                            <small class="text-muted d-block mb-1">Shortcut Analisis:</small>
                            <button class="btn btn-sm btn-dark w-100 mb-2" onclick="window.print()">Cetak Laporan Bulanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('pembelianChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_reverse($labels)); ?>,
                datasets: [{
                    label: 'Pengeluaran (Rp)',
                    data: <?= json_encode(array_reverse($data_values)); ?>,
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>