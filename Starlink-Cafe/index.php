<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Starlink Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); 
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container { 
            max-width: 400px; 
            border-top: 5px solid #0d6efd;
        }
        .form-footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 login-container bg-white p-4 shadow rounded">
                <h3 class="text-center mb-2">Starlink Cafe</h3>
                <p class="text-center text-muted mb-4">Sistem Manajemen Pembelian</p>

                <?php 
                if(isset($_GET['pesan'])){
                    if($_GET['pesan'] == "gagal"){
                        echo "<div class='alert alert-danger shadow-sm py-2 small text-center'>Username atau Password salah!</div>";
                    } else if($_GET['pesan'] == "logout"){
                        echo "<div class='alert alert-info shadow-sm py-2 small text-center'>Anda telah berhasil logout.</div>";
                    } else if($_GET['pesan'] == "terlarang"){
                        echo "<div class='alert alert-warning shadow-sm py-2 small text-center'>Silakan login untuk mengakses halaman tersebut.</div>";
                    }
                }
                ?>
                
                <form action="proses_login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    
                    <div class="mb-3 text-end">
                        <a href="lupa_password.php" class="text-decoration-none text-muted small">Lupa kata sandi?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3 shadow-sm">Login Sistem</button>
                    
                    <div class="text-center form-footer">
                        <span>Belum punya akun? </span>
                        <a href="register.php" class="text-decoration-none fw-bold text-primary">Daftar Sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>