<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login | Pengaduan DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Aplikasi Pengaduan Masyarakat</h2>
        <p>DPRD Kota Tasikmalaya</p>
        <hr>
        <form action="proses_login.php" method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="login">Masuk</button>
        </form>
        <a href="register.php">Belum punya akun? Daftar di sini</a>
    </div>
</body>
</html>