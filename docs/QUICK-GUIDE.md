# eBudget Sederhana - Quick Guide

## Panduan Cepat

### 1. Login Pertama Kali

```
URL: http://localhost:8000/login
Username: superadmin
Password: password
```

### 2. Alur Kerja Singkat

```
Periode Anggaran → Program Kerja → Pengajuan Dana → Approval
→ Pencairan → LPJ → (Jika ada sisa) Refund
```

### 3. Kode Warna Status

| Warna | Status |
|-------|--------|
| 🟢 Hijau | Disetujui / Selesai / Sukses |
| 🟡 Kuning | Pending / Menunggu / Proses |
| 🔴 Merah | Ditolak / Gagal / Batal |
| 🔵 Biru | Draft / Baru |

### 4. Shortcut Keyboard

| Tombol | Fungsi |
|--------|---------|
| `D` | Dashboard |
| `P` | Pengajuan |
| `A` | Approvals |
| `ESC` | Kembali |

### 5. Quick Links

| Menu | URL |
|------|-----|
| Dashboard | `/dashboard` |
| Periode Anggaran | `/periode-anggaran` |
| Program Kerja | `/program-kerja` |
| Pengajuan Dana | `/pengajuan-dana` |
| Pencairan Dana | `/pencairan-dana` |
| LPJ | `/laporan-pertanggung-jawaban` |
| Refund | `/refund` |
| Approvals | `/approvals` |

---

## Workflow Dasar

### Staff Divisi - Mengajukan Dana

1. Login ke sistem
2. Buka menu **Program Kerja**
3. Buat **Sub Program** (jika belum ada)
4. Buka menu **Pengajuan Dana**
5. Klik **Buat Pengajuan**
6. Pilih program/sub-program
7. Isi detail dan jumlah dana
8. Upload lampiran
9. Klik **Kirim**

### Kepala Divisi - Approval

1. Buka menu **Approvals**
2. Review pengajuan dari staff
3. Klik **Setuju** atau **Tolak**
4. Tambahkan catatan jika perlu

### Staff Keuangan - Pencairan

1. Buka menu **Pencairan Dana**
2. Pilih pengajuan yang sudah disetujui
3. Verifikasi data penerima
4. Klik **Proses Pencairan**
5. Upload bukti transfer
6. Konfirmasi

### Staff Divisi - LPJ

1. Buka menu **LPJ**
2. Klik **Buat LPJ**
3. Pilih pencairan yang akan dilaporkan
4. Isi detail kegiatan dan hasil
5. Upload dokumentasi
6. Isi rincian penggunaan dana
7. Jika ada sisa, proses refund
8. Klik **Kirim LPJ**

---

## FAQ - Pertanyaan Umum

### Q: Bagaimana cara reset password?

**A:** Hubungi administrator sistem untuk reset password. Untuk demo, gunakan password default `password`.

### Q: Kenapa pengajuan saya belum muncul di approvals?

**A:** Pastikan:
- Periode anggaran sudah aktif
- Status pengajuan bukan Draft
- Anda login sebagai user yang memiliki hak approval

### Q: Bisakah mengedit pengajuan setelah dikirim?

**A:** Tidak bisa. Pengajuan yang sudah dikirim tidak dapat diedit. Batalkan dan buat baru jika masih status Pending.

### Q: Bagaimana jika ada sisa dana dari LPJ?

**A:** Sisa dana akan otomatis diproses refund. Pilih metode pengembalian saat membuat LPJ.

### Q: Berapa lama proses approval?

**A:** Tergantung level approver:
- Level 1 (Kepala Divisi): 1-2 hari
- Level 2 (Direktur Keuangan): 2-3 hari
- Level 3 (Direktur Utama): 3-5 hari

### Q: Apakah bisa mengajukan dana tunai?

**A:** Ya, pilih metode pencairan "Tunai" saat membuat pengajuan.

---

## Tips Cepat

### 💡 Tips Pengajuan Cepat Disetujui

1. Isi detail dengan jelas dan lengkap
2. Lampirkan dokumen pendukung yang relevan
3. Ajukan jauh hari sebelum kegiatan (minimal 7 hari)
4. Sesuaikan dengan sisa anggaran tersedia
5. Pastikan program kerja sudah disetujui

### 💡 Tips LPJ Cepat Disetujui

1. Kirim LPJ maksimal 7 hari setelah kegiatan
2. Dokumentasikan dengan foto/video kegiatan
3. Lampirkan bukti pengeluaran lengkap
4. Jujur dalam melaporan penggunaan dana
5. Proses refund sisa dana segera

---

## Troubleshooting Cepat

### Masalah | Solusi
---------|----------
Login gagal | Cek email/password, reset jika perlu
Data tidak muncul | Refresh halaman, cek filter
Tombol tidak aktif | Cek hak akses, status data
Error saat submit | Isi semua field wajib, cek ukuran file
Loading lama | Cek koneksi internet, refresh halaman

---

## Kontak Support

```
📧 Email: support@ebudget.local
📱 WhatsApp: +62 XXX XXXX
🕒 Jam Operasional: Senin - Jumat, 08:00 - 17:00
```

---

*Dokumentasi ini bagian dari eBudget Sederhana Manual Book*
