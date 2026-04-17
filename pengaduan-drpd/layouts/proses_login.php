<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $sql = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE username='$username'");
    $data = mysqli_fetch_assoc($sql);

    if(mysqli_num_rows($sql) > 0){
        if(password_verify($password, $data['password'])){
            $_SESSION['login'] = true;
            $_SESSION['nik']   = $data['nik'];
            $_SESSION['nama']  = $data['nama'];
            header("location:dashboard.php");
            exit;
        } else {
            header("location:index.php?error=password");
            exit;
        }
    } else {
        header("location:index.php?error=user");
        exit;
    }
} else {
    header("location:index.php");
    exit;
}
?>