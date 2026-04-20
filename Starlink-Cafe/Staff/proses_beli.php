<?php
session_start();

// Proteksi Halaman: Memastikan hanya aktor Staff yang bisa memproses (Sesuai Use Case)
if (!isset($_SESSION['role']) || $_SESSION['role'] != "Staff") {
    header("location:../index.php?pesan=terlarang");
    exit;
}

include '../config/db.php';

if (isset($_POST['simpan_transaksi'])) {
    // Mengambil data dari form order.php
    $id_supplier = mysqli_real_escape_string($conn, $_POST['id_supplier']);
    $id_makanan  = mysqli_real_escape_string($conn, $_POST['id_makanan']);
    $jumlah      = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tanggal     = date('Y-m-d');

    // 1. Logika 'Hitung Total' (Sesuai Use Case: Hitung Total)
    // Mengambil harga dari entitas FoodItem/makanan
    $query_makanan = mysqli_query($conn, "SELECT harga FROM makanan WHERE id_makanan = '$id_makanan'");
    $data_makanan  = mysqli_fetch_assoc($query_makanan);
    $harga_satuan  = $data_makanan['harga'];
    
    // Kalkulasi total biaya yang akan disimpan di tabel pembelian
    $total_harga = $harga_satuan * $jumlah;

    // 2. Logika 'Simpan Bukti Transaksi' (Sesuai Use Case)
    $query_beli = "INSERT INTO pembelian (id_supplier, id_makanan, jumlah, total_harga, tanggal) 
                   VALUES ('$id_supplier', '$id_makanan', '$jumlah', '$total_harga', '$tanggal')";
    
    if (mysqli_query($conn, $query_beli)) {
        // Mendapatkan ID transaksi yang baru saja masuk untuk keperluan cetak struk
        $id_transaksi_baru = mysqli_insert_id($conn);

        // 3. Logika 'Update Stok' (Sesuai Use Case: Update Stok via Extend Kelola Stok)
        // Menambah stok di Inventory berdasarkan jumlah pembelian
        $update_stok = "UPDATE makanan SET stok = stok + $jumlah WHERE id_makanan = '$id_makanan'";
        mysqli_query($conn, $update_stok);

        // 4. Redirect ke halaman cetak struk dengan membawa ID transaksi
        header("location:cetak_struk.php?id=$id_transaksi_baru");
    } else {
        // Jika query gagal, kembali ke form order
        header("location:order.php?pesan=gagal_simpan");
    }
} else {
    // Jika diakses tanpa melalui tombol simpan, lempar kembali ke form
    header("location:order.php");
}
?>