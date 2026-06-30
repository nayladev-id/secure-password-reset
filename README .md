# 🛡️ Sistem Pemulihan Sandi Berbasis OTP

Repositori ini berisi proyek aplikasi web *Client-Server* sederhana yang mendemonstrasikan implementasi fitur **Lupa Sandi (Forgot Password)** yang aman menggunakan *One-Time Password* (OTP) yang dikirim via email. 

Proyek ini dibangun untuk memenuhi tugas mata kuliah **Keamanan Informasi**, dengan fokus pada pencegahan celah keamanan web yang umum terjadi pada sistem autentikasi.

## ✨ Fitur Keamanan (Mitigasi Celah)
Sistem ini dirancang dengan mengacu pada standar keamanan, mencakup perlindungan terhadap:
1. **User Enumeration Attack:** Menggunakan *Generic Response* (pesan seragam) untuk mencegah peretas mengetahui apakah suatu email terdaftar di dalam *database* atau tidak.
2. **Brute-Force Attack:** Mengimplementasikan *Time-based Restriction*, di mana kode OTP hanya berlaku maksimal selama 5 menit.
3. **Replay Attack:** Menggunakan protokol *Burn-after-reading*. Setelah OTP berhasil diverifikasi, data OTP langsung dihapus (`NULL`) dari *database*.
4. **Data Breach (Kebocoran Data):** Semua kata sandi pengguna (*password*) dienkripsi menggunakan algoritma **Bcrypt Hashing**.
5. **SQL Injection:** Seluruh kueri *database* menggunakan *PDO Prepared Statements*.

## 🛠️ Teknologi yang Digunakan
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla), Bootstrap 5, Bootstrap Icons.
* **Backend:** PHP 8.x
* **Database:** MySQL (MariaDB)
* **Library:** [PHPMailer](https://github.com/PHPMailer/PHPMailer) (untuk pengiriman email SMTP)

---

## 🚀 Cara Instalasi dan Penggunaan

Ikuti langkah-langkah berikut untuk menjalankan sistem ini di komputer lokal (*localhost*):

### 1. Persiapan Database
1. Pastikan **XAMPP** (Apache & MySQL) sudah berjalan.
2. Buka `http://localhost/phpmyadmin`.
3. Buat database baru dengan nama `db_auth_otp`.
4. Import file `db_auth_otp.sql` yang ada di dalam folder `database/` repositori ini ke dalam database tersebut.

### 2. Instalasi Dependensi (PHPMailer)
Jika folder `vendor` belum ada di dalam folder `api/`, buka terminal di folder `api/` dan jalankan perintah:
```bash
composer require phpmailer/phpmailer

### 3. Konfigurasi SMTP Email
Sistem ini memerlukan email "Robot" untuk bertugas sebagai pengirim OTP. 
1. Buka file `api/request_otp.php`.
2. Cari bagian **Konfigurasi Server SMTP Gmail**.
3. Ubah bagian berikut dengan email aslimu dan **16-Digit Sandi Aplikasi (App Password)** dari akun Google milikmu:

   `$mail->Username   = 'email_pengirimmu@gmail.com';`
   
   `$mail->Password   = '16_digit_sandi_aplikasi_tanpa_spasi';`

   *(Catatan: Jangan gunakan password email utama Anda. Buat Sandi Aplikasi khusus di pengaturan Keamanan Akun Google).*

### 4. Uji Coba Aplikasi
1. Buka browser dan akses `http://localhost/tugas_otp/daftar.html`.
2. Daftarkan email yang ingin diuji coba.
3. Klik tombol **Coba Simulasi Lupa Sandi** (atau akses `index.html`).
4. Masukkan email yang didaftarkan, lalu cek kotak masuk (Inbox) email tersebut untuk mendapatkan 6 digit OTP.
5. Masukkan OTP untuk memverifikasi dan memperbarui sandi baru.

---

## 👨‍💻 Pengembang
Dikembangkan oleh
- Amanda Fitri Honestya (240103154)
- Anjel Dina Erawati    (240103155)
- Aulia Athifa zahra    (240103156)
- Nayla Putri Salsabila (240103172)
Nayla Putri Salsabila   (240103172)

*Proyek ini murni ditujukan untuk simulasi dan pembelajaran akademik (Information Security).*