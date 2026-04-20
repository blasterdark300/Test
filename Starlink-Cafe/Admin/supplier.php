<?php
session_start();
include '../config/db.php';

// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("location:../index.php?pesan=terlarang");
    exit;
}

// --- LOGIKA PAGINATION ---
$jumlahDataPerHalaman = 10; // Tampilkan 10 data per halaman
$resultTotal = mysqli_query($conn, "SELECT COUNT(*) as total FROM supplier");
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalData = $rowTotal['total'];
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);

// Tentukan halaman aktif
$halamanAktif = (isset($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
if ($halamanAktif < 1) $halamanAktif = 1;

// Tentukan awal data (offset)
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// --- LOGIKA CRUD ---
if (isset($_POST['tambah'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $kontak  = mysqli_real_escape_string($conn, $_POST['kontak']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);

    $query = "INSERT INTO supplier (nama_supplier, kontak, alamat) VALUES ('$nama', '$kontak', '$alamat')";
    mysqli_query($conn, $query);
    header("Location: supplier.php?status=sukses_tambah");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier=$id");
    header("Location: supplier.php?status=sukses_hapus");
    exit;
}

// Ambil data dengan LIMIT untuk pagination
$result = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id_supplier ASC LIMIT $awalData, $jumlahDataPerHalaman");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kelola Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .page-link { color: #0d6efd; border-radius: 5px; margin: 0 2px; }
        .active > .page-link { background-color: #0d6efd !important; border-color: #0d6efd !important; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="bi bi-truck me-2 text-primary"></i>Master Data Supplier</h2>
            <a href="index.php" class="btn btn-secondary shadow-sm">Dashboard</a>
        </div>

        <div class="card card-custom mb-4">
            <div class="card-header bg-primary text-white fw-bold">Daftarkan Supplier Baru</div>
            <div class="card-body">
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Nama Perusahaan/Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" placeholder="Contoh: CV Maju Jaya" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">No. Kontak (WhatsApp)</label>
                        <input type="text" name="kontak" class="form-control" placeholder="0812xxxx" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Alamat Kantor</label>
                        <div class="input-group">
                            <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap..." required>
                            <button type="submit" name="tambah" class="btn btn-success px-4">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom overflow-hidden mb-3">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4" width="8%">No</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $awalData + 1; // Nomor urut disesuaikan dengan awal data halaman
                    while($row = mysqli_fetch_assoc($result)) : 
                    ?>
                    <tr class="align-middle">
                        <td class="ps-4 text-muted fw-bold"><?= $no++; ?></td> 
                        <td class="fw-bold"><?= $row['nama_supplier']; ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $row['kontak']; ?></span></td>
                        <td><?= $row['alamat']; ?></td>
                        <td class="text-center">
                            <div class="btn-group border rounded shadow-sm">
                                <a href="edit_supplier.php?id=<?= $row['id_supplier']; ?>" class="btn btn-sm btn-outline-warning border-0"><i class="bi bi-pencil-square"></i></a>
                                <a href="supplier.php?hapus=<?= $row['id_supplier']; ?>" 
                                   class="btn btn-sm btn-outline-danger border-0" 
                                   onclick="return confirm('Hapus supplier ini?')">
                                   <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
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