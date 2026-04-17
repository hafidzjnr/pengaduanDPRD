<?php
session_start();
include 'config/koneksi.php';

// Menyiapkan CDN SweetAlert2 dan styling dasar agar background tidak putih polos saat pop-up muncul
$sweetalert = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memproses...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>body { background-color: #f8f9fc; font-family: "Poppins", sans-serif; }</style>
</head>
<body>';

$sweetalert_close = '</body></html>';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE username='$username'");
    $data = mysqli_fetch_assoc($sql);

    // Cek apakah username ada dan password cocok
    if(mysqli_num_rows($sql) > 0){
        if(password_verify($password, $data['password'])){
            $_SESSION['login'] = true;
            $_SESSION['nik'] = $data['nik'];
            $_SESSION['nama'] = $data['nama'];
            header("location:dashboard.php");
        } else {
            // Pop-up jika Password Salah
            echo $sweetalert;
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: 'Password yang Anda masukkan salah.',
                    confirmButtonColor: '#0d6efd'
                }).then((result) => {
                    window.location = 'index.php';
                });
            </script>";
            echo $sweetalert_close;
        }
    } else {
        // Pop-up jika Username Tidak Ditemukan
        echo $sweetalert;
        echo "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Username tidak ditemukan!',
                confirmButtonColor: '#0d6efd'
            }).then((result) => {
                window.location = 'index.php';
            });
        </script>";
        echo $sweetalert_close;
    }
}
?>