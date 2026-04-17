<?php
session_start();
include 'config/koneksi.php';

/* ===== HANDLE KIRIM TANGGAPAN ===== */
if (isset($_POST['kirim_tanggapan'])) {
    $id_pengaduan = (int) $_POST['id_pengaduan'];
    $tgl          = date('Y-m-d');
    $tanggapan    = mysqli_real_escape_string($koneksi, $_POST['tanggapan']);
    mysqli_query($koneksi, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan) VALUES ('$id_pengaduan','$tgl','$tanggapan')");
    mysqli_query($koneksi, "UPDATE pengaduan SET status='selesai' WHERE id_pengaduan='$id_pengaduan'");
    header("location:admin_dashboard.php?page=pengaduan&notif=tanggapan");
    exit;
}

/* ===== STATISTIK ===== */
$total    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan"))['c'];
$menunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan WHERE status='0'"))['c'];
$proses   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan WHERE status='proses'"))['c'];
$selesai  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM pengaduan WHERE status='selesai'"))['c'];
$total_masyarakat = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as c FROM masyarakat"))['c'];

/* ===== DATA PENGADUAN ===== */
$sql = mysqli_query($koneksi, "
    SELECT p.*, m.nama
    FROM pengaduan p
    LEFT JOIN masyarakat m ON p.nik = m.nik
    ORDER BY FIELD(p.status,'0','proses','selesai'), p.tgl_pengaduan DESC
");

/* Ambil semua tanggapan, kelompokkan per id_pengaduan */
$sql_tang_all  = mysqli_query($koneksi, "SELECT * FROM tanggapan ORDER BY tgl_tanggapan ASC");
$tanggapan_map = [];
while ($t = mysqli_fetch_assoc($sql_tang_all)) {
    $tanggapan_map[$t['id_pengaduan']][] = $t;
}

/* ===== DATA MASYARAKAT ===== */
$sql_masyarakat = mysqli_query($koneksi, "
    SELECT m.*,
           COUNT(p.id_pengaduan)      as total_laporan,
           SUM(p.status='0')          as laporan_menunggu,
           SUM(p.status='proses')     as laporan_proses,
           SUM(p.status='selesai')    as laporan_selesai
    FROM masyarakat m
    LEFT JOIN pengaduan p ON m.nik = p.nik
    GROUP BY m.nik
    ORDER BY total_laporan DESC
");

/* ===== STATISTIK BULANAN ===== */
$sql_bulanan = mysqli_query($koneksi, "
    SELECT DATE_FORMAT(tgl_pengaduan,'%b %Y') as bulan,
           DATE_FORMAT(tgl_pengaduan,'%Y-%m') as bulan_sort,
           COUNT(*) as total,
           SUM(status='selesai') as selesai
    FROM pengaduan
    WHERE tgl_pengaduan >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY bulan_sort, bulan
    ORDER BY bulan_sort ASC
");
$stat_bulanan = [];
while ($r = mysqli_fetch_assoc($sql_bulanan)) $stat_bulanan[] = $r;

/* ===== PENGATURAN ===== */
$setting_success = '';
if (isset($_POST['simpan_pengaturan'])) {
    $_SESSION['pengaturan'] = [
        'nama_instansi' => $_POST['nama_instansi'] ?? '',
        'email_kontak'  => $_POST['email_kontak']  ?? '',
        'telp_kontak'   => $_POST['telp_kontak']   ?? '',
    ];
    $setting_success = 'Pengaturan berhasil disimpan.';
}
$pengaturan = $_SESSION['pengaturan'] ?? [
    'nama_instansi' => 'DPRD Kota Tasikmalaya',
    'email_kontak'  => 'dprd@tasikmalayakota.go.id',
    'telp_kontak'   => '0265-XXXXXX',
];

$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-badge">&#9878;&#65039;</div>
                <h2>Panel Kerja DPRD</h2>
                <p>Kota Tasikmalaya</p>
            </div>
            <nav class="sidebar-nav">
                <a class="nav-item <?php echo $page === 'dashboard' ? 'active' : ''; ?>" href="?page=dashboard">
                    <span class="nav-icon">&#128202;</span><span>Dashboard</span>
                </a>
                <a class="nav-item <?php echo $page === 'pengaduan' ? 'active' : ''; ?>" href="?page=pengaduan">
                    <span class="nav-icon">&#128203;</span><span>Pengaduan</span>
                    <?php if ($menunggu > 0): ?>
                        <span class="nav-badge"><?php echo $menunggu; ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-item <?php echo $page === 'masyarakat' ? 'active' : ''; ?>" href="?page=masyarakat">
                    <span class="nav-icon">&#128101;</span><span>Data Masyarakat</span>
                </a>
                <a class="nav-item <?php echo $page === 'statistik' ? 'active' : ''; ?>" href="?page=statistik">
                    <span class="nav-icon">&#128200;</span><span>Statistik</span>
                </a>
                <a class="nav-item <?php echo $page === 'pengaturan' ? 'active' : ''; ?>" href="?page=pengaturan">
                    <span class="nav-icon">&#9881;&#65039;</span><span>Pengaturan</span>
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="admin-info">
                    <strong>Administrator</strong>
                    <span>DPRD Tasikmalaya</span>
                </div>
                <a href="logout.php" class="sidebar-logout">Keluar dari Sistem</a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="admin-content">

            <div class="top-bar">
                <div>
                    <?php
                    $titles = [
                        'dashboard'  => ['Manajemen Pengaduan',   'Ringkasan laporan masyarakat hari ini'],
                        'pengaduan'  => ['Daftar Pengaduan',       'Kelola dan tindaklanjuti semua pengaduan masuk'],
                        'masyarakat' => ['Data Masyarakat',        'Informasi akun dan riwayat laporan masyarakat'],
                        'statistik'  => ['Statistik &amp; Laporan', 'Analisis data pengaduan masyarakat'],
                        'pengaturan' => ['Pengaturan Sistem',      'Konfigurasi sistem pengaduan DPRD'],
                    ];
                    $pt = $titles[$page] ?? $titles['dashboard'];
                    ?>
                    <h1><?php echo $pt[0]; ?></h1>
                    <p><?php echo $pt[1]; ?></p>
                </div>
                <div class="date-badge">&#128197; <?php echo date('d M Y'); ?></div>
            </div>

            <div class="admin-main">

                <?php if (isset($_GET['notif']) && $_GET['notif'] === 'tanggapan'): ?>
                    <div class="alert-success-sm">&#9989; Tanggapan berhasil dikirim dan laporan ditandai selesai.</div>
                <?php endif; ?>

                <!-- DASHBOARD -->
                <?php if ($page === 'dashboard'): ?>

                    <div class="admin-stats">
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon total">&#128193;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $total; ?></div>
                            <div class="admin-stat-label">Total Pengaduan</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon menunggu">&#9203;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $menunggu; ?></div>
                            <div class="admin-stat-label">Menunggu Verifikasi</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon proses">&#128260;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $proses; ?></div>
                            <div class="admin-stat-label">Sedang Diproses</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon selesai">&#9989;</div>
                                <?php if ($total > 0): ?>
                                    <span class="stat-pct"><?php echo round(($selesai / $total) * 100); ?>%</span>
                                <?php endif; ?>
                            </div>
                            <div class="admin-stat-value"><?php echo $selesai; ?></div>
                            <div class="admin-stat-label">Selesai Ditangani</div>
                        </div>
                    </div>

                    <div class="section-header">
                        <div>
                            <h2>Pengaduan Terbaru</h2>
                            <p>5 laporan yang baru masuk</p>
                        </div>
                        <a href="?page=pengaduan" class="section-link">Lihat Semua &rarr;</a>
                    </div>

                    <?php
                    $sql_recent = mysqli_query($koneksi, "
                SELECT p.*, m.nama FROM pengaduan p
                LEFT JOIN masyarakat m ON p.nik=m.nik
                ORDER BY p.tgl_pengaduan DESC LIMIT 5
            ");
                    ?>
                    <div class="table-card">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Pelapor</th>
                                        <th>Isi Laporan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($d = mysqli_fetch_array($sql_recent)):
                                        $sk   = $d['status'] === '0' ? 'menunggu' : $d['status'];
                                        $stxt = $d['status'] === '0' ? 'Menunggu' : ($d['status'] === 'proses' ? 'Proses' : 'Selesai');
                                    ?>
                                        <tr>
                                            <td><span class="tgl"><?php echo date('d M Y', strtotime($d['tgl_pengaduan'])); ?></span></td>
                                            <td>
                                                <div class="pelapor-name"><?php echo htmlspecialchars($d['nama'] ?? '&mdash;'); ?></div>
                                                <span class="nik-badge"><?php echo htmlspecialchars($d['nik']); ?></span>
                                            </td>
                                            <td>
                                                <div class="laporan-text"><?php echo htmlspecialchars(mb_substr($d['isi_laporan'], 0, 70)) . (mb_strlen($d['isi_laporan']) > 70 ? '&hellip;' : ''); ?></div>
                                            </td>
                                            <td><span class="badge <?php echo $sk; ?>"><?php echo $stxt; ?></span></td>
                                            <td>
                                                <?php if ($d['status'] === '0'): ?>
                                                    <a class="action-btn btn-proses" href="update_status.php?id=<?php echo $d['id_pengaduan']; ?>&status=proses">&rarr; Proses</a>
                                                <?php elseif ($d['status'] === 'proses'): ?>
                                                    <a class="action-btn btn-selesai" href="update_status.php?id=<?php echo $d['id_pengaduan']; ?>&status=selesai">&#10003; Selesai</a>
                                                <?php else: ?>
                                                    <span style="color:#16a34a;">&#9989;</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- PENGADUAN — dengan tabel tanggapan -->
                <?php elseif ($page === 'pengaduan'): ?>

                    <div class="admin-stats">
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon total">&#128193;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $total; ?></div>
                            <div class="admin-stat-label">Total</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon menunggu">&#9203;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $menunggu; ?></div>
                            <div class="admin-stat-label">Menunggu</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon proses">&#128260;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $proses; ?></div>
                            <div class="admin-stat-label">Diproses</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon selesai">&#9989;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $selesai; ?></div>
                            <div class="admin-stat-label">Selesai</div>
                        </div>
                    </div>

                    <div class="section-header">
                        <div>
                            <h2>Daftar Pengaduan Masuk</h2>
                            <p>Total <?php echo $total; ?> laporan &mdash; klik <strong>&#128172; Tanggapi</strong> untuk memberi tanggapan resmi DPRD.</p>
                        </div>
                        <div class="admin-filter-row">
                            <button class="admin-filter-btn active" onclick="adminFilter('semua',this)">Semua</button>
                            <button class="admin-filter-btn" onclick="adminFilter('menunggu',this)">&#9203; Menunggu</button>
                            <button class="admin-filter-btn" onclick="adminFilter('proses',this)">&#128260; Proses</button>
                            <button class="admin-filter-btn" onclick="adminFilter('selesai',this)">&#9989; Selesai</button>
                        </div>
                    </div>

                    <div class="table-card">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Pelapor</th>
                                        <th>Isi Laporan</th>
                                        <th>Foto</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="adminTable">
                                    <?php
                                    mysqli_data_seek($sql, 0);
                                    $no = 1;
                                    while ($d = mysqli_fetch_array($sql)):
                                        $sk    = $d['status'] === '0' ? 'menunggu' : $d['status'];
                                        $stxt  = $d['status'] === '0' ? 'Menunggu' : ($d['status'] === 'proses' ? 'Proses' : 'Selesai');
                                        $id    = $d['id_pengaduan'];
                                        $tangs = $tanggapan_map[$id] ?? [];
                                    ?>
                                        <!-- Row utama -->
                                        <tr class="row-main" data-status="<?php echo $sk; ?>">
                                            <td class="id-num"><?php echo $no++; ?></td>
                                            <td><span class="tgl"><?php echo date('d M Y', strtotime($d['tgl_pengaduan'])); ?></span></td>
                                            <td>
                                                <div class="pelapor-name"><?php echo htmlspecialchars($d['nama'] ?? '&mdash;'); ?></div>
                                                <span class="nik-badge"><?php echo htmlspecialchars($d['nik']); ?></span>
                                            </td>
                                            <td>
                                                <div class="laporan-text" title="<?php echo htmlspecialchars($d['isi_laporan']); ?>">
                                                    <?php echo htmlspecialchars(mb_substr($d['isi_laporan'], 0, 65)) . (mb_strlen($d['isi_laporan']) > 65 ? '&hellip;' : ''); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($d['foto'])): ?>
                                                    <a class="foto-link" href="assets/img/<?php echo htmlspecialchars($d['foto']); ?>" target="_blank">&#128247; Lihat</a>
                                                <?php else: ?>
                                                    <span style="color:#9ca3af;font-size:12px;">&mdash;</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge <?php echo $sk; ?>"><?php echo $stxt; ?></span></td>
                                            <td>
                                                <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-start;">
                                                    <?php if ($d['status'] === '0'): ?>
                                                        <a class="action-btn btn-proses" href="update_status.php?id=<?php echo $id; ?>&status=proses">&rarr; Proses</a>
                                                    <?php elseif ($d['status'] === 'proses'): ?>
                                                        <a class="action-btn btn-selesai" href="update_status.php?id=<?php echo $id; ?>&status=selesai">&#10003; Selesai</a>
                                                    <?php else: ?>
                                                        <span style="color:#16a34a;">&#9989; Selesai</span>
                                                    <?php endif; ?>
                                                    <button class="action-btn btn-tanggapan" onclick="toggleTanggapan(<?php echo $id; ?>)">
                                                        &#128172; Tanggapi
                                                        <?php if (count($tangs) > 0): ?>
                                                            <span class="tang-count"><?php echo count($tangs); ?></span>
                                                        <?php endif; ?>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Row tanggapan (collapsed) -->
                                        <tr class="tanggapan-panel" id="tang-<?php echo $id; ?>" data-status="<?php echo $sk; ?>" style="display:none;">
                                            <td colspan="7">
                                                <div class="tanggapan-inner">

                                                    <!-- Riwayat tanggapan -->
                                                    <div>
                                                        <div class="tanggapan-header">&#128172; Riwayat Tanggapan DPRD</div>
                                                        <?php if (empty($tangs)): ?>
                                                            <p class="tanggapan-empty">Belum ada tanggapan untuk laporan ini.</p>
                                                        <?php else: ?>
                                                            <div class="tanggapan-list">
                                                                <?php foreach ($tangs as $t): ?>
                                                                    <div class="tanggapan-item">
                                                                        <div class="tanggapan-meta">
                                                                            <span class="tanggapan-badge">&#9878;&#65039; DPRD Tasikmalaya</span>
                                                                            <span class="tanggapan-tgl">&#128197; <?php echo date('d M Y', strtotime($t['tgl_tanggapan'])); ?></span>
                                                                        </div>
                                                                        <div class="tanggapan-isi"><?php echo nl2br(htmlspecialchars($t['tanggapan'])); ?></div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Form tanggapan baru -->
                                                    <div>
                                                        <div class="tanggapan-header" style="margin-bottom:10px;">&#9997;&#65039; Kirim Tanggapan Baru</div>
                                                        <form method="POST" action="?page=pengaduan" class="form-tanggapan">
                                                            <input type="hidden" name="id_pengaduan" value="<?php echo $id; ?>">
                                                            <textarea name="tanggapan" rows="3"
                                                                placeholder="Tulis tanggapan resmi DPRD terhadap laporan ini&hellip;"
                                                                required></textarea>
                                                            <div class="form-tanggapan-actions">
                                                                <button type="submit" name="kirim_tanggapan" class="btn-kirim-tanggapan">
                                                                    &#9989; Kirim &amp; Selesaikan Laporan
                                                                </button>
                                                                <button type="button" class="btn-batal-tanggapan"
                                                                    onclick="toggleTanggapan(<?php echo $id; ?>)">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>

                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- DATA MASYARAKAT -->
                <?php elseif ($page === 'masyarakat'): ?>

                    <div class="admin-stats" style="grid-template-columns:repeat(2,1fr);max-width:480px;margin-bottom:24px;">
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon total">&#128101;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $total_masyarakat; ?></div>
                            <div class="admin-stat-label">Total Akun Terdaftar</div>
                        </div>
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon selesai">&#128203;</div>
                            </div>
                            <div class="admin-stat-value"><?php echo $total; ?></div>
                            <div class="admin-stat-label">Total Laporan Masuk</div>
                        </div>
                    </div>

                    <div class="search-bar">
                        <span>&#128269;</span>
                        <input type="text" id="searchMasyarakat" placeholder="Cari nama, NIK, atau nomor telepon&hellip;" oninput="filterMasyarakat()">
                    </div>

                    <div class="masyarakat-grid" id="masyarakatGrid">
                        <?php
                        mysqli_data_seek($sql_masyarakat, 0);
                        while ($m = mysqli_fetch_assoc($sql_masyarakat)):
                            $parts   = explode(' ', $m['nama']);
                            $inisial = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        ?>
                            <div class="masyarakat-card" data-search="<?php echo strtolower($m['nama'] . ' ' . $m['nik'] . ' ' . $m['telp']); ?>">
                                <div class="masyarakat-header">
                                    <div class="masyarakat-avatar"><?php echo $inisial; ?></div>
                                    <div>
                                        <div class="masyarakat-nama"><?php echo htmlspecialchars($m['nama']); ?></div>
                                        <div class="masyarakat-nik">NIK: <?php echo htmlspecialchars($m['nik']); ?></div>
                                        <div class="masyarakat-telp">&#128222; <?php echo htmlspecialchars($m['telp'] ?: '&mdash;'); ?></div>
                                    </div>
                                </div>
                                <div class="laporan-mini">
                                    <span class="laporan-mini-item total">&#128203; <?php echo $m['total_laporan']; ?> laporan</span>
                                    <?php if ($m['laporan_menunggu'] > 0): ?><span class="laporan-mini-item menunggu">&#9203; <?php echo $m['laporan_menunggu']; ?></span><?php endif; ?>
                                    <?php if ($m['laporan_proses']   > 0): ?><span class="laporan-mini-item proses">&#128260; <?php echo $m['laporan_proses']; ?></span><?php endif; ?>
                                    <?php if ($m['laporan_selesai']  > 0): ?><span class="laporan-mini-item selesai">&#9989; <?php echo $m['laporan_selesai']; ?></span><?php endif; ?>
                                    <?php if ($m['total_laporan'] == 0): ?><span class="laporan-mini-item total" style="color:#9ca3af;">Belum ada laporan</span><?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div id="noMasyarakat" style="display:none;text-align:center;padding:48px;color:#9ca3af;font-size:14px;">
                        Tidak ada data yang cocok dengan pencarian.
                    </div>


                    <!-- STATISTIK -->
                <?php elseif ($page === 'statistik'): ?>

                    <div class="overview-grid">
                        <div class="overview-item">
                            <div class="overview-num"><?php echo $total; ?></div>
                            <div class="overview-lbl">Total Pengaduan</div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-num"><?php echo $total_masyarakat; ?></div>
                            <div class="overview-lbl">Pengguna Terdaftar</div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-num"><?php echo $total > 0 ? round(($selesai / $total) * 100) : 0; ?>%</div>
                            <div class="overview-lbl">Tingkat Penyelesaian</div>
                        </div>
                    </div>

                    <div class="stat-section">
                        <h3>&#128202; Tren Pengaduan 12 Bulan Terakhir</h3>
                        <?php if (empty($stat_bulanan)): ?>
                            <p style="color:#9ca3af;font-size:14px;">Belum ada data pengaduan.</p>
                        <?php else:
                            $max_val = max(array_column($stat_bulanan, 'total')) ?: 1;
                        ?>
                            <div class="chart-bars">
                                <?php foreach ($stat_bulanan as $b): ?>
                                    <div class="chart-bar-wrap">
                                        <div class="chart-bar total"
                                            style="height:<?php echo round(($b['total'] / $max_val) * 150); ?>px"
                                            data-val="<?php echo $b['total']; ?> laporan"></div>
                                        <div class="chart-label"><?php echo substr($b['bulan'], 0, 3); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="chart-legend">
                                <div class="legend-item">
                                    <div class="legend-dot" style="background:#2563eb;"></div>Total Laporan per Bulan
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="stat-section">
                        <h3>&#128197; Distribusi Status Pengaduan</h3>
                        <?php
                        $items = [
                            ['label' => 'Menunggu Verifikasi', 'val' => $menunggu, 'color' => '#ea580c'],
                            ['label' => 'Sedang Diproses',    'val' => $proses,  'color' => '#2563eb'],
                            ['label' => 'Selesai Ditangani',  'val' => $selesai, 'color' => '#16a34a'],
                        ];
                        foreach ($items as $it):
                            $pct = $total > 0 ? round(($it['val'] / $total) * 100) : 0;
                        ?>
                            <div class="progress-row">
                                <div class="progress-label"><?php echo $it['label']; ?></div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width:<?php echo $pct; ?>%;background:<?php echo $it['color']; ?>;"></div>
                                </div>
                                <div class="progress-pct"><?php echo $pct; ?>%</div>
                            </div>
                        <?php endforeach; ?>
                        <p style="font-size:12px;color:#9ca3af;margin-top:16px;">*Berdasarkan <?php echo $total; ?> total pengaduan.</p>
                    </div>


                    <!-- PENGATURAN -->
                <?php elseif ($page === 'pengaturan'): ?>

                    <?php if ($setting_success): ?>
                        <div class="alert-success-sm">&#9989; <?php echo $setting_success; ?></div>
                    <?php endif; ?>

                    <div class="pengaturan-grid">
                        <div class="pengaturan-card">
                            <h3>&#9881;&#65039; Informasi Instansi</h3>
                            <p class="sub">Perbarui identitas dan kontak resmi instansi.</p>
                            <form method="POST" action="?page=pengaturan">
                                <div class="p-form-group">
                                    <label>Nama Instansi</label>
                                    <input type="text" name="nama_instansi" value="<?php echo htmlspecialchars($pengaturan['nama_instansi']); ?>" placeholder="Nama instansi resmi">
                                </div>
                                <div class="p-form-group">
                                    <label>Email Kontak</label>
                                    <input type="email" name="email_kontak" value="<?php echo htmlspecialchars($pengaturan['email_kontak']); ?>" placeholder="email@instansi.go.id">
                                </div>
                                <div class="p-form-group">
                                    <label>Nomor Telepon</label>
                                    <input type="text" name="telp_kontak" value="<?php echo htmlspecialchars($pengaturan['telp_kontak']); ?>" placeholder="0xxx-xxxxx">
                                </div>
                                <hr class="p-divider">
                                <button type="submit" name="simpan_pengaturan" class="btn-save">&#128190; Simpan Pengaturan</button>
                            </form>
                        </div>

                        <div class="pengaturan-card">
                            <h3>&#8505;&#65039; Informasi Sistem</h3>
                            <p class="sub">Detail teknis dan status sistem saat ini.</p>
                            <div class="info-row">
                                <div class="info-icon">&#128187;</div>
                                <div>
                                    <div class="info-key">Versi PHP</div>
                                    <div class="info-val"><?php echo phpversion(); ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon">&#128197;</div>
                                <div>
                                    <div class="info-key">Tanggal Server</div>
                                    <div class="info-val"><?php echo date('d M Y, H:i'); ?></div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon">&#128203;</div>
                                <div>
                                    <div class="info-key">Total Pengaduan</div>
                                    <div class="info-val"><?php echo $total; ?> laporan</div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon">&#128101;</div>
                                <div>
                                    <div class="info-key">Total Pengguna</div>
                                    <div class="info-val"><?php echo $total_masyarakat; ?> akun terdaftar</div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon">&#9989;</div>
                                <div>
                                    <div class="info-key">Status Sistem</div>
                                    <div class="info-val" style="color:#16a34a;">&#9679; Berjalan Normal</div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <script>
        /* Filter tabel pengaduan */
        function adminFilter(status, btn) {
            document.querySelectorAll('.admin-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('#adminTable .row-main').forEach(row => {
                const show = status === 'semua' || row.dataset.status === status;
                row.classList.toggle('hidden', !show);
            });
            // Sembunyikan semua panel tanggapan saat filter berubah
            document.querySelectorAll('.tanggapan-panel').forEach(p => p.style.display = 'none');
        }

        /* Buka / tutup panel tanggapan */
        function toggleTanggapan(id) {
            const panel = document.getElementById('tang-' + id);
            if (!panel) return;
            const isOpen = panel.style.display !== 'none';
            // Tutup semua panel lain dulu
            document.querySelectorAll('.tanggapan-panel').forEach(p => p.style.display = 'none');
            if (!isOpen) {
                panel.style.display = 'table-row';
                setTimeout(() => panel.querySelector('textarea')?.focus(), 100);
            }
        }

        /* Filter kartu masyarakat */
        function filterMasyarakat() {
            const q = document.getElementById('searchMasyarakat').value.toLowerCase();
            const cs = document.querySelectorAll('.masyarakat-card');
            let visible = 0;
            cs.forEach(c => {
                const match = c.dataset.search.includes(q);
                c.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            const el = document.getElementById('noMasyarakat');
            if (el) el.style.display = visible === 0 ? 'block' : 'none';
        }
    </script>

</body>

</html>