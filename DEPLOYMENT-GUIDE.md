# Panduan Deployment E-Budget Sederhana ke VPS

Panduan lengkap untuk deploying aplikasi Laravel E-Budget Sederhana ke Virtual Private Server (VPS).

---

## Persiapan Di VPS

### 1. System Requirements

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 + extensions
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd php8.2-intl -y

# Install Nginx
sudo apt install nginx -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (untuk Vite build)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y

# Install Git
sudo apt install git -y
```

### 2. Setup Database (MySQL - Recommended)

```bash
# Install MySQL
sudo apt install mysql-server -y

# Secure MySQL
sudo mysql_secure_installation

# Buat database
sudo mysql -u root -p
```

```sql
CREATE DATABASE ebudget_db;
CREATE USER 'ebudget_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON ebudget_db.* TO 'ebudget_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Deploy Aplikasi

### 3. Clone & Setup Aplikasi

```bash
# Clone repository (atau upload via FTP/SFTP)
cd /var/www
sudo git clone <your-repo-url> ebudget-sederhana
cd ebudget-sederhana

# Atur permissions
sudo chown -R www-data:www-data /var/www/ebudget-sederhana
sudo chmod -R 755 /var/www/ebudget-sederhana
sudo chmod -R 775 storage bootstrap/cache

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 4. Konfigurasi Environment

```bash
# Copy .env.example
cp .env.example .env

# Generate APP key
php artisan key:generate

# Edit .env
nano .env
```

**Penting - Edit di .env:**
```env
APP_ENV=production
APP_DEBUG=false          # ← WAJIB false untuk production!
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebudget_db
DB_USERNAME=ebudget_user
DB_PASSWORD=your_secure_password
```

### 5. Run Migrations

```bash
# Run database migrations
php artisan migrate --force

# (Opsional) Seed data jika ada
php artisan db:seed --force

# Clear & cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## Konfigurasi Nginx

### 6. Buat Virtual Host

```bash
sudo nano /etc/nginx/sites-available/ebudget
```

**Paste konfigurasi berikut:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name domainanda.com www.domainanda.com;
    root /var/www/ebudget-sederhana/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/ebudget /etc/nginx/sites-enabled/

# Test config
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

---

## SSL dengan Let's Encrypt (Wajib)

### 7. Install SSL Certificate

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate
sudo certbot --nginx -d domainanda.com -d www.domainanda.com

# Auto-renewal
sudo certbot renew --dry-run
```

---

## Security Hardening

### 8. Keamanan Tambahan

```bash
# Set permissions yang aman
sudo chown -R www-data:www-data /var/www/ebudget-sederhana
sudo chmod -R 755 /var/www/ebudget-sederhana
sudo chmod -R 775 storage bootstrap/cache

# Pastikan .env tidak bisa diakses
sudo chmod 600 .env

# (Opsional) Setup firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## Monitoring & Maintenance

### 9. Setup Queue Worker (jika ada queue)

```bash
# Install Supervisor
sudo apt install supervisor -y

# Buat config
sudo nano /etc/supervisor/conf.d/ebudget-worker.conf
```

```ini
[program:ebudget-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ebudget-sederhana/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/ebudget-sederhana/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ebudget-worker:*
```

### 10. Setup Cron Job (Scheduler)

```bash
crontab -e
```

```
* * * * * cd /var/www/ebudget-sederhana && php artisan schedule:run >> /dev/null 2>&1
```

---

## Checklist Sebelum Production

| Item | Status |
|------|--------|
| APP_ENV=production | |
| APP_DEBUG=false | |
| SSL Certificate | |
| Database backup plan | |
| Firewall configured | |
| Permissions set correctly | |
| Route caching done | |
| Composer --no-dev | |

---

## Troubleshooting Common Issues

### 500 Internal Server Error

Cek log Laravel:
```bash
sudo tail -f /var/www/ebudget-sederhana/storage/logs/laravel.log
```

Cek log Nginx:
```bash
sudo tail -f /var/log/nginx/error.log
```

### Permission denied

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data /var/www/ebudget-sederhana
```

### Database connection failed

- Cek kredensial di `.env`
- Pastikan MySQL service running: `sudo systemctl status mysql`
- Test koneksi: `php artisan tinker` lalu `DB::connection()->getPdo()`

### 502 Bad Gateway

PHP-FPM tidak running:
```bash
sudo systemctl status php8.2-fpm
sudo systemctl start php8.2-fpm
```

---

## Backup Strategy

### Database Backup

Buat script backup:
```bash
nano /var/www/ebudget-sederhana/backup-db.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y-%m-%d-%H%M%S)
BACKUP_DIR="/var/backups/ebudget"
mkdir -p $BACKUP_DIR

mysqldump -u ebudget_user -p'your_password' ebudget_db > $BACKUP_DIR/db-backup-$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "db-backup-*.sql" -mtime +7 -delete
```

```bash
chmod +x /var/www/ebudget-sederhana/backup-db.sh

# Add to crontab (daily at 2 AM)
0 2 * * * /var/www/ebudget-sederhana/backup-db.sh
```

### Application Backup

```bash
# Backup seluruh aplikasi
tar -czf /var/backups/ebudget/app-backup-$(date +%Y-%m-%d).tar.gz /var/www/ebudget-sederhana
```

---

## Update Application

```bash
cd /var/www/ebudget-sederhana

# Pull latest code
sudo -u www-data git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services if needed
sudo supervisorctl restart ebudget-worker:*
```

---

## Spesifikasi VPS Minimum

| Resource | Minimum | Recommended |
|----------|---------|-------------|
| CPU | 1 Core | 2+ Cores |
| RAM | 1 GB | 2-4 GB |
| Storage | 20 GB SSD | 40+ GB SSD |
| OS | Ubuntu 22.04 LTS | Ubuntu 22.04 LTS |

---

## Useful Commands

```bash
# Cek PHP version
php -v

# Cek Composer
composer --version

# Cek Node.js
node -v

# Cek Nginx status
sudo systemctl status nginx

# Restart Nginx
sudo systemctl restart nginx

# Cek PHP-FPM status
sudo systemctl status php8.2-fpm

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Cek MySQL status
sudo systemctl status mysql

# Laravel artisan cache clear
php artisan cache:clear

# Laravel artisan config clear
php artisan config:clear

# Laravel artisan route clear
php artisan route:clear

# Laravel artisan view clear
php artisan view:clear

# Cek queue status
sudo supervisorctl status

# Restart queue worker
sudo supervisorctl restart ebudget-worker:*
```

---

## Support

Jika mengalami masalah:
1. Cek log error di `/var/www/ebudget-sederhana/storage/logs/laravel.log`
2. Cek Nginx error log di `/var/log/nginx/error.log`
3. Pastikan semua permissions sudah benar
4. Verifikasi konfigurasi .env
