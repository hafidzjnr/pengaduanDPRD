<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pengaduan DPRD Tasikmalaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light d-flex align-items-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card card-login p-4 shadow-lg border-0 rounded-4">
                    <div class="card-body text-center">
                        <i class="fas fa-building text-primary fa-3x mb-3"></i>
                        <h4 class="fw-bold mb-1">DPRD Kota Tasikmalaya</h4>
                        <p class="text-muted mb-4">Layanan Pengaduan Masyarakat</p>
                        
                        <form action="proses_login.php" method="POST">
                            <div class="form-floating mb-3 text-start">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                <label for="username"><i class="fas fa-user text-muted me-2"></i>Username</label>
                            </div>
                            <div class="form-floating mb-4 text-start">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password"><i class="fas fa-lock text-muted me-2"></i>Password</label>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100 py-2 mb-3 fw-bold shadow-sm rounded-3">Masuk Sistem</button>
                        </form>
                        <hr>
                        <p class="mb-0">Belum punya akun? <a href="register.php" class="text-decoration-none fw-bold">Daftar di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>