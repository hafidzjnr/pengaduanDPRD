<?php
session_start();
include 'config/koneksi.php';
$id_pengaduan = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE id_pengaduan='$id_pengaduan'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Tanggapan | DPRD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar sidebar-admin shadow">
            <div class="brand-logo">
                <div class="icon-logo"><i class="fas fa-balance-scale text-warning fa-lg"></i></div>
                <div><h5 class="mb-0 fw-bold">Panel Kerja</h5></div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Kembali</a></li>
            </ul>
        </nav>

        <div class="main-panel">
            <h3 class="fw-bold mb-4">Tindak Lanjut Laporan</h3>
            <div class="card-ui p-4">
                <div class="bg-light p-3 rounded-3 mb-4 border">
                    <small class="text-muted fw-bold d-block mb-2"><i class="fas fa-user text-secondary"></i> NIK: <?php echo $data['nik']; ?></small>
                    <p class="mb-0 fs-6">"<?php echo $data['isi_laporan']; ?>"</p>
                </div>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="text" name="tgl" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggapan Resmi DPRD</label>
                        <textarea name="tanggapan" class="form-control" rows="5" required placeholder="Tuliskan tindak lanjut..."></textarea>
                    </div>
                    <button type="submit" name="kirim_tanggapan" class="btn btn-success py-2 fw-bold rounded-3"><i class="fas fa-check-circle me-2"></i>Selesaikan Laporan</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    if(isset($_POST['kirim_tanggapan'])){
        $tgl = $_POST['tgl'];
        $tanggapan = $_POST['tanggapan'];
        mysqli_query($koneksi, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan) VALUES ('$id_pengaduan', '$tgl', '$tanggapan')");
        mysqli_query($koneksi, "UPDATE pengaduan SET status='selesai' WHERE id_pengaduan='$id_pengaduan'");
        echo "<script>Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Laporan diselesaikan.'}).then((result) => { window.location='admin_dashboard.php'; });</script>";
    }
    ?>
</body>
</html>