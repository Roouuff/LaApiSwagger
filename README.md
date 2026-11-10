# Perpustakaan API (REST API Laravel 13 + Sanctum + OpenAPI Swagger)

REST API untuk manajemen data perpustakaan yang dibangun menggunakan **Laravel 13** dan **PHP 8.3+**. Proyek ini dilengkapi dengan sistem autentikasi berbasis token menggunakan **Laravel Sanctum**, operasi CRUD lengkap untuk data buku, serta dokumentasi interaktif **OpenAPI 3** melalui paket **L5-Swagger**.

---

## Fitur Utama

- **Autentikasi Berbasis Token.** Mendukung registrasi pengguna baru, login (dengan proteksi _rate limiting_), melihat profil pengguna aktif, dan logout menggunakan Laravel Sanctum.
- **Manajemen Data Buku (CRUD).** Mendukung fitur melihat daftar buku (dilengkapi paginasi dan pencarian berdasarkan judul atau penulis), menambah buku baru, melihat detail, memperbarui data, serta menghapus buku.
- **Dokumentasi Interaktif OpenAPI 3.** Menggunakan atribut PHP 8 (`#[OA\...]`) untuk menghasilkan dokumentasi otomatis yang dapat diuji langsung melalui antarmuka web Swagger UI.
- **Automated Testing.** Dilengkapi dengan _feature test_ menggunakan PHPUnit untuk memastikan seluruh _endpoint_ autentikasi dan manajemen buku berjalan sesuai standar.

---

## Teknologi yang Digunakan

- **Framework:** Laravel 13 (PHP 8.3+)
- **Autentikasi:** Laravel Sanctum
- **Dokumentasi API:** `darkaonline/l5-swagger` (OpenAPI 3)
- **Database:** SQLite (default) atau MySQL

---

## Panduan Instalasi & Menjalankan Proyek

1. **Clone repositori dan masuk ke direktori proyek:**

    ```bash
    git clone <url-repositori-anda>
    cd perpus-api
    ```

2. **Instal dependensi PHP:**

    ```bash
    composer install
    ```

3. **Salin file konfigurasi `.env` dan buat _application key_:**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Sesuaikan konfigurasi `.env`:**
   Pastikan pengaturan URL aplikasi dan konfigurasi Swagger sudah ditambahkan di dalam file `.env`:

    ```env
    APP_URL=http://localhost:8000
    L5_SWAGGER_CONST_HOST=http://localhost:8000
    L5_SWAGGER_GENERATE_ALWAYS=true
    ```

    _(Catatan: Secara default proyek menggunakan SQLite. Jika ingin menggunakan MySQL, sesuaikan bagian `DB\__`di`.env`).\*

5. **Jalankan migrasi database dan _seeder_:**
   Perintah ini akan membuat tabel yang dibutuhkan sekaligus mengisi 1 akun admin demo dan 20 data buku acak:

    ```bash
    php artisan migrate:fresh --seed
    ```

6. **Generate dokumentasi Swagger & jalankan server lokal:**
    ```bash
    php artisan l5-swagger:generate
    php artisan serve
    ```
    Akses dokumentasi interaktif di browser melalui: `http://localhost:8000/api/documentation`.

---

## Akun Demo (Hasil Seeder)

Gunakan kredensial berikut untuk mencoba _endpoint_ login:

- **Email:** `admin@example.com`
- **Password:** `password`

---

## Daftar Endpoint API

| Method      | Endpoint            | Fungsi                                                    | Autentikasi       |
| :---------- | :------------------ | :-------------------------------------------------------- | :---------------- |
| `POST`      | `/api/register`     | Registrasi akun baru dan mendapatkan token                | Tidak             |
| `POST`      | `/api/login`        | Login pengguna dan mendapatkan token                      | Tidak             |
| `GET`       | `/api/me`           | Mengambil profil pengguna yang sedang login               | Ya (Bearer Token) |
| `POST`      | `/api/logout`       | Menghapus token aktif (logout)                            | Ya (Bearer Token) |
| `GET`       | `/api/books`        | Daftar buku (mendukung query `?search=` dan `?per_page=`) | Ya (Bearer Token) |
| `POST`      | `/api/books`        | Menambahkan data buku baru                                | Ya (Bearer Token) |
| `GET`       | `/api/books/{book}` | Melihat detail satu buku berdasarkan ID                   | Ya (Bearer Token) |
| `PUT/PATCH` | `/api/books/{book}` | Memperbarui data buku berdasarkan ID                      | Ya (Bearer Token) |
| `DELETE`    | `/api/books/{book}` | Menghapus data buku berdasarkan ID                        | Ya (Bearer Token) |

---

## Cara Pengujian via Swagger UI

1. Buka `http://localhost:8000/api/documentation`.
2. Buka bagian **Auth → `POST /api/login`**, klik **Try it out**, lalu klik **Execute** menggunakan akun demo.
3. Salin nilai `token` dari respons JSON yang muncul (contoh: `1|Xz9aBcDe...`).
4. Klik tombol **Authorize** (ikon gembok) di bagian kanan atas halaman Swagger.
5. Tempelkan token tersebut ke kolom yang tersedia (**tanpa** mengetik kata `Bearer`), klik **Authorize**, lalu klik **Close**.
6. Seluruh _endpoint_ di bagian **Books** dan **Auth (`/me`, `/logout`)** kini siap diuji.

---

## Menjalankan Automated Test

Untuk menjalankan pengujian otomatis menggunakan PHPUnit:

```bash
php artisan test
```
