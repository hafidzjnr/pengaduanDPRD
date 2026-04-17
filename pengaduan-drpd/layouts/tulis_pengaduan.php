<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}
include 'config/koneksi.php';

$error   = '';
$success = false;

if (isset($_POST['kirim'])) {
    $tgl = $_POST['tgl'];
    $nik = $_SESSION['nik'];
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_laporan']);

    if (strlen(trim($_POST['isi_laporan'])) < 20) {
        $error = 'Isi laporan terlalu singkat. Harap jelaskan masalah secara detail (minimal 20 karakter).';
    } elseif (empty($_FILES['foto']['name'])) {
        $error = 'Bukti foto wajib dilampirkan.';
    } else {
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.';
        } elseif ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
            $error = 'Ukuran foto melebihi 5MB.';
        } else {
            $lokasi    = "assets/img/";
            if (!is_dir($lokasi)) mkdir($lokasi, 0755, true);
            $nama_foto = time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $lokasi . $nama_foto)) {
                // FIX: Gunakan NULL agar id_pengaduan AUTO_INCREMENT terisi otomatis
                $query = mysqli_query($koneksi, "INSERT INTO pengaduan (id_pengaduan, tgl_pengaduan, nik, isi_laporan, foto, status) VALUES (NULL, '$tgl', '$nik', '$isi', '$nama_foto', '0')");
                if ($query) $success = true;
                else {
                    $error = 'Gagal menyimpan laporan. Coba lagi.';
                    unlink($lokasi . $nama_foto);
                }
            } else {
                $error = 'Gagal mengunggah foto. Pastikan folder assets/img/ dapat ditulis.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Pengaduan | DPRD Tasikmalaya</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <nav class="main-nav">
        <div class="nav-brand">
            <div class="nav-logo">⚖️</div>
            <div class="nav-brand-text">
                <h1>Form Pengaduan Masyarakat</h1>
                <p>DPRD Kota Tasikmalaya</p>
            </div>
        </div>
        <a href="dashboard.php" class="back-link">&#8592; Kembali</a>
    </nav>

    <main class="pengaduan-main">
        <?php if (!$success): ?>

            <div class="page-header">
                <p class="eyebrow">Layanan Pengaduan</p>
                <h2>Tulis Laporan Pengaduan</h2>
                <p>Sampaikan keluhan Anda secara jelas agar dapat segera ditindaklanjuti oleh DPRD Kota Tasikmalaya.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">&#9888;&#65039; <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="tip-box">
                <div class="tip-icon">&#128161;</div>
                <div class="tip-text">
                    <strong>Tips pengaduan yang efektif:</strong> Tuliskan lokasi kejadian, kronologi masalah, dan dampaknya secara jelas. Sertakan foto sebagai bukti pendukung.
                </div>
            </div>

            <div class="form-card">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Tanggal Pengaduan</label>
                        <input type="date" name="tgl" value="<?php echo date('Y-m-d'); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Isi Laporan Kinerja Desa</label>
                        <textarea name="isi_laporan" rows="6" required id="laporanInput" oninput="updateCounter()"
                            placeholder="Ceritakan masalah yang Anda alami secara detail. Sertakan lokasi, waktu kejadian, dan dampak yang dirasakan..."><?php echo isset($_POST['isi_laporan']) ? htmlspecialchars($_POST['isi_laporan']) : ''; ?></textarea>
                        <div class="char-counter" id="charCounter"><span id="charCount">0</span> karakter (minimal 20)</div>
                        <p class="hint">Deskripsikan masalah selengkap mungkin agar mudah ditindaklanjuti.</p>
                    </div>

                    <div class="divider"></div>

                    <div class="form-group">
                        <label>Bukti Foto</label>
                        <div class="upload-area" id="uploadArea">
                            <input type="file" name="foto" accept="image/*" onchange="handleFile(this)">
                            <div class="upload-icon" id="uploadIcon">&#128247;</div>
                            <div class="upload-text" id="uploadText">Klik untuk unggah foto bukti</div>
                            <div class="upload-subtext" id="uploadSub">JPG, PNG, WEBP &mdash; Maks. 5MB</div>
                        </div>
                    </div>

                    <button class="btn-primary" type="submit" name="kirim">&#128232; Kirim Laporan Pengaduan</button>
                </form>
                <a href="dashboard.php" class="btn-cancel">Batal</a>
            </div>

        <?php else: ?>

            <div class="form-card">
                <div class="success-state">
                    <div class="success-icon">&#9989;</div>
                    <h3>Laporan Berhasil Terkirim!</h3>
                    <p>Terima kasih, <strong><?php echo htmlspecialchars(explode(' ', $_SESSION['nama'])[0]); ?></strong>.<br>
                        Laporan Anda telah diterima dan akan segera ditindaklanjuti oleh DPRD Kota Tasikmalaya.</p>
                    <div>
                        <a href="lihat_pengaduan.php" class="btn-secondary">&#128203; Lihat Riwayat</a>
                        <a href="tulis_pengaduan.php" class="btn-secondary">&#43; Tulis Lagi</a>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </main>

    <script>
        function updateCounter() {
            const len = document.getElementById('laporanInput').value.length;
            document.getElementById('charCount').textContent = len;
            document.getElementById('charCounter').className = 'char-counter' + (len >= 20 ? ' ok' : '');
        }

        function handleFile(input) {
            if (input.files && input.files[0]) {
                const size = (input.files[0].size / 1024 / 1024).toFixed(1);
                document.getElementById('uploadIcon').textContent = '✅';
                document.getElementById('uploadText').textContent = input.files[0].name;
                document.getElementById('uploadSub').textContent = size + ' MB — siap diunggah';
                document.getElementById('uploadArea').classList.add('done');
            }
        }
        updateCounter();
    </script>

</body>

</html>