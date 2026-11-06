# ruma-by-yota

# RUMA — Ruang Usaha Masyarakat Alam
Platform Pendampingan Usaha Berbasis Hasil Bumi dan Sumber Daya Lokal oleh **SiYota.org**.

---

## 🌾 Tentang RUMA

**RUMA (Ruang Usaha Masyarakat Alam)** adalah platform digital yang dirancang untuk mendampingi kelompok tani, KWT (Kelompok Wanita Tani), dan usaha hasil bumi lokal. Dibangun dengan pendekatan **Mobile-First**, antarmuka visual yang bersih, ramah pengguna desa, serta pemisahan peran yang aman dan terstruktur.

### Fitur Utama:
1. **Public Website (`ruma.siyota.org`)**:
   - Showcase profil kelompok binaan terverifikasi (foto, lokasi, PIC, logo bisnis, komoditas unggulan).
   - Peta interaktif sebaran lahan pertanian/kebun kelompok binaan.
   - Pendaftaran mandiri kelompok tani baru.

2. **Dashboard Kelompok (Mobile App View / PWA)**:
   - Satu kelompok satu akun utama (`USER`).
   - Manajemen profil kelompok, lahan, komoditas, dan produk hasil bumi.
   - Pencatatan transaksi keuangan kas masuk & kas keluar.
   - Ruang belajar, materi pendampingan, SOP, dan jadwal temu/mentoring.
   - Pelaporan pemasaran dan feedback langsung dari fasilitator.

3. **Dashboard Fasilitator**:
   - Monitoring kelompok binaan wilayah dampingan.
   - Evaluasi produk, kurasi materi, penugasan, dan mentoring.

4. **Super Admin**:
   - Verifikasi pendaftaran kelompok, persetujuan konten publik, penugasan fasilitator.
   - Manajemen integrasi gateway WhatsApp, Email Transaksional, dan Google OAuth.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12 (PHP 8.4)
- **Frontend**: Blade + Tailwind CSS (via Vite)
- **Database**: MySQL
- **Maps**: Leaflet.js
- **Auth & Services**: Laravel Socialite (Google OAuth), WhatsApp Gateway Service, SMTP Mailer

---

## 🚀 Panduan Instalasi Lokal

### 1. Clone Repositori
```bash
git clone https://github.com/Trifebri3/ruma-by-yota.git
cd ruma-by-yota
```

### 2. Install Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database `DB_*`, serta kredensial `WHATSAPP_*`, `MAIL_*`, dan `GOOGLE_*` di dalam file `.env`.

### 4. Migrasi & Seeder Database
```bash
php artisan migrate:fresh --seed
```

### 5. Build Aset Frontend & Jalankan Server
```bash
npm run build
php artisan serve
```
Akses aplikasi melalui peramban di `http://localhost:8000`.

---

## 📄 Lisensi
Hak Cipta © 2026 SiYota.org — Inisiatif Pendampingan Masyarakat Alam.
