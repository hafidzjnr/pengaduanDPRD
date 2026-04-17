<?php
session_start();
if(!isset($_SESSION['login'])){
    header("location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | Masyarakat</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <h1>Layanan Pengaduan DPRD Kota Tasikmalaya</h1>
        <p>Selamat Datang, <b><?php echo $_SESSION['nama']; ?></b> | <a href="logout.php">Keluar</a></p>
    </nav>

    <div class="container">
        <h3>Menu Utama</h3>
        <ul>
            <li><a href="tulis_pengaduan.php">Tulis Pengaduan</a></li>
            <li><a href="lihat_pengaduan.php">Lihat Riwayat Laporan</a></li>
        </ul>
    </div>
</body>
</html>