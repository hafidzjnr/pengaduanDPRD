<?php
session_start();
if(!isset($_SESSION['login'])){
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Masyarakat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-bullhorn me-2"></i>Pengaduan DPRD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link text-white"><i class="fas fa-user-circle me-1"></i> Hai, <?php echo $_SESSION['nama']; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger btn-sm text-white ms-lg-3 px-3" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Keluar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-5 text-white bg-primary rounded-3 shadow">
                    <h2 class="fw-bold">Selamat Datang di Layanan Pengaduan!</h2>
                    <p class="fs-5">Suarakan keluhan dan aspirasi Anda terkait kinerja desa kepada DPRD Kota Tasikmalaya.</p>
                </div>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-5">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                        <h4 class="card-title fw-bold">Tulis Pengaduan</h4>
                        <p class="card-text text-muted">Sampaikan laporan beserta bukti foto pendukung.</p>
                        <a href="tulis_pengaduan.php" class="btn btn-outline-primary px-4 rounded-pill">Buat Laporan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-5">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-history fa-2x"></i>
                        </div>
                        <h4 class="card-title fw-bold">Riwayat Pengaduan</h4>
                        <p class="card-text text-muted">Pantau status laporan yang sudah Anda kirimkan.</p>
                        <a href="lihat_pengaduan.php" class="btn btn-outline-success px-4 rounded-pill">Cek Riwayat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>