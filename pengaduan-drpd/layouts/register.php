<?php
session_start();
include 'config/koneksi.php';

$error = '';
$success = '';

if (isset($_POST['daftar'])) {
    $nik      = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $telp     = mysqli_real_escape_string($koneksi, $_POST['telp']);

    if (strlen($nik) != 16 || !is_numeric($nik)) {
        $error = 'NIK harus 16 digit angka.';
    } else {
        $cek = mysqli_query($koneksi, "SELECT nik FROM masyarakat WHERE nik='$nik' OR username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'NIK atau username sudah terdaftar. Silakan gunakan data lain.';
        } else {
            $query = mysqli_query($koneksi, "INSERT INTO masyarakat VALUES ('$nik','$nama','$username','$password','$telp')");
            if ($query) $success = 'Akun berhasil dibuat! Silakan login.';
            else        $error   = 'Gagal menyimpan data. Coba beberapa saat lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Pengaduan DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="register-body">

    <div class="register-card">
        <div class="card-header">
            <a href="index.php" class="back-btn">&#8592;</a>
            <div>
                <h2>Registrasi Masyarakat</h2>
                <p>Buat akun untuk mulai menyampaikan pengaduan</p>
            </div>
        </div>

        <?php if ($error):   ?><div class="alert alert-danger">&#9888;&#65039; <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success">&#9989; <?php echo $success; ?> <a href="index.php" style="color:inherit;font-weight:600;">Login sekarang &rarr;</a></div><?php endif; ?>

        <form action="" method="POST" <?php echo $success ? 'style="display:none;"' : ''; ?>>
            <p class="section-label">Data Identitas</p>
            <div class="form-group">
                <label>NIK</label>
                <input type="text" name="nik" placeholder="16 digit NIK sesuai KTP" maxlength="16"
                    value="<?php echo isset($_POST['nik']) ? htmlspecialchars($_POST['nik']) : ''; ?>" required>
                <p class="hint">Nomor Induk Kependudukan 16 digit</p>
            </div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Sesuai KTP"
                    value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" required>
            </div>

            <div class="divider"></div>
            <p class="section-label">Data Akun</p>

            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Username login"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="tel" name="telp" placeholder="08xx-xxxx-xxxx"
                        value="<?php echo isset($_POST['telp']) ? htmlspecialchars($_POST['telp']) : ''; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password yang kuat" required>
                <p class="hint">Minimal 8 karakter</p>
            </div>

            <button class="btn-primary" type="submit" name="daftar">Buat Akun Sekarang</button>
        </form>

        <div class="form-footer">
            Sudah punya akun? <a href="index.php">Masuk di sini</a>
        </div>
    </div>

</body>

</html>