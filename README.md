# Secure User Dashboard & Management System
Tugas Kelompok – Pemrograman Web (Pertemuan 14)
Mata Kuliah: Pemrograman Web | Dosen: Tori Sutisna, M.Kom.

## Anggota Kelompok
| No | Nama | NIM |
|----|------|-----|
| 1  | Siti Rakhma Nursyifa | 124005066 |
| 2  | Zulfa Sahliya Padilah | 124005008 |
| 3  | Nabilah Khansa Ginanjar | 124005001 |
| 4  | Zahroh Nur Salsabila | 124005015 |

## Fitur Aplikasi
  - Registrasi akun baru dengan validasi kekuatan password (huruf kapital, angka, karakter spesial)
  - Login menggunakan username atau email
  - Dashboard profil pengguna (protected page — otomatis redirect jika belum login)
  - Edit profil & ganti password dengan verifikasi password lama
  - Hapus akun permanen dengan konfirmasi password
  - Semua interaksi via JavaScript Fetch API tanpa page reload
  
## Prasyarat
  Pastikan perangkat kamu sudah terinstal:
  - **XAMPP** (versi 8.0 ke atas) → [Download di sini](https://www.apachefriends.org/)
  - **Web Browser** modern (Google Chrome / Firefox)
  - **Code Editor** (Visual Studio Code direkomendasikan)
  
## Panduan Instalasi & Konfigurasi
  ### Langkah 1 — Download & Siapkan File Proyek
      1. Download atau clone repositori ini
      2. Ekstrak folder `praktikum_p14` (jika dalam bentuk .zip)
      3. Salin folder `praktikum_p14` ke dalam direktori htdocs XAMPP:
         - **Windows** → `C:\xampp\htdocs\praktikum_p14`
  
  ### Langkah 2 — Jalankan XAMPP
      1. Buka **XAMPP Control Panel**
      2. Klik tombol **Start** pada modul **Apache**
      3. Klik tombol **Start** pada modul **MySQL**
      4. Pastikan keduanya berstatus **Running** (indikator hijau)
  
  ### Langkah 3 — Import Database
      1. Buka browser, akses `http://localhost/phpmyadmin`
      2. Klik menu **Import** di bagian atas
      3. Klik **Choose File** → pilih file `db_web_p14.sql` dari folder proyek
      4. Klik tombol **Import** di bagian bawah halaman
      5. Jika berhasil, database `db_web_p14` dan tabel `users` akan terbuat otomatis
    > Alternatif: klik tab **SQL** di phpMyAdmin, lalu salin dan jalankan isi file `db_web_p14.sql`
  
  ### Langkah 4 — Konfigurasi Koneksi Database
      Buka file `db.php`, sesuaikan pengaturan berikut jika diperlukan:

        ```php
        $host     = "localhost";   // Host database (biarkan localhost)
        $dbname   = "db_web_p14"; // Nama database
        $username = "root";        // Username MySQL (default XAMPP: root)
        $password = "";            // Password MySQL (default XAMPP: kosong)
        ```
    > Jika kamu menggunakan XAMPP versi standar di Windows, tidak perlu mengubah apapun.

  ### Langkah 5 — Akses Aplikasi
      Buka browser dan akses: http://localhost/praktikum_p14/ 
        -> maka akan diarahkan ke halaman login & registrasi

## Struktur Folder
  ```
  praktikum_p14/
  ├── bootstrap/
  │   ├── css/
  │   │   ├── bootstrap.min.css
  │   │   └── bootstrap-icons.min.css
  │   └── js/
  │       └── bootstrap.bundle.min.js
  ├── api/
  │   ├── register.php        ← Endpoint Registrasi (POST)
  │   ├── login.php           ← Endpoint Login (POST)
  │   ├── logout.php          ← Endpoint Logout (GET)
  │   ├── get_profile.php     ← Endpoint Baca Profil (GET)
  │   ├── update_profile.php  ← Endpoint Update Profil (POST)
  │   └── delete_account.php  ← Endpoint Hapus Akun (POST)
  ├── index.html              ← Halaman Login & Registrasi
  ├── dashboard.php           ← Halaman Dashboard (Protected)
  ├── db.php                  ← Konfigurasi koneksi PDO
  ├── auth.php                ← Session guard & helper JSON response
  ├── db_web_p14.sql          ← Dump skema database
  └── README.md               ← Dokumentasi proyek
  ```

## Cara Penggunaan
  1. **Registrasi** — klik "Daftar di sini", isi form dengan password yang kuat
  2. **Login** — masukkan username atau email beserta password
  3. **Dashboard** — lihat data profil di menu Profil Saya
  4. **Edit Profil** — ubah nama atau email di menu Edit Profil
  5. **Ganti Password** — isi field password di halaman Edit Profil
  6. **Hapus Akun** — menu Hapus Akun, masukkan password konfirmasi

## Keamanan yang Diterapkan
  - **PDO Prepared Statements** — mencegah SQL Injection
  - **Bcrypt Password Hashing** — password tidak disimpan plain text
  - **Session Regeneration** — mencegah session fixation attack
  - **Protected Routes** — dashboard tidak bisa diakses tanpa login
  - **Server-side Validation** — validasi dilakukan di sisi server, bukan hanya client
  - **HTTP Status Code** — setiap response API menggunakan status code yang sesuai

## Teknologi yang Digunakan
  - **Backend** — PHP 8+ dengan PDO (MySQL)
  - **Database** — MySQL 5.7+
  - **Frontend** — HTML5, CSS3, JavaScript (ES2017+ async/await)
  - **Komunikasi API** — JavaScript Fetch API (tanpa page reload)
  - **UI Framework** — Bootstrap 5.3 + Bootstrap Icons
