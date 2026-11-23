# INVENTORYPRO - Laravel Fundamental (Pertemuan 5)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-inventory__p5__db-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://www.chartjs.org/)

**INVENTORYPRO Laravel** adalah implementasi ulang dari *Inventory Management System* Pertemuan 4 (PHP Native) ke dalam arsitektur **Laravel Framework (MVC)**. Seluruh aturan bisnis (*business rules*), integritas database, dan fitur operasional tetap dipertahankan dengan standar implementasi modern Laravel.

---

## 🏛️ Penjelasan Arsitektur MVC & Alur Request

Sesuai materi Pertemuan 5, sistem ini mengimplementasikan alur:
```text
HTTP Request ──► Route ──► Controller ──► Model (Eloquent) ──► Database (MySQL)
                                  │                                   │
                                  ▼                                   ▼
                              Response ◄─── Blade View ◄────── Query Result
```

1. **Request**: Klien mengirimkan HTTP Request (GET, POST, PUT, DELETE) melalui browser.
2. **Route (`routes/web.php`)**: Menerima request, memetakan URI ke Controller action yang bersangkutan (menggunakan *Resource Routing* dan *Named Routes*).
3. **Controller (`app/Http/Controllers/`)**:
   - Menerima dan memvalidasi input formulir (`$request->validate()`).
   - Mengatur alur logika bisnis, proteksi transaksi ACID (`DB::transaction`).
   - Memanggil Model Eloquent untuk berinteraksi dengan database.
4. **Model (`app/Models/`) & Database**:
   - Merepresentasikan entitas data (`Product`, `Category`, `Supplier`, `StockMovement`, `User`).
   - Menyediakan relasi antar model (`hasMany`, `belongsTo`, `belongsToMany`).
   - Berinteraksi dengan database MySQL `inventory_p5_db`.
5. **Response & Blade View (`resources/views/`)**:
   - Controller mengirimkan data terformat ke view Blade.
   - Blade me-render template dengan *Layout Inheritance* (`layouts/app.blade.php`), *Components* (`<x-alert>`, `<x-badge>`), loop `@forelse`, dan *escaping* `{{ $var }}`.

---

## 🌟 Fitur Unggulan & Pemenuhan Rubrik Penilaian

| Komponen Rubrik | Bobot | Implementasi di Project Ini |
| :--- | :---: | :--- |
| **Environment & Konfigurasi** | 10% | Composer, `.env` terkonfigurasi ke database khusus `inventory_p5_db`, Artisan CLI, AppServiceProvider. |
| **Pemahaman MVC & Alur Request** | 15% | Pemisahan tanggung jawab penuh: Route terstruktur, Controller ramping, Model berelasi, Blade tanpa raw query. |
| **Routing & Controller** | 15% | `Route::resource('products', ProductController::class)`, Named routes (`products.index`, `stock.in`, dll), Route parameter & model binding. |
| **Blade & Form** | 15% | Layout `layouts/app.blade.php`, Reusable Components `<x-alert>` & `<x-badge>`, proteksi `@csrf`, HTTP spoofing `@method('PUT')`, validasi `@error` & `old()`. |
| **Migration, Model, Factory, Seeder** | 15% | 6 migration tabel, Model lengkap dengan `$fillable`, Factory dummy data, dan `DatabaseSeeder` yang siap dieksekusi dengan `php artisan migrate:fresh --seed`. |
| **Eloquent & Relationship** | 15% | `Category hasMany Product`, `Product belongsTo Category`, `Product belongsToMany Supplier`, `Product hasMany StockMovement`. Eager loading `with()` mencegah N+1 query. |
| **Migrasi Fitur Inventory P4** | 15% | Master Produk CRUD, Search, Filter (Kategori & Status Stok), Sorting Whitelist, Pagination query string, Transaksi Stok IN/OUT ber-ACID, Pencegahan Stok Negatif, Dashboard Analitik, Grafik Chart.js, Cetak Laporan. |

---

## 🚀 Panduan Instalasi & Menjalankan

### 1. Prasyarat Sistem
- **PHP** versi 8.2 atau lebih baru.
- **MySQL / MariaDB** (aktif melalui XAMPP Control Panel).
- **Composer** versi 2.x.

### 2. Konfigurasi Database Baru (`inventory_p5_db`)
Pastikan database baru telah dibuat (atau buat melalui MySQL/phpMyAdmin):
```sql
CREATE DATABASE IF NOT EXISTS inventory_p5_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

File `.env` sudah dikonfigurasi ke database ini:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_p5_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi Database & Seeding Data Awal
Jalankan perintah berikut di direktori project `C:\xampp\htdocs\inventory-laravel`:
```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:
1. Membuat seluruh tabel baru (`users`, `categories`, `products`, `suppliers`, `product_suppliers`, `stock_movements`, dll).
2. Mengisi data akun awal (`admin` dan `staff`).
3. Mengisi 5 kategori elektronik bawaan P4.
4. Mengisi master pemasok (*suppliers*).
5. Mengisi data produk dengan variasi status stok (*Aman*, *Menipis/Kritis*, *Habis/Kosong*).
6. Mengisi riwayat mutasi stok 7 hari terakhir untuk visualisasi grafik.

### 4. Menjalankan Aplikasi Web

Aplikasi dapat dijalankan melalui **salah satu dari dua cara berikut**:

#### Cara A: Langsung melalui Web Server Apache XAMPP
Buka browser dan akses:
```text
http://localhost/inventory-laravel/public/
```

#### Cara B: Menggunakan Artisan Development Server
Jalankan di terminal:
```bash
php artisan serve
```
Kemudian buka browser pada:
```text
http://127.0.0.1:8000/
```

---

## 🔐 Kredensial Akun Pengguna

| Role | Username | Password | Deskripsi Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | Akses penuh CRUD produk, valuasi aset inventaris, ringkasan nilai per kategori, dan cetak laporan resmi. |
| **Staff Gudang** | `staff` | `staff123` | Akses operasional: Catat Barang Masuk, Catat Barang Keluar, dan pantau riwayat mutasi. |

*Tersedia juga fitur peralihan cepat (*Quick Switch*) Admin/Staff pada bilah navigasi atas untuk mempermudah evaluasi modul penilaian.*

---

## 🧪 Pengujian Unit & Fitur (Automated Tests)

Project ini dilengkapi dengan automated test suite menggunakan PHPUnit:
```bash
php artisan test --filter=InventoryTest
```
Pengujian mencakup:
- Akses halaman Dashboard
- Katalog produk, pencarian, dan penyaringan
- Operasi pembuatan produk (Create & Store)
- Transaksi Barang Masuk (Stock In) & pencatatan mutasi
- Transaksi Barang Keluar (Stock Out) & pengurangan stok
- Validasi pencegahan stok bernilai negatif (*overdraft protection*)
- Integritas relasi Eloquent ORM
