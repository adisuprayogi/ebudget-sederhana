# Panduan Deploy Laravel ke CyberPanel

## Prasyarat

- Akses login ke CyberPanel (https://156.67.214.38:8090/base/)
- File aplikasi Laravel (ebudget-sederhana)
- Akses ke File Manager CyberPanel

---

## Langkah 1: Persiapan File di Local

### 1.1 Install Dependencies (jika belum)
```bash
composer install --no-dev --optimize-autoloader
```

### 1.2 Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 1.3 Pastikan folder vendor sudah lengkap

---

## Langkah 2: Buat Website di CyberPanel

1. Login ke CyberPanel: `https://156.67.214.38:8090/base/`

2. Masuk ke menu **Websites** > **Create Website**

3. Isi form:
   - **Select User**: Pilih user yang tersedia
   - **Domain**: Masukkan domain/subdomain (misalnya: `ebudget.yourdomain.com`)
   - **Path**: `/ebudget` (atau sesuai keinginan)
   - **PHP Version**: Pilih **8.2** atau higher

4. Klik **Create Website**

---

## Langkah 3: Upload File Aplikasi

### 3.1 Buka File Manager

1. Masuk ke menu **File Manager**

2. Navigasi ke folder website yang baru dibuat:
   ```
   /home/username/public/ebudget/
   ```

### 3.2 Hapus file default

Hapus file-file default CyberPanel:
- `index.php`
- `cgi-bin` folder
- `favicon.ico`

### 3.3 Upload file Laravel

**Cara 1: Upload Zip (Recommended)**

1. Di local, buat file zip project:
   ```bash
   # Di folder project local
   zip -r ebudget.zip . -x "node_modules/*" ".git/*" ".env.local"
   ```

2. Di CyberPanel File Manager:
   - Klik **Upload**
   - Pilih file `ebudget.zip`
   - Tunggu upload selesai

3. Extract file:
   - Klik kanan pada `ebudget.zip`
   - Pilih **Extract**
   - Pastikan file ter-extract di root folder

**Cara 2: Upload Folder per Folder**

Jika file terlalu besar, upload folder penting:
```
app/
bootstrap/
config/
database/
public/
resources/
routes/
vendor/      <-- Jika sudah ada di local
.env.example
composer.json
composer.lock
artisan
```

---

## Langkah 4: Buat Database

### 4.1 Create Database di CyberPanel

1. Masuk ke menu **Databases** > **Create Database**

2. Isi form:
   - **Database Name**: `ebudget_db` (atau nama lain)
   - **Username**: `ebudget_user`
   - **Password**: Buat password kuat
   - **Host**: Localhost

3. Klik **Submit**

4. **Simpan** kredensial database ini:
   - Database Name
   - Username
   - Password

---

## Langkah 5: Konfigurasi Environment

### 5.1 Buat file .env

1. Di File Manager, cari file `.env.example`

2. Rename menjadi `.env`:
   - Klik kanan pada `.env.example`
   - Pilih **Rename**
   - Ubah nama menjadi `.env`

3. Edit file `.env`:
   - Klik kanan pada `.env`
   - Pilih **Edit**

### 5.2 Isi konfigurasi database

Edit bagian berikut di `.env`:

```env
APP_NAME="E-Budget Sederhana"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://ebudget.yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebudget_db        # Ganti dengan nama database
DB_USERNAME=ebudget_user       # Ganti dengan username database
DB_PASSWORD=your_password      # Ganti dengan password database

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## Langkah 6: Generate APP_KEY

### 6.1 Generate APP_KEY

Di CyberPanel ada **Terminal** yang bisa digunakan:

1. Masuk ke menu **List Websites**

2. Klik tombol **Launch** pada website yang sudah dibuat

3. Klik **Terminal**

4. Jalankan perintah:
```bash
php artisan key:generate
```

5. APP_KEY akan otomatis ter-generate dan diupdate ke `.env`

---

## Langkah 7: Setup Folder Permissions

### 7.1 Set Permissions via Terminal

Di CyberPanel Terminal yang sama, jalankan:

```bash
# Set folder permissions
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (jika perlu)
chown -R lsc:lsc .
```

### 7.2 Alternative: Set Permissions via File Manager

Jika terminal tidak tersedia:

1. Buka File Manager

2. Klik kanan folder `storage` > **Permissions**

3. Set permission ke **775**

4. Lakukan hal yang sama untuk folder `bootstrap/cache`

---

## Langkah 8: Install Dependencies (jika vendor tidak ada)

### 8.1 Via Terminal CyberPanel

Jika folder `vendor/` belum ada:

1. Buka Terminal CyberPanel

2. Jalankan:
```bash
composer install --no-dev --optimize-autoloader
```

**Jika composer tidak tersedia**, Anda perlu:

### 8.2 Upload vendor folder dari local

1. Di local, jalankan:
```bash
composer install --no-dev --optimize-autoloader
```

2. Zip folder `vendor`:
```bash
zip -r vendor.zip vendor/
```

3. Upload ke CyberPanel dan extract

---

## Langkah 9: Run Migrations

### 9.1 Jalankan Migration

Di Terminal CyberPanel, jalankan:

```bash
php artisan migrate --force
```

**Jika ada error**, cek koneksi database di `.env`

### 9.2 Seeding (Optional)

Jika ada seeder untuk data awal:

```bash
php artisan db:seed --force
```

---

## Langkah 10: Setup Document Root

### 10.1 Point ke folder public

1. Di CyberPanel, masuk ke menu **List Websites**

2. Klik **Edit** pada website yang sudah dibuat

3. Di bagian **Advanced** > **Document Root**

4. Ubah path menjadi:
   ```
   /home/username/public/ebudget/public
   ```

5. Klik **Save**

---

## Langkah 11: Clear Cache

### 11.1 Clear semua cache

Di Terminal CyberPanel:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Langkah 12: Test Aplikasi

1. Buka browser dan akses domain/subdomain yang sudah disetup

2. Coba login dengan akun yang sudah dibuat (melalui seeder atau buat manual)

3. Test fitur-fitur utama aplikasi

---

## Troubleshooting

### Error 500

Cek file log di:
```
storage/logs/laravel.log
```

Common causes:
- `.env` belum dibuat atau salah konfigurasi
- Folder `storage` dan `bootstrap/cache` tidak writable
- Database connection failed

### Permission Denied

Pastikan permission sudah benar:
```bash
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Database Connection Failed

Cek kembali konfigurasi di `.env`:
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- DB_HOST (biasanya 127.0.0.1 atau localhost)

### 404 Not Found

Pastikan Document Root sudah mengarah ke folder `public`

---

## Setup SSL (Recommended)

1. Di CyberPanel, masuk ke menu **Websites** > **SSL**

2. Pilih website yang sudah dibuat

3. Pilih **Let's Encrypt** (gratis)

4. Klik **Issue SSL**

5. Aktifkan **Force HTTP to HTTPS**

---

## Checklist Deploy

- [ ] Website sudah dibuat di CyberPanel
- [ ] File Laravel sudah diupload
- [ ] Database sudah dibuat
- [ ] File `.env` sudah dikonfigurasi
- [ ] APP_KEY sudah di-generate
- [ ] Folder permissions sudah di-set
- [ ] Dependencies sudah terinstall
- [ ] Migration sudah dijalankan
- [ ] Document root sudah mengarah ke `/public`
- [ ] Cache sudah di-clear
- [ ] SSL sudah terinstall (optional)
- [ ] Aplikasi sudah di-test

---

## Maintenance Setelah Deploy

### Update Application

Untuk update aplikasi di masa depan:

1. Upload file yang diubah

2. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Backup

CyberPanel biasanya sudah menyediakan fitur backup. Atur schedule backup untuk:
- Database
- File aplikasi

### Monitoring

Cek regularly:
- `storage/logs/laravel.log` untuk error
- Disk space usage
- Database performance
