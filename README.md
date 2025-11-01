# SIMAKIN - Sistem Informasi Monitoring Kebersihan Internal

[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem Informasi Monitoring Kebersihan Internal (SIMAKIN) adalah aplikasi web berbasis Laravel yang dirancang untuk memantau, mengelola, dan meningkatkan standar kebersihan di lingkungan internal Pengadilan Agama Kabupaten Malang.

## Fitur Utama

### Dashboard Admin
- **Monitoring Real-time**: Pantau progress pembersihan ruangan secara real-time
- **Manajemen Pengguna**: Kelola akun Office Boy (OB)
- **Manajemen Ruangan**: Tambah, edit, dan hapus data ruangan
- **Manajemen Tugas**: Atur tugas-tugas pembersihan yang harus dilakukan
- **Laporan & Ekspor**: Export laporan pembersihan dalam format Excel
- **Statistik Dashboard**: Visualisasi data pembersihan harian

### Dashboard Office Boy (OB)
- **Tugas Harian**: Lihat tugas pembersihan yang harus diselesaikan
- **Update Real-time**: Tandai tugas selesai secara real-time
- **Status Ruangan**: Update status kebersihan ruangan
- **Riwayat Pembersihan**: Lihat riwayat tugas yang telah dilakukan

## Teknologi yang Digunakan

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Bootstrap 5, Sneat Template
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Export**: Laravel Excel (maatwebsite/excel)
- **Real-time Updates**: AJAX dengan jQuery

## Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 5.7 atau lebih tinggi

## Instalasi dan Setup

### 1. Clone Repository
```bash
git clone <repository-url>
cd simakin
```

### 2. Install Dependencies PHP
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:
```env
APP_NAME="SIMAKIN"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simakin
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 4. Buat Database MySQL
```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE simakin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Setup Database
```bash
# Jalankan migrasi
php artisan migrate

# (Opsional) Jalankan seeder untuk data dummy
php artisan db:seed
```

### 7. Install Dependencies Frontend
```bash
npm install
```

### 8. Build Assets Frontend
```bash
npm run build
# atau untuk development
npm run dev
```

### 9. Jalankan Aplikasi
```bash
# Menggunakan PHP built-in server
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Struktur Pengguna

### Administrator
- **Email**: admin@example.com
- **Password**: password
- **Akses**: Semua fitur manajemen

### Office Boy (OB)
- **Email**: ob@example.com
- **Password**: password
- **Akses**: Dashboard OB, update tugas pembersihan

## Struktur Database

### Tabel Utama
- **users**: Data pengguna (admin, ob)
- **ruangan**: Data ruangan yang perlu dibersihkan
- **tugas**: Daftar tugas pembersihan
- **cleaning_records**: Rekaman pembersihan harian
- **cleaning_record_tasks**: Detail tugas dalam satu sesi pembersihan

## Cara Penggunaan

### Untuk Administrator:
1. Login dengan akun admin
2. Kelola data ruangan melalui menu "Ruangan"
3. Atur tugas pembersihan melalui menu "Tugas"
4. Kelola akun OB melalui menu "Manajemen OB"
5. Monitor progress pembersihan di dashboard
6. Export laporan pembersihan jika diperlukan

### Untuk Office Boy:
1. Login dengan akun OB
2. Lihat tugas harian di dashboard
3. Klik checkbox untuk menandai tugas selesai
4. Update status ruangan setelah semua tugas selesai

## Keamanan

- Autentikasi berbasis Laravel Sanctum
- Middleware untuk role-based access control
- Validasi input di semua form
- CSRF protection
- Password hashing dengan bcrypt

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

---

**Dikembangkan untuk**: Pengadilan Agama Kabupaten Malang  
**Versi**: 1.0.0  
**Tanggal Rilis**: November 2025