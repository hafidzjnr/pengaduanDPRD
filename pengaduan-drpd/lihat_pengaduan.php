<?php
session_start();
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pengaduan</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        table { border-collapse: collapse; width: 80%; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #333; color: white; }
        .status { font-weight: bold; color: orange; }
    </style>
</head>
<body>
    <nav>
        <h1>Riwayat Laporan Anda</h1>
        <p><a href="dashboard.php" style="color:white;">Kembali</a></p>
    </nav>

    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Isi Laporan</th>
            <th>Foto</th>
            <th>Status</th>
        </tr>
        <?php
        $no = 1;
        $nik = $_SESSION['nik'];
        $sql = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nik='$nik' ORDER BY id_pengaduan DESC");
        while($d = mysqli_fetch_array($sql)){
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $d['tgl_pengaduan']; ?></td>
            <td><?php echo $d['isi_laporan']; ?></td>
            <td><img src="assets/img/<?php echo $d['foto']; ?>" width="100"></td>
            <td>
                <?php 
                if($d['status'] == '0') echo "Menunggu Verifikasi";
                elseif($d['status'] == 'proses') echo "Sedang Diproses";
                else echo "Selesai";
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>