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
    <title>Beri Tanggapan | DPRD Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f4f6f9;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold"><i class="fas fa-reply-all text-success me-2"></i>Tanggapi Laporan</h4>
                        <a href="admin_dashboard.php" class="btn btn-light btn-sm fw-bold"><i class="fas fa-times"></i> Batal</a>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="bg-light p-3 rounded-3 mb-4 border">
                            <small class="text-muted fw-bold d-block mb-2">DETAIL LAPORAN (NIK: <?php echo $data['nik']; ?>)</small>
                            <p class="mb-0 fs-6">"<?php echo $data['isi_laporan']; ?>"</p>
                        </div>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Tanggapan</label>
                                <input type="text" name="tgl" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tulis Tanggapan Resmi DPRD</label>
                                <textarea name="tanggapan" class="form-control" rows="5" required placeholder="Tuliskan tindak lanjut atau balasan untuk pelapor..."></textarea>
                            </div>
                            <button type="submit" name="kirim_tanggapan" class="btn btn-success w-100 py-2 fw-bold rounded-3 shadow-sm"><i class="fas fa-check-circle me-2"></i>Kirim & Selesaikan Laporan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if(isset($_POST['kirim_tanggapan'])){
        $tgl = $_POST['tgl'];
        $tanggapan = $_POST['tanggapan'];

        // 1. Masukkan ke tabel tanggapan
        $insert_tanggapan = mysqli_query($koneksi, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan) VALUES ('$id_pengaduan', '$tgl', '$tanggapan')");
        
        // 2. Ubah status pengaduan menjadi 'selesai'
        $update_status = mysqli_query($koneksi, "UPDATE pengaduan SET status='selesai' WHERE id_pengaduan='$id_pengaduan'");

        if($insert_tanggapan && $update_status){
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Tanggapan terkirim dan laporan dinyatakan selesai.',
                    confirmButtonColor: '#22c55e'
                }).then((result) => {
                    window.location='admin_dashboard.php';
                });
            </script>";
        } else {
             echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan tanggapan.',
                    confirmButtonColor: '#22c55e'
                });
            </script>";
        }
    }
    ?>
</body>
</html>