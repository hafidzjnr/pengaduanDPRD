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
    <title>Tulis Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-bullhorn me-2"></i>Pengaduan DPRD</a>
            <div class="ms-auto">
                <a class="btn btn-light btn-sm px-3 fw-bold" href="dashboard.php"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h4 class="fw-bold"><i class="fas fa-pen text-primary me-2"></i>Form Pengaduan</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Pengaduan</label>
                                <input type="text" name="tgl" class="form-control bg-light" value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Isi Laporan Kinerja Desa</label>
                                <textarea name="isi_laporan" class="form-control" rows="5" required placeholder="Jelaskan secara detail kejadian atau keluhan Anda..."></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Bukti Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                                <div class="form-text">Format didukung: JPG, PNG, JPEG.</div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="kirim" class="btn btn-primary py-2 fw-bold rounded-3 shadow-sm"><i class="fas fa-paper-plane me-2"></i>Kirim Laporan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'config/koneksi.php';
    if(isset($_POST['kirim'])){
        $tgl = $_POST['tgl'];
        $nik = $_SESSION['nik'];
        $isi = $_POST['isi_laporan'];
        
        $foto = $_FILES['foto']['name'];
        $tmp  = $_FILES['foto']['tmp_name'];
        $lokasi = "assets/img/";
        $nama_foto = rand(0,9999)."-".$foto; 

        move_uploaded_file($tmp, $lokasi.$nama_foto);

        // Perbaikan Query Auto Increment
        $query = mysqli_query($koneksi, "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) VALUES ('$tgl', '$nik', '$isi', '$nama_foto', '0')");

        if($query){
            // SweetAlert Laporan Berhasil
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Laporan Terkirim!',
                    text: 'Pengaduan Anda berhasil masuk ke sistem kami.',
                    confirmButtonColor: '#0d6efd'
                }).then((result) => {
                    window.location='lihat_pengaduan.php';
                });
            </script>";
        } else {
            // SweetAlert Laporan Gagal
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim!',
                    text: 'Terjadi kesalahan sistem, silakan coba lagi.',
                    confirmButtonColor: '#0d6efd'
                });
            </script>";
        }
    }
    ?>
</body>
</html>