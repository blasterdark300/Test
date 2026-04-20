<?php
session_start();
include '../config/db.php';

// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("location:../index.php?pesan=terlarang");
    exit;
}

// --- LOGIKA PAGINATION ---
$jumlahDataPerHalaman = 10; 
$resultTotal = mysqli_query($conn, "SELECT COUNT(*) as total FROM makanan");
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalData = $rowTotal['total'];
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);

$halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
if ($halamanAktif < 1) $halamanAktif = 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// 1. Logika TAMBAH Data
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $query = "INSERT INTO makanan (nama_makanan, harga, stok, status_check) 
              VALUES ('$nama', 0, 0, 0)";
    
    if (mysqli_query($conn, $query)) {
        header("Location: menu.php?status=sukses_tambah");
        exit;
    }
}

// 2. Logika HAPUS Data
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    try {
        mysqli_query($conn, "DELETE FROM makanan WHERE id_makanan=$id");
        header("Location: menu.php?status=sukses_hapus");
        exit;
    } catch (mysqli_sql_exception $e) {
        header("Location: menu.php?status=gagal_relasi");
        exit;
    }
}

// Ambil data dengan LIMIT
$result = mysqli_query($conn, "SELECT * FROM makanan ORDER BY id_makanan ASC LIMIT $awalData, $jumlahDataPerHalaman");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Master Menu Starlink Cafe</h2>
                <p class="text-muted">Admin: Input Nama Menu. Manager: Input Harga & Stok.</p>
            </div>
            <a href="index.php" class="btn btn-secondary shadow-sm">Dashboard</a>
        </div>

        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm">
                <?php 
                    if($_GET['status'] == 'sukses_tambah') echo "<strong>Berhasil!</strong> Menu baru telah didaftarkan.";
                    if($_GET['status'] == 'sukses_hapus') echo "<strong>Berhasil!</strong> Menu telah dihapus.";
                    if($_GET['status'] == 'gagal_relasi') echo "<strong>Gagal!</strong> Menu tidak bisa dihapus karena sudah ada riwayat transaksi.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card card-custom mb-4">
            <div class="card-body p-4">
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label fw-bold">Nama Makanan / Minuman</label>
                        <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Es Kopi Susu Gula Aren" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="tambah" class="btn btn-primary w-100 fw-bold">Daftarkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom overflow-hidden mb-3">
            <table class="table table-hover bg-white align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="10%" class="ps-4">No</th>
                        <th>Nama Menu</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $awalData + 1; 
                    if(mysqli_num_rows($result) > 0): 
                        while($row = mysqli_fetch_assoc($result)) : 
                    ?>
                    <tr>
                        <td class="ps-4 text-muted fw-bold"><?= $no++; ?></td>
                        <td><strong><?= $row['nama_makanan']; ?></strong></td>
                        <td class="text-center">
                            <a href="menu.php?hapus=<?= $row['id_makanan']; ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Hapus menu ini?')">Hapus</a>
                        </td>
                    </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada menu yang didaftarkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav>
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