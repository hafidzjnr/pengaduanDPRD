<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Pengaduan DPRD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light d-flex align-items-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-5">
                <div class="card card-login p-4 shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus text-primary fa-3x mb-3"></i>
                            <h4 class="fw-bold mb-1">Registrasi Akun</h4>
                            <p class="text-muted">Lengkapi data diri Anda di bawah ini</p>
                        </div>
                        <form action="" method="POST">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-white"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text" name="nik" class="form-control" placeholder="NIK (16 Digit)" required maxlength="16">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-white"><i class="fas fa-at text-muted"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-text bg-white"><i class="fas fa-phone text-muted"></i></span>
                                <input type="text" name="telp" class="form-control" placeholder="No. Telepon" required>
                            </div>
                            <button type="submit" name="daftar" class="btn btn-primary w-100 py-2 mb-3 fw-bold rounded-3 shadow-sm">Daftar Sekarang</button>
                        </form>
                        <div class="text-center">
                            <a href="index.php" class="text-decoration-none fw-bold"><i class="fas fa-arrow-left me-1"></i> Kembali ke Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'config/koneksi.php';
    if(isset($_POST['daftar'])){
        $nik = $_POST['nik'];
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $telp = $_POST['telp'];

        $query = mysqli_query($koneksi, "INSERT INTO masyarakat VALUES ('$nik', '$nama', '$username', '$password', '$telp')");

        if($query){
            // SweetAlert Berhasil Daftar
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    text: 'Data Anda telah disimpan. Silakan login.',
                    confirmButtonColor: '#0d6efd'
                }).then((result) => {
                    window.location='index.php';
                });
            </script>";
        } else {
            // SweetAlert Gagal Daftar
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    text: 'NIK atau Username mungkin sudah terdaftar.',
                    confirmButtonColor: '#0d6efd'
                });
            </script>";
        }
    }
    ?>
</body>
</html>