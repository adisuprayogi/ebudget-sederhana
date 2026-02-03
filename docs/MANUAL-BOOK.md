# eBudget Sederhana - Manual Book Pengguna

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Login ke Aplikasi](#login-ke-aplikasi)
3. [Dashboard](#dashboard)
4. [Manajemen Periode Anggaran](#manajemen-periode-anggaran)
5. [Manajemen Program Kerja](#manajemen-program-kerja)
6. [Pengajuan Dana](#pengajuan-dana)
7. [Pencairan Dana](#pencairan-dana)
8. [Laporan Pertanggungjawaban (LPJ)](#laporan-pertanggungjawaban-lpj)
9. [Refund Dana](#refund-dana)
10. [Sistem Approval](#sistem-approval)
11. [Profil Pengguna](#profil-pengguna)

---

## Pendahuluan

### Tentang eBudget Sederhana

**eBudget Sederhana** adalah sistem manajemen anggaran terintegrasi yang dirancang untuk membantu organisasi dalam mengelola perencanaan, pengajuan, pencairan, dan pertanggungjawaban dana secara transparan dan akuntabel.

### Fitur Utama

- ✅ Perencanaan dan penetapan anggaran per periode
- ✅ Pengajuan dana dengan workflow approval
- ✅ Pencairan dana dengan tracking real-time
- ✅ Laporan pertanggungjawaban (LPJ) digital
- ✅ Sistem refund untuk dana tidak terpakai
- ✅ Multi-level approval system
- ✅ Dashboard monitoring dan pelaporan

### Hak Akses Pengguna

| Role | Keterangan |
|------|------------|
| **Superadmin** | Akses penuh ke seluruh fitur sistem |
| **Direktur Utama** | Approval dan monitoring level tertinggi |
| **Direktur Keuangan** | Manajemen keuangan dan approval |
| **Kepala Divisi** | Pengajuan dan monitoring divisi |
| **Staff Divisi** | Pengajuan dana dan LPJ |
| **Staff Keuangan** | Pencairan dan administrasi keuangan |

---

## Login ke Aplikasi

### Halaman Login

![Login Page](../screenshots/manual/01-login-page.png)

### Cara Login

1. Buka browser dan kunjungi URL aplikasi
2. Masukkan **Email** atau **Username**
3. Masukkan **Password**
4. Klik tombol **Log in**

### Default User untuk Testing

| Username | Email | Password | Role |
|----------|-------|----------|------|
| superadmin | superadmin@example.com | password | Superadmin |
| direktutama | direktur.utama@example.com | password | Direktur Utama |
| direkturkeuangan | direktur@example.com | password | Direktur Keuangan |

---

## Dashboard

### Tampilan Dashboard

![Dashboard](../screenshots/manual/02-dashboard.png)

### Menu Navigasi

![Menu Navigation](../screenshots/manual/13-menu-navigation.png)

Dashboard memberikan ringkasan informasi:
- **Statistik Anggaran** - Total pagu, terealisasi, dan sisa anggaran
- **Pengajuan Dana** - Pending, disetujui, dan ditolak
- **Pencairan Dana** - Status pencairan
- **LPJ** - Status laporan pertanggungjawaban
- **Notifications** - Notifikasi aktivitas terbaru

---

## Manajemen Periode Anggaran

### Daftar Periode Anggaran

![Periode Anggaran List](../screenshots/manual/03-periode-anggaran-list.png)

### Membuat Periode Anggaran Baru

1. Klik menu **Periode Anggaran**
2. Klik tombol **Tambah Periode**
3. Isi form:
   - **Nama Periode**: Contoh: "Anggaran 2025"
   - **Tahun**: Tahun anggaran
   - **Tanggal Mulai Perencanaan**: Awal periode perencanaan
   - **Tanggal Selesai Perencanaan**: Akhir periode perencanaan
   - **Tanggal Mulai Penggunaan**: Awal periode penggunaan
   - **Tanggal Selesai Penggunaan**: Akhir periode penggunaan
4. Klik **Simpan**

### Mengaktifkan Periode Anggaran

1. Pada periode yang diinginkan, klik tombol **Aktifkan**
2. Konfirmasi aktivasi
3. Periode akan menjadi aktif untuk pengajuan dana

### Menutup Periode Anggaran

1. Klik tombol **Tutup** pada periode yang ingin ditutup
2. Konfirmasi penutupan
3. Periode tidak dapat menerima pengajuan baru setelah ditutup

---

## Manajemen Program Kerja

### Daftar Program Kerja

![Program Kerja List](../screenshots/manual/05-program-kerja-list.png)

### Membuat Program Kerja Baru

1. Klik menu **Program Kerja**
2. Klik tombol **Tambah Program**
3. Isi form:
   - **Periode Anggaran**: Pilih periode aktif
   - **Divisi**: Divisi pemilik program
   - **Nama Program**: Nama program kerja
   - **Deskripsi**: Deskripsi singkat program
   - **Target Output**: Target yang ingin dicapai
4. Klik **Simpan**

### Sub Program

Setiap program kerja dapat memiliki sub-program:
1. Buka detail program kerja
2. Klik **Tambah Sub Program**
3. Isi nama dan deskripsi sub-program
4. Klik **Simpan**

---

## Pengajuan Dana

### Daftar Pengajuan Dana

![Pengajuan Dana List](../screenshots/manual/06-pengajuan-dana-list.png)

### Status Pengajuan

| Status | Keterangan |
|--------|------------|
| **Draft** | Belum dikirim, masih dapat diedit |
| **Pending** | Menunggu approval |
| **Disetujui** | Disetujui, siap cair |
| **Ditolak** | Pengajuan ditolak |
| **Menunggu Pencairan** | Siap dicairkan |
| **Cair** | Dana sudah dicairkan |
| **Selesai** - LPJ Terkirim | LPJ sudah dikirim |
| **Dibatalkan** | Pengajuan dibatalkan |

### Membuat Pengajuan Dana Baru

1. Klik menu **Pengajuan Dana**
2. Klik tombol **Buat Pengajuan**
3. Pilih **Program Kerja** atau **Sub Program**
4. Pilih **Sumber Dana**
5. Pilih **Metode Pencairan**:
   - Transfer Bank
   - Tunai
   - Rekening Perusahaan
6. Isi **Detail Pengajuan**:
   - Nama kegiatan
   - Jumlah dana yang diajukan
   - Tanggal kebutuhan dana
   - Lampiran dokumen pendukung
7. Klik **Kirim Pengajuan**

### Melihat Detail Pengajuan

1. Klik tombol **Lihat** pada pengajuan yang diinginkan
2. Detail menampilkan:
   - Informasi pengajuan
   - Riwayat approval
   - Status pencairan
   - Status LPJ

### Membatalkan Pengajuan

1. Buka detail pengajuan
2. Klik **Batalkan** (hanya untuk status Draft/Pending)
3. Konfirmasi pembatalan

---

## Pencairan Dana

### Daftar Pencairan Dana

![Pencairan Dana List](../screenshots/manual/08-pencairan-dana-list.png)

### Status Pencairan

| Status | Keterangan |
|--------|------------|
| **Draft** | Belum diproses |
| **Pending** | Menunggu verifikasi |
| **Disetujui** - Siap Cair | Siap untuk transfer |
| **Diproses** | Sedang diproses |
| **Sukses** | Dana berhasil dicairkan |
| **Gagal** | Pencairan gagal |

### Memproses Pencairan Dana

1. Klik menu **Pencairan Dana**
2. Pilih pengajuan yang sudah disetujui
3. Klik **Proses Pencairan**
4. Verifikasi:
   - Penerima dana
   - Nomor rekening
   - Jumlah yang akan dicairkan
5. Upload bukti transfer (setelah dilakukan)
6. Klik **Konfirmasi Pencairan**

---

## Laporan Pertanggungjawaban (LPJ)

### Daftar LPJ

![LPJ List](../screenshots/manual/09-lpj-list.png)

### Status LPJ

| Status | Keterangan |
|--------|------------|
| **Draft** | Belum dikirim |
| **Terkirim** | Menunggu verifikasi |
| **Diterima** - Verifikasi | Sedang diverifikasi |
| **Disetujui** | LPJ disetujui |
| **Ditolak** | LPJ ditolak, perlu revisi |

### Membuat LPJ Baru

1. Klik menu **Laporan Pertanggungjawaban**
2. Klik **Buat LPJ**
3. Pilih **Pencairan Dana** yang akan dilaporkan
4. Isi **Detail LPJ**:
   - Kegiatan yang telah dilaksanakan
   - Hasil yang dicapai
   - Dokumentasi kegiatan (foto/video)
   - Bukti penggunaan dana
5. Isi **Rincian Penggunaan**:
   - Item pengeluaran
   - Jumlah terpakai
   - Sisa dana
6. Upload lampiran:
   - Kwitansi/Invoice
   - Dokumentasi foto
   - Bukti pembayaran
7. Klik **Kirim LPJ**

### Sisa Dana & Refund

Jika ada sisa dana:
1. Isi nominal sisa dana
2. Pilih metode pengembalian:
   - Transfer kembali
   - Ditahan di kas
3. Klik **Proses Refund**

---

## Refund Dana

### Daftar Refund

![Refund List](../screenshots/manual/10-refund-list.png)

### Status Refund

| Status | Keterangan |
|--------|------------|
| **Pending** | Menunggu diproses |
| **Diproses** | Sedang diproses |
| **Selesai** | Refund selesai |
| **Gagal** | Refund gagal |

### Membuat Refund

Refund dapat dibuat dari:
1. **Sisa LPJ** - Dana tidak terpakai dari LPJ
2. **Pengajuan Dibatalkan** - Setelah pencairan dilakukan

### Memproses Refund

1. Klik menu **Refund**
2. Pilih refund yang akan diproses
3. Verifikasi:
   - Jumlah refund
   - Rekening tujuan pengembalian
4. Lakukan transfer ke rekening asal
5. Upload bukti transfer
6. Klik **Konfirmasi Refund**

---

## Sistem Approval

### Daftar Approval

![Approvals List](../screenshots/manual/11-approvals-list.png)

### Level Approval

Approval dilakukan secara berjenjang:
1. **Level 1**: Kepala Divisi
2. **Level 2**: Direktur Keuangan
3. **Level 3**: Direktur Utama

### Menyetujui Pengajuan

1. Klik menu **Approvals**
2. Pilih pengajuan yang perlu approval
3. Review detail pengajuan
4. Tambahkan catatan approval (opsional)
5. Klik **Setuju**

### Menolak Pengajuan

1. Klik menu **Approvals**
2. Pilih pengajuan yang akan ditolak
3. Klik **Tolak**
4. Isi alasan penolakan (wajib)
5. Klik **Konfirmasi Penolakan**

### Bulk Approval

Untuk menyetujui banyak pengajuan sekaligus:
1. Pilih pengajuan dengan checkbox
2. Klik **Setuju Terpilih**
3. Konfirmasi bulk approval

---

## Profil Pengguna

### Halaman Profil

![Profile](../screenshots/manual/12-profile.png)

### Mengupdate Profil

1. Klik avatar/nama user di pojok kanan atas
2. Klik **Profile**
3. Edit informasi:
   - **Nama Lengkap**
   - **Email**
   - **Username**
   - **Nomor Telepon**
4. Klik **Simpan**

### Mengubah Password

1. Di halaman Profile
2. Scroll ke bagian **Password**
3. Isi:
   - **Password Saat Ini**
   - **Password Baru**
   - **Konfirmasi Password Baru**
4. Klik **Update Password**

---

## Tips dan Best Practices

### Perencanaan Anggaran

1. **Buat periode anggaran** dengan jelas dan realistis
2. **Definisikan program kerja** dengan target yang terukur
3. **Alokasikan dana** berdasarkan prioritas
4. **Review dan update** secara berkala

### Pengajuan Dana

1. **Isi detail pengajuan** dengan jelas dan lengkap
2. **Lampirkan dokumen pendukung** yang relevan
3. **Ajukan jauh hari** sebelum kegiatan
4. **Monitor status** pengajuan secara berkala

### LPJ dan Pertanggungjawaban

1. **Kirim LPJ** tepat waktu setelah kegiatan
2. **Dokumentasikan** dengan bukti yang lengkap
3. **Jujur dan transparan** dalam pelaporan penggunaan dana
4. **Proses refund** sisa dana segera setelah LPJ

### Troubleshooting

### Masalah Login

**Tidak bisa login?**
- Pastikan email/username benar
- Reset password jika lupa
- Hubungi admin jika akun terkunci

### Pengajuan Tidak Muncul

- Pastikan periode anggaran aktif
- Periksa hak akses user
- Hubungi admin divisi terkait

### Approval Terlambat

- Hubungi approver langsung
- Cek notifikasi sistem
- Gunakan fitur follow-up

---

## Kontak & Bantuan

### Admin System

Untuk bantuan teknis dan pertanyaan:
- Email: support@ebudget.local
- Telepon: +62 XXX XXXX

### Dokumentasi Teknis

Dokumentasi teknis dan API tersedia di:
- GitHub Repository
- Wiki Internal

---

## Changelog

### Versi 1.0
- Initial release
- Fitur dasar pengajuan dan pencairan
- Sistem approval multi-level
- Modul LPJ dan refund
- Dashboard monitoring

---

*Dokumentasi ini dibuat untuk eBudget Sederhana versi 1.0*
*Terakhir diperbarui: Februari 2026*
