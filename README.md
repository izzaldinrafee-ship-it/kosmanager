# KosManager

**Sistem Manajemen Kos Berbasis Web**

KosManager adalah aplikasi web untuk membantu pengelola kos mengelola data kamar, penghuni, tagihan, transaksi pembayaran, dan laporan keuangan melalui satu sistem terpusat.

## Fitur Utama

- **Dashboard** — ringkasan informasi dan statistik kos.
- **Manajemen Kamar** — mengelola data kamar dan status hunian.
- **Manajemen Penghuni** — mengelola data penghuni kos.
- **Pembayaran** — membuat dan mengelola tagihan bulanan.
- **Riwayat Transaksi** — mencatat pembayaran dengan metode Tunai, Transfer, QRIS, dan E-Wallet.
- **Status Pembayaran Otomatis** — memperbarui status menjadi Belum Bayar, Sebagian, atau Lunas.
- **Laporan Keuangan** — melihat ringkasan pembayaran dan keuangan.
- **Profil Akun** — memperbarui informasi akun dan kata sandi.

## Teknologi

- PHP
- Laravel
- MySQL
- Bootstrap 5
- Bootstrap Icons
- Vite dan npm

## Persyaratan

- PHP dan Composer
- MySQL
- Node.js dan npm

## Instalasi Lokal

1. Clone repository:

   ```bash
   git clone <URL-REPOSITORY>
   cd kosmanager
   ```

2. Instal dependensi PHP:

   ```bash
   composer install
   ```

3. Buat file konfigurasi lokal:

   ```bat
   copy .env.example .env
   ```

4. Buat application key:

   ```bash
   php artisan key:generate
   ```

5. Atur koneksi database MySQL pada file `.env`, lalu buat database kosong yang sesuai.

6. Jalankan migrasi database:

   ```bash
   php artisan migrate
   ```

7. Instal dependensi frontend dan buat aset:

   ```bash
   npm install
   npm run build
   ```

8. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

9. Buka http://127.0.0.1:8000 di browser.

## Status Proyek

Proyek portofolio untuk menerapkan pengembangan aplikasi web menggunakan Laravel, pengelolaan database relasional, autentikasi pengguna, dan alur transaksi pembayaran.

## Catatan Keamanan

- Jangan mengunggah file `.env` atau kredensial database ke repository publik.
- Gunakan database pengembangan sendiri saat menjalankan migrasi.
- Buat akun aplikasi sendiri setelah instalasi jika belum tersedia akun awal.