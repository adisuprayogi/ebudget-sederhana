# eBudget Sederhana - Dokumentasi

## 📚 Daftar Dokumentasi

| Dokumen | Deskripsi | Format |
|----------|-----------|--------|
| **[MANUAL-BOOK.md](MANUAL-BOOK.md)** | Panduan lengkap penggunaan aplikasi | Markdown |
| **[QUICK-GUIDE.md](QUICK-GUIDE.md)** | Panduan cepat dan FAQ | Markdown |
| **[SCREENSHOTS.md](SCREENSHOTS.md)** | Kumpulan screenshot aplikasi | Markdown |

## 📸 Screenshot Aplikasi

Semua screenshot tersedia di folder `screenshots/manual/`:

| File | Deskripsi |
|------|-----------|
| `01-login-page.png` | Halaman login |
| `02-dashboard.png` | Dashboard utama |
| `03-periode-anggaran-list.png` | Daftar periode anggaran |
| `05-program-kerja-list.png` | Daftar program kerja |
| `06-pengajuan-dana-list.png` | Daftar pengajuan dana |
| `08-pencairan-dana-list.png` | Daftar pencairan dana |
| `09-lpj-list.png` | Daftar LPJ |
| `10-refund-list.png` | Daftar refund |
| `11-approvals-list.png` | Daftar approval |
| `12-profile.png` | Halaman profil |
| `13-menu-navigation.png` | Navigasi menu |

---

## 🔧 Teknis Dokumentasi

### Menjalankan Tests

```bash
# Jalankan semua E2E tests
npx playwright test

# Jalankan screenshot capture
npx playwright test tests/e2e/screenshots.spec.cjs

# Lihat HTML report
npx playwright show-report
```

### Build Dokumentasi

Untuk mengubah Markdown ke PDF:

```bash
# Menggunakan pandoc (jika terinstall)
pandoc MANUAL-BOOK.md -o manual-book.pdf

# Atau menggunakan markdown-pdf (VS Code extension)
# Klik kanan pada file .md -> Markdown PDF: Export
```

---

## 📖 Panduan Penggunaan

### Untuk Pengguna Baru

1. Baca **[QUICK-GUIDE.md](QUICK-GUIDE.md)** untuk memulai
2. Pelajari **[MANUAL-BOOK.md](MANUAL-BOOK.md)** untuk detail lengkap
3. Lihat **screenshots** untuk visualisasi

### Untuk Administrator

1. Setup user roles dan permissions
2. Konfigurasi periode anggaran
3. Monitor approval queue
4. Generate laporan bulanan

### Untuk Developer

1. Cek `tests/e2e/` untuk test cases
2. Lihat `routes/web.php` untuk routing
3. Review controllers di `app/Http/Controllers/`
4. Models di `app/Models/`

---

## 🎓 Training Materials

### Modul 1: Dasar-dasar (30 menit)
- Login dan Dashboard
- Navigasi Menu
- Profil Pengguna

### Modul 2: Perencanaan (45 menit)
- Periode Anggaran
- Program Kerja
- Penetapan Pagu

### Modul 3: Pengajuan Dana (45 menit)
- Membuat Pengajuan
- Upload Dokumen
- Tracking Status

### Modul 4: Approval (30 menit)
- Review Pengajuan
- Approval/Rejection
- Catatan Approval

### Modul 5: Pencairan & LPJ (45 menit)
- Proses Pencairan
- Buat LPJ
- Refund

---

## 📞 Support

### Technical Support
- Email: support@ebudget.local
- GitHub Issues: [Create Issue]

### User Support
- Telepon: +62 XXX XXXX
- WhatsApp: +62 XXX XXXX
- Email: help@ebudget.local

---

*Dokumentasi ini diperbarui secara berkala*
*Versi: 1.0.0 | Terakhir update: Februari 2026*
