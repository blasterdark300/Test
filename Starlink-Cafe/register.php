<?php
include 'config/db.php'; // Pastikan path ke file koneksi benar

$pesan = "";
if (isset($_POST['daftar'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Di tahap belajar bisa langsung, tapi disarankan password_hash
    $role     = $_POST['role'];

    // Cek apakah username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert alert-danger'>Username sudah digunakan!</div>";
    } else {
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            $pesan = "<div class='alert alert-success'>Registrasi berhasil! Silakan <a href='index.php'>Login</a></div>";
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal mendaftar: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; height: 100vh; }
        .register-container { max-width: 450px; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container bg-white p-4 shadow rounded">
            <h3 class="text-center mb-4">Daftar Akun Baru</h3>
            <?= $pesan; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Buat username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Buat password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role / Jabatan</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="Admin">Admin (Master Data)</option>
                        <option value="Staff">Staff (Input Pembelian)</option>
                        <option value="Manager">Manager (Laporan)</option>
                    </select>
                </div>
                <button type="submit" name="daftar" class="btn btn-success w-100 mb-3">Daftar Sekarang</button>
                <div class="text-center">
                    <a href="index.php" class="text-decoration-none">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>