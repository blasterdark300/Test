<?php
// Memulai session
session_start();

// Menghapus semua variabel session
$_SESSION = array();

// Jika ingin menghapus session secara total di server
session_destroy();

// Mengarahkan kembali ke halaman login dengan pesan sukses
header("location:index.php?pesan=logout");
exit;
?>