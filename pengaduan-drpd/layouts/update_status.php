<?php
session_start();
include 'config/koneksi.php';

$allowed_status = ['proses', 'selesai'];

if(isset($_GET['id']) && isset($_GET['status']) && in_array($_GET['status'], $allowed_status)){
    $id     = (int) $_GET['id'];
    $status = $_GET['status'];

    $query = mysqli_query($koneksi, "UPDATE pengaduan SET status='$status' WHERE id_pengaduan='$id'");

    if($query){
        header("location:admin_dashboard.php?updated=1");
    } else {
        header("location:admin_dashboard.php?error=1");
    }
} else {
    header("location:admin_dashboard.php");
}
exit;
?>