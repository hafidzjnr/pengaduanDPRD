<?php
session_start();
include 'config/koneksi.php';
// Harusnya ada proteksi login admin di sini
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel Admin DPRD</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        table { border-collapse: collapse; width: 90%; margin: 20px auto; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #d32f2f; color: white; }
        .btn-proses { background: #ff9800; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .btn-selesai { background: #4caf50; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <nav style="background: #d32f2f;">
        <h1>Panel Kerja DPRD Kota Tasikmalaya</h1>
        <p>Manajemen Pengaduan Kinerja Desa</p>
    </nav>

    <h2 style="text-align:center;">Daftar Masuk Pengaduan</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>NIK Pelapor</th>
            <th>Isi Laporan</th>
            <th>Foto</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $sql = mysqli_query($koneksi, "SELECT * FROM pengaduan ORDER BY status ASC, tgl_pengaduan DESC");
        while($d = mysqli_fetch_array($sql)){
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $d['tgl_pengaduan']; ?></td>
            <td><?php echo $d['nik']; ?></td>
            <td><?php echo $d['isi_laporan']; ?></td>
            <td><a href="assets/img/<?php echo $d['foto']; ?>" target="_blank">Lihat Foto</a></td>
            <td>
                <?php 
                if($d['status'] == '0') echo "<span style='color:red;'>Menunggu</span>";
                elseif($d['status'] == 'proses') echo "<span style='color:orange;'>Proses</span>";
                else echo "<span style='color:green;'>Selesai</span>";
                ?>
            </td>
            <td>
                <?php if($d['status'] == '0'): ?>
                    <a class="btn-proses" href="update_status.php?id=<?php echo $d['id_pengaduan']; ?>&status=proses">Proses</a>
                <?php elseif($d['status'] == 'proses'): ?>
                    <a class="btn-selesai" href="update_status.php?id=<?php echo $d['id_pengaduan']; ?>&status=selesai">Selesai</a>
                <?php else: ?>
                    ✅
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>