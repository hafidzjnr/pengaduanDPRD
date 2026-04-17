<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun | Pengaduan DPRD</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Registrasi Masyarakat</h2>
        <form action="" method="POST">
            <input type="text" name="nik" placeholder="NIK (16 Digit)" required>
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="telp" placeholder="No. Telepon" required>
            <button type="submit" name="daftar">Daftar Sekarang</button>
        </form>
        <a href="index.php">Sudah punya akun? Login</a>
    </div>

    <?php
    include 'config/koneksi.php';
    if(isset($_POST['daftar'])){
        $nik = $_POST['nik'];
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        // Password di-hash agar aman
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $telp = $_POST['telp'];

        $query = mysqli_query($koneksi, "INSERT INTO masyarakat VALUES ('$nik', '$nama', '$username', '$password', '$telp')");

        if($query){
            echo "<script>alert('Data Berhasil Disimpan, Silahkan Login'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal Daftar, NIK mungkin sudah terdaftar');</script>";
        }
    }
    ?>
</body>
</html>