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
    <title>Tulis Pengaduan | DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <h1>Form Pengaduan Masyarakat</h1>
        <p><a href="dashboard.php" style="color:white;">Kembali</a></p>
    </nav>

    <div class="container" style="width: 500px; margin-top: 20px; background: white; padding: 20px; border-radius: 8px;">
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Tanggal Pengaduan</label>
            <input type="text" name="tgl" value="<?php echo date('Y-m-d'); ?>" readonly>

            <label>Isi Laporan Kinerja Desa</label>
            <textarea name="isi_laporan" rows="5" style="width: 100%;" required placeholder="Tuliskan keluhan Anda..."></textarea>

            <label>Bukti Foto</label>
            <input type="file" name="foto" accept="image/*" required>

            <button type="submit" name="kirim">Kirim Laporan</button>
        </form>
    </div>

    <?php
    include 'config/koneksi.php';
    if(isset($_POST['kirim'])){
        $tgl = $_POST['tgl'];
        $nik = $_SESSION['nik'];
        $isi = $_POST['isi_laporan'];
        
        // Proses Upload Foto
        $foto = $_FILES['foto']['name'];
        $tmp  = $_FILES['foto']['tmp_name'];
        $lokasi = "assets/img/";
        $nama_foto = rand(0,999).$foto; // Biar nama file tidak bentrok

        move_uploaded_file($tmp, $lokasi.$nama_foto);

        $query = mysqli_query($koneksi, "INSERT INTO pengaduan VALUES ('', '$tgl', '$nik', '$isi', '$nama_foto', '0')");

        if($query){
            echo "<script>alert('Laporan Berhasil Terkirim!'); window.location='lihat_pengaduan.php';</script>";
        } else {
            echo "<script>alert('Gagal Mengirim Laporan');</script>";
        }
    }
    ?>
</body>
</html>