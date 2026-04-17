<?php
session_start();
include 'config/koneksi.php';

// Menghitung jumlah data untuk Stat Cards
$c_total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan"))['jml'];
$c_menunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan WHERE status='0'"))['jml'];
$c_proses = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan WHERE status='proses'"))['jml'];
$c_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pengaduan WHERE status='selesai'"))['jml'];

// Logika Filter Tabel
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
$where = "";
if($filter == 'menunggu') $where = "WHERE status='0'";
elseif($filter == 'proses') $where = "WHERE status='proses'";
elseif($filter == 'selesai') $where = "WHERE status='selesai'";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin | DPRD Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar-dprd">
            <div class="brand-logo">
                <div class="icon-logo"><i class="fas fa-balance-scale text-warning fa-lg"></i></div>
                <div>
                    <h5 class="mb-0 fw-bold">Panel Kerja DPRD</h5>
                    <small style="color: rgba(255,255,255,0.7); font-size: 12px;">Kota Tasikmalaya</small>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="admin_dashboard.php" class="active"><i class="fas fa-columns"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-inbox"></i> Pengaduan Masuk</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Data Masyarakat</a></li>
                <li><a href="#"><i class="fas fa-chart-line"></i> Laporan & Statistik</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Pengaturan</a></li>
            </ul>
            <div class="sidebar-footer">
                <small class="fw-bold d-block mb-1">Administrator</small>
                <small class="d-block mb-3" style="color: rgba(255,255,255,0.7);">DPRD Tasikmalaya</small>
                <a href="logout.php" class="btn btn-outline-light w-100" style="border-color: rgba(255,255,255,0.2);"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a>
            </div>
        </nav>

        <div class="main-panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Manajemen Pengaduan</h3>
                    <p class="text-muted">Kelola dan tindaklanjuti pengaduan masyarakat</p>
                </div>
                <button class="btn btn-light shadow-sm border"><i class="fas fa-calendar-alt text-primary"></i></button>
            </div>

            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card-stat d-flex align-items-center gap-3">
                        <div class="icon-box icon-purple"><i class="fas fa-folder-open"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0"><?php echo $c_total; ?></h3>
                            <small class="text-muted">Total Pengaduan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-stat d-flex align-items-center gap-3">
                        <div class="icon-box icon-orange"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0"><?php echo $c_menunggu; ?></h3>
                            <small class="text-muted">Menunggu Verifikasi</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-stat d-flex align-items-center gap-3">
                        <div class="icon-box icon-blue"><i class="fas fa-sync-alt"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0"><?php echo $c_proses; ?></h3>
                            <small class="text-muted">Sedang Diproses</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-stat d-flex align-items-center gap-3">
                        <div class="icon-box icon-green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0"><?php echo $c_selesai; ?></h3>
                            <small class="text-muted">Selesai Ditangani</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-ui">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">Daftar Pengaduan Masuk</h5>
                        <small class="text-muted">Semua laporan yang diterima dari masyarakat</small>
                    </div>
                    <div class="btn-group shadow-sm">
                        <a href="admin_dashboard.php?filter=semua" class="btn btn-filter btn-light border <?php if($filter=='semua') echo 'bg-white shadow-sm'; ?>">Semua</a>
                        <a href="admin_dashboard.php?filter=menunggu" class="btn btn-filter btn-light border <?php if($filter=='menunggu') echo 'text-warning fw-bold'; ?>"><i class="fas fa-hourglass-half me-1"></i> Menunggu</a>
                        <a href="admin_dashboard.php?filter=proses" class="btn btn-filter btn-light border <?php if($filter=='proses') echo 'text-primary fw-bold'; ?>"><i class="fas fa-sync-alt me-1"></i> Proses</a>
                        <a href="admin_dashboard.php?filter=selesai" class="btn btn-filter btn-danger border <?php if($filter=='selesai') echo 'bg-danger text-white'; else echo 'text-danger bg-white'; ?>"><i class="fas fa-check me-1"></i> Selesai</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="text-muted" style="font-size: 13px;">
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL</th>
                                <th>NIK PELAPOR</th>
                                <th>ISI LAPORAN</th>
                                <th>FOTO</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sql = mysqli_query($koneksi, "SELECT * FROM pengaduan $where ORDER BY tgl_pengaduan DESC");
                            if(mysqli_num_rows($sql) > 0) {
                                while($d = mysqli_fetch_array($sql)){
                            ?>
                            <tr>
                                <td class="fw-bold text-muted"><?php echo $no++; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($d['tgl_pengaduan'])); ?></td>
                                <td class="fw-semibold"><?php echo $d['nik']; ?></td>
                                <td><?php echo substr($d['isi_laporan'], 0, 30); ?>...</td>
                                <td><a href="assets/img/<?php echo $d['foto']; ?>" target="_blank" class="btn btn-sm btn-light border"><i class="fas fa-image text-secondary"></i></a></td>
                                <td>
                                    <?php 
                                    if($d['status'] == '0') echo '<span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-circle" style="font-size:8px;"></i> Menunggu</span>';
                                    elseif($d['status'] == 'proses') echo '<span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fas fa-circle" style="font-size:8px;"></i> Proses</span>';
                                    else echo '<span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-circle" style="font-size:8px;"></i> Selesai</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php if($d['status'] == '0'): ?>
                                        <a href="update_status.php?id=<?php echo $d['id_pengaduan']; ?>&status=proses" class="btn btn-sm btn-primary px-3 rounded-pill">Verifikasi & Proses</a>
                                    <?php elseif($d['status'] == 'proses'): ?>
                                        <a href="beri_tanggapan.php?id=<?php echo $d['id_pengaduan']; ?>" class="btn btn-sm btn-success px-3 rounded-pill"><i class="fas fa-reply me-1"></i> Tanggapi</a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-light border text-muted px-3 rounded-pill" disabled><i class="fas fa-check-double"></i> Ditutup</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data ditemukan.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>