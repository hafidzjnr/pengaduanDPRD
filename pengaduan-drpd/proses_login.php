<?php
session_start();
include 'config/koneksi.php';

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
            echo "<script>alert('Password Salah!'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Username Tidak Ditemukan!'); window.location='index.php';</script>";
    }
}
?>