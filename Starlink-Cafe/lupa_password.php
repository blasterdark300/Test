<?php
include 'config/db.php';

$pesan = "";
if (isset($_POST['reset'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['new_password'];

    // Cek apakah username ada di database
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        // Update password baru
        $update = mysqli_query($conn, "UPDATE users SET password = '$new_password' WHERE username = '$username'");
        if ($update) {
            $pesan = "<div class='alert alert-success'>Password berhasil diperbarui! Silakan <a href='index.php'>Login</a></div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger'>Username tidak ditemukan!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; height: 100vh; }
        .reset-container { max-width: 400px; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="reset-container bg-white p-4 shadow rounded">
            <h3 class="text-center mb-3">Reset Password</h3>
            <p class="text-center text-muted small mb-4">Masukkan username Anda untuk memperbarui kata sandi.</p>
            <?= $pesan; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Username akun Anda">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control" required placeholder="Masukkan password baru">
                </div>
                <button type="submit" name="reset" class="btn btn-warning w-100 mb-3">Perbarui Password</button>
                <div class="text-center">
                    <a href="index.php" class="text-decoration-none">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>