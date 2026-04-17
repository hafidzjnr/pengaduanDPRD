<?php
session_start();
if (isset($_SESSION['login'])) {
    header("location:dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pengaduan DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-body">

    <div class="login-wrapper">
        <div class="side-panel">
            <div class="logo-area">
                <div class="logo-badge">⚖️</div>
                <h1>Pengaduan Masyarakat DPRD</h1>
                <p class="side-desc">Kota Tasikmalaya — Platform aspirasi dan pengaduan kinerja desa secara digital.</p>
            </div>
            <div class="side-features">
                <div class="feature-item">
                    <div class="feature-dot"></div>Sampaikan laporan langsung ke DPRD
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>Pantau status pengaduan Anda
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>Tanggapan resmi dari pemerintah
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div>Aman &amp; terpercaya
                </div>
            </div>
        </div>

        <div class="form-panel">
            <h2>Selamat Datang</h2>
            <p class="form-subtitle">Masuk ke akun masyarakat Anda</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    &#9888;&#65039; <?php
                                    if ($_GET['error'] == 'password') echo 'Password yang Anda masukkan salah.';
                                    elseif ($_GET['error'] == 'user')  echo 'Username tidak ditemukan.';
                                    else echo 'Terjadi kesalahan. Silakan coba lagi.';
                                    ?>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username Anda" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                </div>
                <button class="btn-primary" type="submit" name="login">Masuk ke Akun</button>
            </form>

            <div class="form-footer">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </div>
        </div>
    </div>

</body>

</html>