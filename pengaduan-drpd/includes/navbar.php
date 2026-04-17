<nav>
  <h1>Layanan Pengaduan DPRD Tasikmalaya</h1>
  <?php if(isset($_SESSION['login'])) : ?>
    <p>
      Selamat Datang <b><?php echo $_SESSION['nama']; ?></b> |
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Keluar</a>
    </p>
    <?php endif; ?>
</nav>