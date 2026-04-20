<?php
session_start();
include 'config/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$login = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($login);

if($cek > 0){
    $data = mysqli_fetch_assoc($login);
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $data['role'];

    // Cek role dan arahkan ke folder masing-masing
    if($data['role'] == "Admin"){
        header("location:Admin/index.php");
    } else if($data['role'] == "Staff"){
        header("location:Staff/index.php");
    } else if($data['role'] == "Manager"){
        header("location:Manager/index.php");
    }
} else {
    header("location:index.php?pesan=gagal");
}
?>