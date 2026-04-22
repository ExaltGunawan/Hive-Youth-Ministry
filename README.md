# Hive Youth Ministry Management System

Sistem manajemen database jemaat, pelayanan, dan keuangan untuk **Hive Youth Ministry**. Dibangun menggunakan **Laravel 11**, **Filament PHP**, dan **PostgreSQL**.

## Main Features
- **Database Jemaat**: Manajemen data anggota dengan sistem filter canggih.
- **Manajemen Pelayanan**: Penjadwalan ibadah, penugasan pelayan(PIC, Liturgos, Pemusik, dll).
- **Sistem Keuangan (Finance)**: Rencana Anggaran (RKA) dan sistem pengajuan pengambilan uang.
- **Notulensi**: Catatan kontrol akses per divisi.
- **Soft Deletes**: Fitur restore data untuk mencegah kehilangan data yang tidak disengaja.
- **Manajemen Role**: Keamanan berbasis roles (Super Admin, Sekretaris, Bendahara, Divisi).

## Persyaratan Sistem
- PHP 8.2 or newer
- PostgreSQL
- Composer
- Node.js & NPM

## installation

### 1. Clone Repository
```bash
git clone https://github.com/ExaltGunawan/Hive-Youth-Ministry.git
cd Hive-Youth-Ministry
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
```
sesuaikan pengaturan database (menggunakan PostgreSQL).

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Setup Database (Migrate & Seed)
```bash
php artisan migrate --seed
```

### 6. Link Storage
```bash
php artisan storage:link
```

### 7. Run Frontend Assets
```bash
npm run dev
```

### 8. Open Admin Panel
Open `http://localhost:8000/admin` in your browser.

---
*Developed for Hive Youth Ministry.*
