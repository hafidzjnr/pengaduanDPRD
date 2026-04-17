<?php
include 'config/koneksi.php';

$id = $_GET['id'];
$status = $_GET['status'];

$query = mysqli_query($koneksi, "UPDATE pengaduan SET status='$status' WHERE id_pengaduan='$id'");

if($query){
    header("location:admin_dashboard.php");
} else {
    echo "Gagal update status";
}
?>