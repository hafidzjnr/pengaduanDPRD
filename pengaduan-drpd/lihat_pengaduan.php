<?php
session_start();
include 'config/koneksi.php';
if(!isset($_SESSION['login'])){
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-5">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-bullhorn me-2"></i>Pengaduan DPRD</a>
            <div class="ms-auto">
                <a class="btn btn-light btn-sm px-3" href="dashboard.php"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="table-wrapper">
            <h4 class="mb-4 fw-bold"><i class="fas fa-list text-primary me-2"></i>Riwayat Laporan Anda</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="40%">Isi Laporan</th>
                            <th width="20%">Foto Bukti</th>
                            <th width="20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $nik = $_SESSION['nik'];
                        $sql = mysqli_query($koneksi, "SELECT * FROM pengaduan WHERE nik='$nik' ORDER BY id_pengaduan DESC");
                        if(mysqli_num_rows($sql) > 0){
                            while($d = mysqli_fetch_array($sql)){
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d M Y', strtotime($d['tgl_pengaduan'])); ?></td>
                            <td><?php echo nl2br($d['isi_laporan']); ?></td>
                            <td>
                                <img src="assets/img/<?php echo $d['foto']; ?>" class="img-thumbnail rounded" width="120" alt="Bukti Foto">
                            </td>
                            <td>
                                <?php 
                                if($d['status'] == '0'){
                                    echo '<span class="badge bg-danger px-3 py-2"><i class="fas fa-clock me-1"></i> Menunggu</span>';
                                } elseif($d['status'] == 'proses'){
                                    echo '<span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-spinner fa-spin me-1"></i> Diproses</span>';
                                } else {
                                    echo '<span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Selesai</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada data pengaduan.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>