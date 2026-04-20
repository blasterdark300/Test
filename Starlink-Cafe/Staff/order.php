<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") { 
    header("location:../index.php?pesan=terlarang"); 
    exit; 
}
include '../config/db.php';

// Mengambil data untuk pilihan (Sesuai Sequence Diagram)
$makanan = mysqli_query($conn, "SELECT * FROM makanan ORDER BY nama_makanan ASC");
$supplier = mysqli_query($conn, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pembelian - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-order { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .form-label { font-weight: 600; color: #495057; }
        .btn-order { border-radius: 10px; padding: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-secondary px-4 shadow-sm mb-4">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-arrow-left-circle me-2"></i>Kembali
        </a>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-order p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-cart-check-fill text-primary fs-1"></i>
                        <h3 class="fw-bold">Input Pembelian Barang</h3>
                        <p class="text-muted">Lengkapi data transaksi di bawah ini</p>
                    </div>

                    <form action="proses_beli.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-truck me-2"></i>Pilih Supplier</label>
                            <select name="id_supplier" class="form-select form-select-lg" required>
                                <option value="" selected disabled>-- Pilih Mitra Supplier --</option>
                                <?php while($s = mysqli_fetch_assoc($supplier)): ?>
                                    <option value="<?= $s['id_supplier']; ?>"><?= $s['nama_supplier']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-egg-fried me-2"></i>Item Makanan/Bahan</label>
                            <select name="id_makanan" class="form-select form-select-lg" required id="pilih_makanan">
                                <option value="" selected disabled>-- Pilih Item --</option>
                                <?php while($m = mysqli_fetch_assoc($makanan)): ?>
                                    <option value="<?= $m['id_makanan']; ?>" data-harga="<?= $m['harga']; ?>">
                                        <?= $m['nama_makanan']; ?> (Rp <?= number_format($m['harga']); ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="bi bi-plus-slash-minus me-2"></i>Jumlah (Qty)</label>
                            <input type="number" name="jumlah" class="form-control form-control-lg" placeholder="0" min="1" required id="qty">
                        </div>

                        <div class="alert alert-primary d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold">Estimasi Total:</span>
                            <h4 class="mb-0 fw-bold" id="total_preview">Rp 0</h4>
                        </div>

                        <button type="submit" name="simpan_transaksi" class="btn btn-primary w-100 btn-order shadow">
                            <i class="bi bi-save2 me-2"></i>Simpan & Cetak Struk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk Hitung Total otomatis (Real-time)
        const selectMakan = document.getElementById('pilih_makanan');
        const inputQty = document.getElementById('qty');
        const previewTotal = document.getElementById('total_preview');

        function hitung() {
            const selectedOption = selectMakan.options[selectMakan.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga') || 0;
            const qty = inputQty.value || 0;
            const total = harga * qty;
            
            previewTotal.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        selectMakan.addEventListener('change', hitung);
        inputQty.addEventListener('input', hitung);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>