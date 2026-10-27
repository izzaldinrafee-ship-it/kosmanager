<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo KosManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; color: #172033; }
        .hero { padding: 80px 0 55px; }
        .brand { font-weight: 800; letter-spacing: -.5px; }
        .feature { height: 100%; border: 1px solid #e8ebf2; border-radius: 16px; }
        .btn-primary { background: #3157d5; border-color: #3157d5; }
    </style>
</head>
<body>
<nav class="navbar bg-white border-bottom">
    <div class="container py-2">
        <a class="navbar-brand brand" href="/demo">KosManager</a>
        <a class="btn btn-primary" href="/login">Masuk ke Demo</a>
    </div>
</nav>

<main class="container">
    <section class="hero">
        <span class="badge text-bg-primary mb-3">PROJECT PORTOFOLIO</span>
        <h1 class="display-4 fw-bold">Kelola kos dengan lebih mudah.</h1>
        <p class="lead text-secondary mt-3" style="max-width: 680px">
            KosManager adalah aplikasi web untuk membantu pengelolaan kamar,
            penghuni, tagihan, transaksi pembayaran, dan laporan keuangan kos.
        </p>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn btn-primary btn-lg" href="/login">Coba Aplikasi</a>
            <a class="btn btn-outline-dark btn-lg"
               href="https://github.com/izzaldinrafee-ship-it/kosmanager"
               target="_blank" rel="noopener noreferrer">Lihat Source Code</a>
        </div>
        <div class="alert alert-light border mt-4" style="max-width: 520px">
            <h2 class="h5 fw-bold">Akun Demo</h2>
            <p class="mb-2">Gunakan akun berikut untuk mencoba aplikasi:</p>
            <p class="mb-1"><strong>Email:</strong> demo@izzaldev.com</p>
            <p class="mb-2"><strong>Password:</strong> KosDemo2026!</p>
            <p class="small text-secondary mb-0">
                Gunakan hanya untuk demo. Data bersifat fiktif dan dapat diubah oleh pengunjung lain.
            </p>
        </div>
    </section>

    <section class="pb-5">
        <h2 class="h3 fw-bold mb-4">Fitur utama</h2>
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <div class="feature bg-white p-4">
                    <h3 class="h5 fw-bold">Dashboard</h3>
                    <p class="text-secondary mb-0">Ringkasan kamar, penghuni aktif, tagihan, dan pembayaran.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature bg-white p-4">
                    <h3 class="h5 fw-bold">Manajemen Kamar</h3>
                    <p class="text-secondary mb-0">Kelola informasi kamar, tipe, harga, dan status hunian.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature bg-white p-4">
                    <h3 class="h5 fw-bold">Data Penghuni</h3>
                    <p class="text-secondary mb-0">Catat informasi penghuni dan riwayat hunian.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature bg-white p-4">
                    <h3 class="h5 fw-bold">Tagihan</h3>
                    <p class="text-secondary mb-0">Kelola tagihan bulanan dan status pembayarannya.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature bg-white p-4">
                    <h3 class="h5 fw-bold">Transaksi Pembayaran</h3>
                    <p class="text-secondary mb-0">Catat pembayaran dan metode transaksi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature bg-white p-4">
                    <h3 class="h5 fw-bold">Laporan</h3>
                    <p class="text-secondary mb-0">Lihat rekap tagihan, tunggakan, dan penerimaan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white border rounded-4 p-4 p-md-5 mb-5">
        <h2 class="h4 fw-bold">Teknologi yang digunakan</h2>
        <p class="text-secondary mb-0">Laravel, PHP, MySQL, Blade, Bootstrap, dan GitHub.</p>
    </section>
</main>

<footer class="bg-white border-top py-4">
    <div class="container d-flex flex-wrap justify-content-between gap-2">
        <span class="text-secondary small">KosManager · Proyek portofolio</span>
        <a class="small text-decoration-none"
           href="https://github.com/izzaldinrafee-ship-it/kosmanager">GitHub Repository</a>
    </div>
</footer>
</body>
</html>
