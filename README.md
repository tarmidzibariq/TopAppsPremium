<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# TopAppsPremium (Laravel)

Project Laravel ini adalah aplikasi internal (admin) untuk mengelola **kategori**, **service/layanan**, **stok masuk/keluar**, serta menampilkan **dashboard & laporan**.

## Prasyarat
- **PHP 8.3+**
- **Composer**
- **Node.js** & **npm** (untuk Vite / frontend assets)
- Database yang didukung Laravel (umumnya MySQL/PostgreSQL). Contoh di bawah menggunakan **MySQL**.

## Cara Install & Setup
1. Clone project, lalu masuk ke folder project.

2. Install dependensi PHP:
```bash
composer install
```

3. Siapkan file `.env`:
```bash
cp .env.example .env
```
Lalu edit konfigurasi database di `.env`, contoh untuk MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=topappspremium
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate APP_KEY:
```bash
php artisan key:generate
```

5. Jalankan migrate database (opsional seed jika tersedia):
```bash
php artisan migrate --force
```
> Jika ingin mengisi data awal (seeders), coba:
```bash
php artisan migrate --force --seed
```

6. Buat symlink untuk akses storage upload (mis. image service):
```bash
php artisan storage:link
```

7. Install dependensi frontend:
```bash
npm install
```

## Cara Menjalankan Proyek
- Jalankan server Laravel:
```bash
php artisan serve
```

- Jalankan Vite (untuk assets/front-end):
```bash
npm run dev
```

Setelah itu buka:
- **Login**: `/` (route login diarahkan ke AuthenticatedSessionController)
- **Dashboard**: `/dashboard`

> Pastikan server sudah berjalan dari port yang ditampilkan oleh `php artisan serve`.

## Fitur yang tersedia
Berdasarkan route & controller yang ada, aplikasi menyediakan modul berikut:

### 1) Autentikasi & Profile
- Login/logout (halaman login ada di `/`).
- **Profile**
  - Edit data profil: `/profile` (GET/PUT)
  - Update password: `/profile/password` (GET/PUT)

### 2) Dashboard (Analitik)
- Halaman dashboard: `/dashboard`
- Menampilkan:
  - Total stok per kategori (`Category` + `Service` + jumlah stok)
  - Total stok masuk/keluar (berdasarkan `type` pada `StockService`)
  - Rekap revenue bulanan (6 bulan terakhir) untuk chart
  - Deret data:
    - `revenueSeries` (nilai revenue dari `quantity * price_service`)
    - `pesanSeries` (jumlah keluar)
    - `masukSeries` (jumlah masuk)
  - Daftar pergerakan stok terbaru (limit 8)
  - **Low stock services** (stok `<= 5`, limit 6)
  - **Top services** (stok tertinggi, limit 5)
  - Statistik total: jumlah kategori, service, user, dan total transaksi

### 3) Category (CRUD)
- Routes berbasis resource: `/category`
- Fitur:
  - List kategori (pagination + pencarian `search`)
  - Buat kategori
  - Edit kategori
  - Hapus kategori
  - Detail kategori: menampilkan services yang terkait

### 4) Service/Layanan (CRUD + Upload Image)
- Routes berbasis resource: `/service`
- Fitur:
  - List service (pagination + filter `category_id` + pencarian `name_service`)
  - Buat/Edit service
    - Upload gambar `image_service` (jpg/jpeg/png/webp, max 2MB)
  - Detail service (`show`):
    - Total **masuk** (`type=in`) dan **keluar** (`type=out`)
    - Histori transaksi stok terkait service (paginate 10, termasuk relasi user)
  - Hapus service (jika image ada, akan dihapus dari storage)

### 5) Stock (Pencatatan Stok Masuk/Keluar)
- List & filter: `/stock`
- Tambah transaksi stok: `/stock` (POST)
- Fitur:
  - Filter berdasarkan:
    - `type` (`in` atau `out`)
    - `category_id`
    - `service_id`
    - rentang tanggal (`date_from`, `date_to`)
  - Menampilkan total:
    - total masuk (`type=in`)
    - total keluar (`type=out`)
  - Saat transaksi `out`, aplikasi **memvalidasi stok tidak boleh melebihi stok tersedia**.
  - Update stok dilakukan dalam transaksi database (`DB::transaction` + `lockForUpdate`).

### 6) Users (CRUD Admin)
- Routes berbasis resource: `/users`
- Fitur:
  - List users (pagination + pencarian berdasarkan `name` atau `email`)
  - Buat user (validasi password minimal 8 + confirmed)
  - Edit user (password opsional)
  - Hapus user (blokir penghapusan akun sendiri)

### 7) Report (Laporan & Grafik)
- Route: `/report`
- Fitur:
  - Filter `month` dan `year` (default mengikuti tanggal sekarang)
  - Statistik kartu:
    - total masuk per bulan
    - total keluar per bulan
    - total transaksi per bulan
  - Grafik 12 bulan untuk tahun tertentu:
    - total masuk per bulan
    - total keluar per bulan
  - Top 5 layanan dengan stok masuk (berdasarkan quantity) untuk bulan terpilih
  - Top 5 layanan dengan stok keluar (berdasarkan quantity) untuk bulan terpilih
  - Daftar **stok kritis** (service `stock_service <= 5`)

## Catatan Teknis
- Image `image_service` tersimpan di disk `public` dan perlu `php artisan storage:link` agar bisa diakses via URL publik.
- Transaksi stok menggunakan tabel `stock_services` dengan kolom:
  - `type` (in/out), `quantity`, `stock_before`, `stock_after`, serta relasi ke `service` dan `user`.

