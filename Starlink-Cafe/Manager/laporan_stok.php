<?php
session_start();
// Proteksi Halaman
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Manager") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// --- LOGIKA PAGINATION ---
$jumlahDataPerHalaman = 10; // Tampilkan 10 menu per halaman
$resultTotal = mysqli_query($conn, "SELECT COUNT(*) as total FROM makanan");
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalData = $rowTotal['total'];
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);

$halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
if ($halamanAktif < 1) $halamanAktif = 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// --- LOGIKA UPDATE HARGA & STOK ---
if (isset($_POST['update_stok'])) {
    $id = $_POST['id_makanan'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query_update = "UPDATE makanan SET harga='$harga', stok='$stok', status_check=1 WHERE id_makanan='$id'";
    if (mysqli_query($conn, $query_update)) {
        header("Location: laporan_stok.php?halaman=$halamanAktif&status=sukses");
        exit;
    }
}

// Ambil data dengan LIMIT untuk pagination
// Diurutkan: Menu Baru (status_check=0) paling atas, lalu stok terkecil
$query = "SELECT * FROM makanan ORDER BY status_check ASC, stok ASC LIMIT $awalData, $jumlahDataPerHalaman";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stok & Harga - Starlink Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-manager { background-color: #0dcaf0; }
        .status-baru { background-color: #fffef0; } 
        .card-report { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .page-link { color: #0dcaf0; }
        .active > .page-link { background-color: #0dcaf0 !important; border-color: #0dcaf0 !important; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-manager px-4 shadow-sm no-print mb-4">
        <div class="container-fluid">
            <a class="navbar-brand text-dark fw-bold" href="index.php">
                <i class="bi bi-arrow-left-circle me-2"></i>Dashboard Manager
            </a>
            <button class="btn btn-outline-dark btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Cetak Laporan
            </button>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark text-uppercase">Manajemen Stok & Harga</h2>
            <p class="text-muted small">Halaman: <?= $halamanAktif; ?> dari <?= $jumlahHalaman; ?></p>
        </div>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                <strong>Berhasil!</strong> Perubahan data telah disimpan.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card card-report overflow-hidden mb-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Item</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Status</th>
                            <th class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $awalData + 1;
                        while($row = mysqli_fetch_assoc($result)): 
                            $is_new = ($row['status_check'] == 0);
                            $kritis = ($row['stok'] < 10);
                        ?>
                        <tr class="<?= $is_new ? 'status-baru' : ''; ?>">
                            <td class="ps-4 text-muted fw-bold"><?= $no++; ?></td>
                            <td>
                                <strong><?= $row['nama_makanan']; ?></strong>
                                <?= $is_new ? '<span class="badge bg-warning text-dark ms-2 small animate-pulse">Baru</span>' : ''; ?>
                            </td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td class="text-center fw-bold <?= $kritis ? 'text-danger' : 'text-success'; ?>">
                                <?= $row['stok']; ?>
                            </td>
                            <td class="text-center">
                                <?php if($kritis): ?>
                                    <span class="badge bg-danger rounded-pill px-3">Restock</span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill px-3">Aman</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center no-print">
                                <button type="button" class="btn btn-sm btn-info text-white px-3 shadow-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal<?= $row['id_makanan']; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= $row['id_makanan']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="" method="POST">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title">Update Harga & Stok</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_makanan" value="<?= $row['id_makanan']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Item: <?= $row['nama_makanan']; ?></label>
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Harga Jual Baru (Rp)</label>
                                                <input type="number" name="harga" class="form-control" value="<?= $row['harga']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Update Jumlah Stok</label>
                                                <input type="number" name="stok" class="form-control" value="<?= $row['stok']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" name="update_stok" class="btn btn-info text-white px-4">Simpan Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <nav class="no-print">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($halamanAktif <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
                </li>
                <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <li class="page-item <?= ($i == $halamanAktif) ? 'active' : ''; ?>">
                        <a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($halamanAktif >= $jumlahHalaman) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>