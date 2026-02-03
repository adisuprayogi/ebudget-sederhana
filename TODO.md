# TODO List - eBudget Sederhana

## ✅ Selesai

### Backend - Migration & Model
- [x] Migration `refund_details` table
- [x] Migration `metode_refund` ke `refunds` table
- [x] Migration buat `lpj_id` nullable di `refunds` table
- [x] Migration tambah `periode_anggaran_id` ke `pengajuan_danas` table
- [x] Migration tambah `periode_anggaran_id` ke `pencairan_danas` table
- [x] Migration tambah `periode_anggaran_id` ke `laporan_pertanggung_jawabans` table
- [x] Model `RefundDetail.php`
- [x] Update model `Refund` dengan relasi `refundDetails()` dan `lpjs()`
- [x] Update model `LaporanPertanggungJawaban` dengan relasi `refundDetails()`
- [x] Update model `PencairanDana` dengan `periode_anggaran_id`
- [x] Update model `LaporanPertanggungJawaban` dengan `periode_anggaran_id`

### Backend - Controller & Routes
- [x] Update `RefundController`:
  - [x] `validateLpjSelection()` - validasi LPJ yang dipilih (API)
  - [x] `createFromSelection()` - form refund dari LPJ yang dipilih
  - [x] `storeFromSelection()` - simpan refund + refund_details
  - [x] Update `store()` untuk handle `metode_refund`
- [x] Tambah route:
  - `POST /refund/validate-lpj`
  - `GET /refund/create-from-selection`
  - `POST /refund/store-from-selection`
- [x] Tambah validasi blokir pengajuan di `PengajuanDanaController@create`
- [x] Fix migration bug di `add_columns_to_program_kerjas_table`
- [x] Jalankan semua migration

---

## ⏳ Belum Selesai

### 1. VIEW - UI Flow Refund Baru

#### 1.1 Update `refund/select-lpj.blade.php`
**File**: `resources/views/refund/select-lpj.blade.php`

**Fitur**:
- Tampilkan list LPJ yang wajib di-refund (approved + sisa_dana > 0 + belum ada refund aktif)
- Checkbox untuk pilih LPJ (bisa pilih banyak)
- Filter: Periode Anggaran, Divisi, Search
- Validasi: semua LPJ yang dipilih harus periode anggaran yang SAMA
- Tombol "Proses Refund" → akan panggil `validateLpjSelection()` lalu redirect ke `createFromSelection()`

**Tampilan**:
```
┌─────────────────────────────────────────────────────────────┐
│ REFUND - Pilih LPJ                                         │
├─────────────────────────────────────────────────────────────┤
│ Filter: [Periode Anggaran ▼] [Divisi ▼] [Search...        │
├─────────────────────────────────────────────────────────────┤
│ ☐ | No. LPJ | Uraian | Sisa Dana | Periode | Aksi        │
│ ☐ | LPJ-001 | Keg A  | Rp 500rb | 2026   | Detail       │
│ ☑ | LPJ-002 | Keg B  | Rp 750rb | 2026   | Detail       │
│ ☐ | LPJ-003 | Keg C  | Rp 300rb | 2026   | Detail       │
├─────────────────────────────────────────────────────────────┤
│ Total terpilih: Rp 750.000                                  │
│ [Proses Refund]                                             │
└─────────────────────────────────────────────────────────────┘
```

**JavaScript**:
- Fetch ke `/refund/validate-lpj` saat tombol "Proses Refund" diklik
- Jika valid (periode sama): redirect ke `/refund/create-from-selection?lpj_ids[]=`
- Jika tidak valid: tampilkan error

#### 1.2 Buat `refund/create-from-selection.blade.php`
**File**: `resources/views/refund/create-from-selection.blade.php`

**Fitur**:
- Tampilkan ringkasan LPJ yang dipilih (read-only)
- Form refund dengan field:
  - Tanggal Refund (date picker)
  - Jumlah Refund (auto total, bisa di-edit)
  - Metode Refund (dropdown: transfer, cash, potong_gaji, lainnya)
  - Jenis Refund (dropdown)
  - Alasan Refund (textarea)
  - Rekening Perusahaan (dropdown, required jika metode=transfer)
  - Rekening Pengirim (text, optional)
  - Nama Pengirim (text, optional)
- Submit ke `/refund/store-from-selection`

**Tampilan**:
```
┌─────────────────────────────────────────────────────────────┐
│ BUAT REFUND                                                  │
├─────────────────────────────────────────────────────────────┤
│ LPJ yang akan di-refund:                                     │
│ ✓ LPJ-002 - Kegiatan B - Rp 750.000                          │
│ ✓ LPJ-003 - Kegiatan C - Rp 300.000                          │
│ ───────────────────────────────────────────────────────────  │
│ TOTAL: Rp 1.050.000                                          │
├─────────────────────────────────────────────────────────────┤
│ Tanggal Refund     : [📅]                      *           │
│ Jumlah Refund      : [Rp 1.050.000            ] *           │
│ Metode Refund      : [Transfer ▼]              *           │
│ Jenis Refund       : [Kelebihan Bayar ▼]       *           │
│ Alasan Refund      : [.....................]     *           │
│ Rekening Tujuan    : [BCN - XXXXXX (Default) ▼] *          │
│ Rekening Pengirim : [........................]                │
│ Nama Pengirim     : [........................]                │
│                                                              │
│                    [Simpan Draft] [Kirim]                    │
└─────────────────────────────────────────────────────────────┘
```

#### 1.3 Update `refund/create.blade.php`
**File**: `resources/views/refund/create.blade.php`

**Update**:
- Tambah field `metode_refund` (dropdown)
- Pastikan form sudah sesuai dengan field baru

---

### 2. JOB/SCHEDULER - Notifikasi Akhir Bulan

#### 2.1 Buat Job `SendRefundReminderJob`
**File**: `app/Jobs/SendRefundReminderJob.php`

**Logic**:
- Jalankan setiap tanggal 25-31 setiap bulan
- Cari user yang punya LPJ:
  - `status = approved`
  - `sisa_dana > 0`
  - `belum ada refund aktif`
  - LPJ dari user tersebut (created_by)
- Kirim notifikasi ke user:
  ```
  "Bulan ini akan berakhir. Anda punya X LPJ dengan total sisa
   dana Rp XXX yang wajib di-refund."
  ```

#### 2.2 Buat Command `SendRefundReminderCommand`
**File**: `app/Console/Commands/SendRefundReminderCommand.php`

**Command**: `php artisan refund:send-reminder`

#### 2.3 Setup Scheduler
**File**: `app/Console/Kernel.php` (Laravel 10) atau `routes/console.php`

**Schedule**:
```php
$schedule->command('refund:send-reminder')
    ->monthlyOn(25, '00:00')
    ->when(function() {
        return now()->day >= 25;
    });
```

---

### 3. TESTING

- [ ] Test flow pilih LPJ → validasi → create refund
- [ ] Test validasi blokir pengajuan (ada sisa LPJ bulan lalu)
- [ ] Test notifikasi pengingat
- [ ] Test scheduler notifikasi akhir bulan

---

## Catatan Penting

### Sumber `periode_anggaran_id`
| Tabel | Sumber |
|-------|--------|
| `pengajuan_danas` | `program_kerja->periode_anggaran_id` |
| `pencairan_danas` | `pengajuan_dana->periode_anggaran_id` |
| `laporan_pertanggung_jawabans` | `pencairan_dana->periode_anggaran_id` |
| `refunds` | `pencairan_dana->periode_anggaran_id` (sudah ada) |

### Aturan Validasi
- **Refund dari banyak LPJ**: semua LPJ harus periode anggaran yang SAMA
- **Blokir pengajuan**: jika ada LPJ bulan-bulan lalu yang belum di-refund

### Metode Refund
- `transfer` - butuh rekening_perusahaan_id
- `cash` - tidak butuh rekening
- `potong_gaji` - tidak butuh rekening
- `lainnya` - tidak butuh rekening

---

## Database
- **Host**: 127.0.0.1
- **Port**: 3306
- **Database**: db_ebudget_sederhana
- **Username**: root
- **Password**: 12345678

---

## Aplikasi
- **URL**: http://127.0.0.1:8002
- **Start**: `php artisan serve --host=127.0.0.1 --port=8002`

---

*Tanggal: 2026-01-29*
