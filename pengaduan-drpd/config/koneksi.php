<?php
$host = "127.0.0.1"; 
$user = "root";
$pass = "";
$db   = "pengaduan_tasik";
$port = 3307; 

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>