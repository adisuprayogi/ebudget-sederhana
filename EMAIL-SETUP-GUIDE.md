# Panduan Setup Email Notification

Panduan lengkap untuk mengkonfigurasi dan menggunakan fitur notifikasi email pada aplikasi E-Budget.

---

## Status Fitur

Fitur email notification telah **AKTIF** dan siap digunakan. Berikut adalah jenis notifikasi yang tersedia:

| Jenis Notifikasi | Penerima | Keterangan |
|------------------|----------|------------|
| Approval Request | Approver | Ketika ada pengajuan menunggu approval |
| Pengajuan Approved | Pengaju | Ketika pengajuan disetujui |
| Pengajuan Rejected | Pengaju | Ketika pengajuan ditolak |
| Ready for Pencairan | Staff Keuangan | Ketika pengajuan siap dicairkan |
| Pencairan Processed | Pengaju | Ketika pencairan diproses |
| LPJ Reminder | Pengaju | Pengingat untuk membuat LPJ |
| LPJ Submitted | Staff Keuangan | Ketika LPJ disubmit |
| Refund Processed | Pengaju | Ketika refund diproses |
| Daily Summary | Direktur Keuangan | Ringkasan harian aktivitas |

---

## Konfigurasi Email

### 1. Setup SMTP di File `.env`

Salin konfigurasi berikut ke file `.env` Anda:

```env
# MAIL CONFIGURATION
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Test email address
MAIL_TEST_ADDRESS=test@example.com
```

### 2. Contoh Konfigurasi untuk Provider Berbeda

#### Gmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

**Catatan:** Gunakan "App Password" bukan password regular. Buat di: https://myaccount.google.com/apppasswords

#### Mailgun

```env
MAIL_MAILER=mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=your-mailgun-api-key
MAIL_ENCRYPTION=tls
```

#### Amazon SES

```env
MAIL_MAILER=ses
MAIL_HOST=email-smtp.us-east-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=your-ses-smtp-username
MAIL_PASSWORD=your-ses-smtp-password
MAIL_ENCRYPTION=tls
```

#### SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

---

## Setup Queue Worker (Recommended)

Email dikirim menggunakan queue untuk performa yang lebih baik. Setup queue worker:

### 1. Buat Tabel Jobs

```bash
php artisan queue:table
php artisan migrate
```

### 2. Konfigurasi Queue di `.env`

```env
QUEUE_CONNECTION=database
```

### 3. Jalankan Queue Worker

**Development:**
```bash
php artisan queue:work
```

**Production dengan Supervisor:**

Buat file config:
```bash
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

---

## Testing Email Configuration

### Test via Tinker

```bash
php artisan tinker
```

```php
// Test kirim email
App\Services\EmailNotificationService::testEmailConfiguration('your-email@example.com');
```

### Test via Controller

Buat route testing sementara di `routes/web.php`:

```php
Route::get('/test-email', function() {
    $result = App\Services\EmailNotificationService::testEmailConfiguration('your-email@example.com');
    return $result ? 'Email sent!' : 'Failed to send email';
});
```

Akses: `http://your-domain.com/test-email`

---

## Mengirim Email secara Programatik

### Contoh Penggunaan

```php
use App\Services\EmailNotificationService;

// Kirim notifikasi approval request
EmailNotificationService::sendApprovalRequest($approval, $pengajuan);

// Kirim notifikasi pengajuan disetujui
EmailNotificationService::sendPengajuanApproved($pengajuan);

// Kirim notifikasi pengajuan ditolak
EmailNotificationService::sendPengajuanRejected($pengajuan, $approval, 'Catatan penolakan');

// Kirim notifikasi siap pencairan
EmailNotificationService::sendReadyForPencairan($pengajuan);

// Kirim daily summary
EmailNotificationService::sendDailySummary();
```

---

## Troubleshooting

### Email Tidak Terkirim

1. **Cek log Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Cek konfigurasi mail:**
   ```bash
   php artisan config:clear
   php artisan tinker
   >>> config('mail')
   ```

3. **Test koneksi SMTP:**
   ```bash
   telnet smtp.gmail.com 587
   ```

### Queue Worker Tidak Berjalan

1. **Cek status queue:**
   ```bash
   php artisan queue:failed
   ```

2. **Retry failed jobs:**
   ```bash
   php artisan queue:retry all
   ```

3. **Cek queue worker:**
   ```bash
   ps aux | grep queue:work
   ```

### Gmail Authentication Error

Jika error "Authentication failed", gunakan App Password:
1. Buka https://myaccount.google.com/apppasswords
2. Buat App Password baru
3. Gunakan App Password tersebut di `MAIL_PASSWORD`

---

## Mengatur Jadwal Email

### LPJ Reminder (Daily)

Tambahkan ke `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Kirim LPJ reminder setiap hari jam 9 pagi
    $schedule->call(function () {
        $pengajuans = PengajuanDana::where('status', 'dicairkan')
            ->whereDoesntHave('lpjs')
            ->where('dicairkan_at', '>', now()->subDays(7))
            ->get();

        foreach ($pengajuans as $pengajuan) {
            EmailNotificationService::sendLpjReminder($pengajuan);
        }
    })->dailyAt('09:00');

    // Kirim daily summary ke direktur keuangan setiap jam 8 pagi
    $schedule->call(function () {
        EmailNotificationService::sendDailySummary();
    })->dailyAt('08:00');
}
```

---

## Security Best Practices

1. **Jangan commit .env file** yang berisi password email
2. **Gunakan App Password** untuk Gmail, bukan password utama
3. **Gunakan environment variables** untuk menyimpan kredensial email
4. **Enable 2FA** pada akun email yang digunakan
5. **Monitor failed jobs** secara regular
6. **Rate limiting** untuk mencegah spam

---

## Mengubah Email Template

Template email terletak di `resources/views/emails/`:

- `approval-request.blade.php` - Notifikasi approval request
- `pengajuan-approved.blade.php` - Notifikasi pengajuan disetujui
- `pengajuan-rejected.blade.php` - Notifikasi pengajuan ditolak
- `ready-for-pencairan.blade.php` - Notifikasi siap pencairan
- `pencairan-processed.blade.php` - Notifikasi pencairan diproses
- `lpj-reminder.blade.php` - Notifikasi reminder LPJ
- `lpj-submitted.blade.php` - Notifikasi LPJ disubmit
- `refund-processed.blade.php` - Notifikasi refund diproses
- `daily-summary.blade.php` - Ringkasan harian
- `test.blade.php` - Template test

Untuk mengubah tampilan email, edit file blade tersebut.

---

## Support

Jika mengalami masalah:
1. Cek log di `storage/logs/laravel.log`
2. Pastikan queue worker berjalan
3. Verifikasi konfigurasi SMTP
4. Test koneksi SMTP menggunakan telnet
