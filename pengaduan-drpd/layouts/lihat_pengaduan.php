<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}
include 'config/koneksi.php';

$nik = $_SESSION['nik'];
$sql = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nik='$nik' ORDER BY id_pengaduan DESC");
$total = mysqli_num_rows($sql);

/* Ambil semua tanggapan milik user ini, kelompokkan per id_pengaduan */
$sql_tang = mysqli_query($koneksi, "
    SELECT t.*
    FROM tanggapan t
    INNER JOIN pengaduan p ON t.id_pengaduan = p.id_pengaduan
    WHERE p.nik = '$nik'
    ORDER BY t.tgl_tanggapan ASC
");
$tanggapan_map = [];
while ($t = mysqli_fetch_assoc($sql_tang)) {
    $tanggapan_map[$t['id_pengaduan']][] = $t;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengaduan | DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <nav class="main-nav">
        <div class="nav-brand">
            <div class="nav-logo">⚖️</div>
            <div class="nav-brand-text">
                <h1>Riwayat Laporan</h1>
                <p>DPRD Kota Tasikmalaya</p>
            </div>
        </div>
        <a href="dashboard.php" class="back-link">&#8592; Dashboard</a>
    </nav>

    <main class="riwayat-main">
        <div class="page-header-row">
            <div>
                <p class="eyebrow">Riwayat Pengaduan</p>
                <h2>Laporan Saya</h2>
                <p>Pantau perkembangan semua pengaduan yang telah Anda kirimkan. Total: <strong><?php echo $total; ?></strong> laporan.</p>
            </div>
            <a href="tulis_pengaduan.php" class="btn-new">&#43; Tulis Pengaduan</a>
        </div>

        <?php if ($total > 0): ?>

            <div class="filter-row">
                <button class="filter-btn active" onclick="filterStatus('semua',this)">Semua</button>
                <button class="filter-btn" onclick="filterStatus('menunggu',this)">&#9203; Menunggu</button>
                <button class="filter-btn" onclick="filterStatus('proses',this)">&#128260; Diproses</button>
                <button class="filter-btn" onclick="filterStatus('selesai',this)">&#9989; Selesai</button>
                <span class="result-count" id="countInfo"></span>
            </div>

            <div id="reportList">
                <?php while ($d = mysqli_fetch_array($sql)):
                    $id   = $d['id_pengaduan'];
                    $sk   = $d['status'] == '0' ? 'menunggu' : $d['status'];
                    $stxt = $d['status'] == '0' ? 'Menunggu Verifikasi'
                          : ($d['status'] == 'proses' ? 'Sedang Diproses' : 'Selesai Ditangani');
                    $tangs = $tanggapan_map[$id] ?? [];
                ?>
                    <div class="report-card" data-status="<?php echo $sk; ?>">
                        <div>
                            <div class="report-id">Laporan #<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></div>
                            <div class="report-content"><?php echo htmlspecialchars($d['isi_laporan']); ?></div>
                            <div class="report-meta">
                                <span class="meta-item">&#128197; <?php echo date('d M Y', strtotime($d['tgl_pengaduan'])); ?></span>
                                <span class="meta-item">&#128100; NIK: <?php echo htmlspecialchars($d['nik']); ?></span>
                            </div>

                            <?php if (!empty($tangs)): ?>
                                <div class="tanggapan-section-label">&#128172; Tanggapan DPRD</div>
                                <?php foreach ($tangs as $t): ?>
                                    <div class="tanggapan-box">
                                        <div class="tanggapan-box-header">
                                            <span class="tanggapan-box-badge">&#9878;&#65039; DPRD Tasikmalaya</span>
                                            <span class="tanggapan-box-tgl">&#128197; <?php echo date('d M Y', strtotime($t['tgl_tanggapan'])); ?></span>
                                        </div>
                                        <div class="tanggapan-box-isi"><?php echo nl2br(htmlspecialchars($t['tanggapan'])); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="report-right">
                            <span class="badge <?php echo $sk; ?>"><?php echo $stxt; ?></span>
                            <?php if (!empty($d['foto'])): ?>
                                <a href="assets/img/<?php echo htmlspecialchars($d['foto']); ?>" class="foto-link" target="_blank">&#128247; Lihat Foto</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div id="emptyFilter" class="hidden">
                <div class="empty-state">
                    <div class="empty-icon">&#128203;</div>
                    <h3>Tidak ada laporan</h3>
                    <p>Belum ada pengaduan dengan status yang dipilih.</p>
                </div>
            </div>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-icon">&#128203;</div>
                <h3>Belum Ada Laporan</h3>
                <p>Anda belum pernah membuat pengaduan. Mulai sampaikan aspirasi Anda kepada DPRD.</p>
                <a href="tulis_pengaduan.php" class="btn-primary" style="width:auto;display:inline-block;padding:12px 28px;margin-top:4px;">Tulis Pengaduan Pertama</a>
            </div>

        <?php endif; ?>
    </main>

    <script>
        function filterStatus(status, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            let visible = 0;
            document.querySelectorAll('.report-card').forEach(card => {
                const show = status === 'semua' || card.dataset.status === status;
                card.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            document.getElementById('emptyFilter').classList.toggle('hidden', visible > 0);
            document.getElementById('countInfo').textContent = status !== 'semua' ? visible + ' laporan' : '';
        }
        filterStatus('semua', document.querySelector('.filter-btn.active'));
    </script>

</body>

</html>