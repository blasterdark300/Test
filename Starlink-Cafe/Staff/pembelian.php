<?php
session_start();
// Proteksi Role Staff
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// Ambil data untuk dropdown sesuai Sequence Diagram
$suppliers = mysqli_query($conn, "SELECT * FROM supplier");
$makanan_query = mysqli_query($conn, "SELECT * FROM makanan"); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pembelian - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .btn-primary { background: #4e73df; border: none; }
        .btn-light { border: 1px solid #ddd; }
        .form-label { font-size: 0.9rem; color: #555; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            
            <div class="mb-3">
                <a href="index.php" class="btn btn-sm btn-light text-secondary shadow-sm">
                    <i class="bi bi-chevron-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark">Panel Staff</h3>
                    <p class="text-muted small">Input Data Pembelian Starlink Cafe</p>
                </div>
                
                <form action="proses_beli.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Supplier</label>
                        <select name="id_supplier" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Supplier --</option>
                            <?php while($s = mysqli_fetch_assoc($suppliers)): ?>
                                <option value="<?= $s['id_supplier']; ?>"><?= $s['nama_supplier']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Makanan</label>
                        <select name="id_makanan" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Makanan --</option>
                            <?php while($m = mysqli_fetch_assoc($makanan_query)): ?>
                                <option value="<?= $m['id_makanan']; ?>"><?= $m['nama_makanan']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Jumlah Beli</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="0" required min="1">
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="simpan_transaksi" class="btn btn-primary p-2 mb-2">
                            <i class="bi bi-check-circle-fill"></i> Simpan & Cetak Struk
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="text-center mt-3">
                <small class="text-muted">Staff: <strong><?= $_SESSION['username']; ?></strong></small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>