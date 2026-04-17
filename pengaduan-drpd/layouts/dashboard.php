<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}
include 'config/koneksi.php';

$nik = $_SESSION['nik'];
$menunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan WHERE nik='$nik' AND status='0'"))['c'];
$proses   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan WHERE nik='$nik' AND status='proses'"))['c'];
$selesai  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan WHERE nik='$nik' AND status='selesai'"))['c'];
$recent   = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nik='$nik' ORDER BY id_pengaduan DESC LIMIT 3");

$nama_parts = explode(' ', $_SESSION['nama']);
$inisial = strtoupper(substr($nama_parts[0], 0, 1) . (isset($nama_parts[1]) ? substr($nama_parts[1], 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Pengaduan DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <nav class="main-nav">
        <div class="nav-brand">
            <div class="nav-logo">⚖️</div>
            <div class="nav-brand-text">
                <h1>Layanan Pengaduan DPRD</h1>
                <p>Kota Tasikmalaya</p>
            </div>
        </div>
        <div class="nav-right">
            <div class="user-pill">
                <div class="user-avatar"><?php echo $inisial; ?></div>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">Keluar</a>
        </div>
    </nav>

    <main class="dashboard-main">
        <div class="greeting-block">
            <p class="eyebrow">Dashboard Masyarakat</p>
            <h2>Halo, <?php echo htmlspecialchars(explode(' ', $_SESSION['nama'])[0]); ?> 👋</h2>
            <p>Selamat datang. Sampaikan laporan dan pantau status pengaduan Anda secara langsung.</p>
        </div>

        <div class="stats-row">
            <div class="stat-card menunggu">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">Menunggu Verifikasi</div>
                <div class="stat-value"><?php echo $menunggu; ?></div>
            </div>
            <div class="stat-card proses">
                <div class="stat-icon">🔄</div>
                <div class="stat-label">Sedang Diproses</div>
                <div class="stat-value"><?php echo $proses; ?></div>
            </div>
            <div class="stat-card selesai">
                <div class="stat-icon">✅</div>
                <div class="stat-label">Selesai Ditangani</div>
                <div class="stat-value"><?php echo $selesai; ?></div>
            </div>
        </div>

        <p class="section-title">Menu Layanan</p>
        <div class="menu-grid">
            <a href="tulis_pengaduan.php" class="menu-card primary">
                <span class="menu-arrow">&#8594;</span>
                <div class="menu-icon">✍️</div>
                <h3>Tulis Pengaduan</h3>
                <p>Laporkan keluhan terkait kinerja desa atau layanan publik di sekitar Anda.</p>
            </a>
            <a href="lihat_pengaduan.php" class="menu-card secondary">
                <span class="menu-arrow">&#8594;</span>
                <div class="menu-icon">📋</div>
                <h3>Riwayat Laporan</h3>
                <p>Lihat semua pengaduan yang telah Anda kirimkan beserta status terkininya.</p>
            </a>
        </div>

        <p class="section-title">Pengaduan Terbaru</p>
        <?php if (mysqli_num_rows($recent) > 0): ?>
            <?php while ($r = mysqli_fetch_array($recent)):
                $st      = $r['status'] == '0' ? 'menunggu' : $r['status'];
                $st_text = $r['status'] == '0' ? 'Menunggu' : ($r['status'] == 'proses' ? 'Diproses' : 'Selesai');
            ?>
                <div class="recent-item">
                    <div class="recent-dot <?php echo $st; ?>"></div>
                    <div class="recent-info">
                        <div class="recent-title"><?php echo htmlspecialchars($r['isi_laporan']); ?></div>
                        <div class="recent-date"><?php echo date('d M Y', strtotime($r['tgl_pengaduan'])); ?></div>
                    </div>
                    <span class="badge <?php echo $st; ?>"><?php echo $st_text; ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-recent">
                Belum ada pengaduan. <a href="tulis_pengaduan.php">Tulis pengaduan pertama Anda &rarr;</a>
            </div>
        <?php endif; ?>
    </main>

</body>

</html>