<?php
session_start();
// Proteksi: Hanya Staff yang bisa mencetak struk pembelian
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") {
    header("location:../index.php?pesan=terlarang");
    exit;
}

include '../config/db.php';

// Mengambil ID transaksi dari URL
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Query Join untuk mengambil data lengkap sesuai entitas pada Class Diagram
$query = mysqli_query($conn, "SELECT p.*, m.nama_makanan, m.harga, s.nama_supplier 
                              FROM pembelian p 
                              JOIN makanan m ON p.id_makanan = m.id_makanan 
                              JOIN supplier s ON p.id_supplier = s.id_supplier 
                              WHERE p.id_pembelian = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "Data transaksi tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian #<?= $id; ?></title>
    <style>
        /* Desain khusus struk (Ukuran standar struk belanja) */
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 300px; 
            margin: 10px auto; 
            color: #000;
        }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; font-size: 12px; }
        .total { font-weight: bold; font-size: 14px; }
        
        /* Menyembunyikan elemen saat diprint */
        @media print {
            .no-print { display: none; }
            body { margin: 0; width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <h2 style="margin:0;">STARLINK CAFE</h2>
        <p style="font-size:10px;">Jl. Raya Narotama No. 04, Surabaya<br>Telp: 0812-XXXX-XXXX</p>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td>ID Trans</td>
            <td>: #<?= $data['id_pembelian']; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= date('d/m/Y H:i', strtotime($data['tanggal'])); ?></td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td>: <?= $data['nama_supplier']; ?></td>
        </tr>
        <tr>
            <td>Petugas</td>
            <td>: <?= $_SESSION['username']; ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td colspan="2"><?= $data['nama_makanan']; ?></td>
        </tr>
        <tr>
            <td><?= $data['jumlah']; ?> x Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
            <td style="text-align:right;">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="total">
        <tr>
            <td>TOTAL BAYAR</td>
            <td style="text-align:right;">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center" style="font-size: 10px;">
        <p>BUKTI PEMBELIAN SAH<br>Starlink Cafe - Terimakasih</p>
    </div>

    <div class="text-center no-print" style="margin-top: 20px;">
        <hr>
        <a href="order.php" style="text-decoration:none; color:blue;">[ Kembali ke Order ]</a>
    </div>

    <script>
        // Otomatis kembali ke dashboard setelah jendela print ditutup
        window.onafterprint = function() {
            window.location.href = 'index.php';
        };
    </script>
</body>
</html>