# RANCANGAN APLIKASI E-BUDGET SEDERHANA

## 1. Overview Aplikasi

Aplikasi budgeting sederhana untuk mengelola keuangan lembaga/perusahaan dengan fitur lengkap dari perencanaan hingga pelaporan.

### Fitur Utama:
- Perencanaan penerimaan
- Pencatatan penerimaan
- Penetapan pagu anggaran
- Perancangan program kerja dan budgeting
- Estimasi waktu dan penggunaan anggaran
- Pengajuan dengan approval system
- Pencairan dana
- Pelaporan penggunaan dana
- LPJ (Laporan Pertanggungjawaban)
- Refund/Sisa dana
- Approval yang dapat dikustomisasi

## 2. Arsitektur Sistem

### Teknologi Stack:
- **Backend**: Laravel 10.x
- **Frontend**: Alpine.js (Local Installation)
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Sanctum
- **UI Framework**: TailwindCSS
- **Icons**: Heroicons
- **File Upload**: Laravel Storage

### Struktur Folder:
```
ebudget-sederhana/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controller classes
│   │   ├── Middleware/       # Custom middleware
│   │   ├── Requests/         # Form Request Validation
│   │   └── Resources/        # API Resources
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Business logic services
│   ├── Rules/                # Custom validation rules
│   └── View/Components/      # Alpine.js Components
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
├── resources/
│   ├── views/               # Blade templates
│   │   ├── layouts/         # Base layouts
│   │   ├── components/      # Reusable blade components
│   │   └── pages/           # Page views
│   └── js/                  # Alpine.js components
│       ├── app.js           # Main Alpine.js file
│       ├── components/      # Component files
│       └── utils/           # Helper functions
├── public/
│   ├── js/                  # Compiled Alpine.js assets
│   └── css/                 # TailwindCSS assets
└── storage/                 # File uploads
```

## 3. Database Design

### Schema MySQL/MariaDB:

```sql
-- Tabel Users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role_id BIGINT UNSIGNED,
    divisi_id BIGINT UNSIGNED,
    is_active BOOLEAN DEFAULT 1,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (divisi_id) REFERENCES divisi(id)
);

-- Tabel Roles
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Divisi/Departemen
CREATE TABLE divisi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_divisi VARCHAR(20) UNIQUE NOT NULL,
    nama_divisi VARCHAR(200) NOT NULL,
    singkatan VARCHAR(50),
    nama_kepala_divisi VARCHAR(100),
    deskripsi TEXT,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Perencanaan Penerimaan
CREATE TABLE perencanaan_penerimaan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tahun_anggaran YEAR NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    estimasi_nominal DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'approved', 'active') DEFAULT 'draft',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Tabel Penerimaan Aktual
CREATE TABLE penerimaan_aktual (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    perencanaan_id BIGINT UNSIGNED,
    tanggal_penerimaan DATE NOT NULL,
    nominal DECIMAL(15,2) NOT NULL,
    bukti_transaksi VARCHAR(255),
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (perencanaan_id) REFERENCES perencanaan_penerimaan(id)
);

-- Tabel Pagu Anggaran
CREATE TABLE pagu_anggaran (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tahun_anggaran YEAR NOT NULL,
    total_pagu DECIMAL(15,2) NOT NULL,
    status ENUM('active', 'inactive', 'closed') DEFAULT 'active',
    approved_by BIGINT UNSIGNED,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Tabel Alokasi Pagu Divisi
CREATE TABLE alokasi_pagu_divisi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pagu_id BIGINT UNSIGNED,
    divisi_id BIGINT UNSIGNED,
    nilai_pagu DECIMAL(15,2) NOT NULL,
    nilai_terpakai DECIMAL(15,2) DEFAULT 0,
    nilai_sisa DECIMAL(15,2) GENERATED ALWAYS AS (nilai_pagu - nilai_terpakai) STORED,
    status ENUM('active', 'frozen', 'closed') DEFAULT 'active',
    catatan TEXT,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pagu_id) REFERENCES pagu_anggaran(id),
    FOREIGN KEY (divisi_id) REFERENCES divisi(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_pagu_divisi (pagu_id, divisi_id)
);

-- Tabel Program Kerja
CREATE TABLE program_kerja (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    alokasi_pagu_divisi_id BIGINT UNSIGNED,
    kode_program VARCHAR(50) UNIQUE NOT NULL,
    nama_program VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    estimasi_biaya DECIMAL(15,2) NOT NULL,
    durasi_mulai DATE NOT NULL,
    durasi_selesai DATE NOT NULL,
    status ENUM('draft', 'proposed', 'approved', 'active', 'completed', 'cancelled') DEFAULT 'draft',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (alokasi_pagu_divisi_id) REFERENCES alokasi_pagu_divisi(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Tabel Sub Program
CREATE TABLE sub_program (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    program_id BIGINT UNSIGNED,
    nama_sub_program VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    estimasi_biaya DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'approved', 'active', 'completed') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES program_kerja(id)
);

-- Tabel Pengajuan Dana
CREATE TABLE pengajuan_dana (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_pengajuan VARCHAR(50) UNIQUE NOT NULL,
    program_id BIGINT UNSIGNED,
    jenis_pengajuan ENUM('kegiatan', 'pengadaan', 'pembayaran', 'honorarium', 'sewa', 'konsumsi', 'lainnya') NOT NULL,
    nominal_diajukan DECIMAL(15,2) NOT NULL,
    tanggal_pengajuan DATE NOT NULL,
    keperluan TEXT NOT NULL,
    penerima_manfaat_type ENUM('pengaju', 'pic_kegiatan', 'pegawai', 'vendor', 'non_pegawai', 'internal', 'external') NOT NULL,
    penerima_manfaat_id BIGINT UNSIGNED NULL COMMENT 'Reference ke users atau vendors atau penerima_manfaat_lainnya',
    penerima_manfaat_name VARCHAR(200) NULL,
    penerima_manfaat_detail JSON NULL COMMENT 'Detail tambahan seperti rekening, kontak, dll',
    status_pengajuan ENUM('draft', 'pending', 'approved', 'rejected', 'processed', 'completed') DEFAULT 'draft',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES program_kerja(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Tabel Detail Pengajuan
CREATE TABLE detail_pengajuan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id BIGINT UNSIGNED,
    sub_program_id BIGINT UNSIGNED,
    item_name VARCHAR(200) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    satuan VARCHAR(50),
    harga_satuan DECIMAL(15,2) NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_dana(id),
    FOREIGN KEY (sub_program_id) REFERENCES sub_program(id)
);

-- Tabel Approval
CREATE TABLE approval (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id BIGINT UNSIGNED,
    approver_id BIGINT UNSIGNED,
    level_approval INT NOT NULL,
    status ENUM('approved', 'rejected') NOT NULL,
    catatan TEXT,
    approved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_dana(id),
    FOREIGN KEY (approver_id) REFERENCES users(id)
);

-- Tabel Approval Config (Updated)
CREATE TABLE approval_config (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    minimal_nominal DECIMAL(15,2) NOT NULL,
    maximal_nominal DECIMAL(15,2),
    level_approval INT NOT NULL,
    approver_role_id BIGINT UNSIGNED,
    approver_divisi_id BIGINT UNSIGNED NULL, -- NULL untuk all divisi, specific untuk divisi tertentu
    is_required BOOLEAN DEFAULT 1, -- Apakah level ini wajib
    is_active BOOLEAN DEFAULT 1,
    urutan INT NOT NULL, -- Urutan approval
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (approver_role_id) REFERENCES roles(id),
    FOREIGN KEY (approver_divisi_id) REFERENCES divisi(id)
);

-- Tabel Pencairan Dana
CREATE TABLE pencairan_dana (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_pencairan VARCHAR(50) UNIQUE NOT NULL,
    pengajuan_id BIGINT UNSIGNED,
    nominal_dicairkan DECIMAL(15,2) NOT NULL,
    tanggal_pencairan DATE NOT NULL,
    metode_pencairan VARCHAR(50),
    bukti_pencairan VARCHAR(255),
    status_pencairan ENUM('pending', 'processed', 'completed', 'confirmed') DEFAULT 'pending',
    tanggal_konfirmasi DATE NULL,
    dikonfirmasi_oleh BIGINT UNSIGNED NULL COMMENT 'User yang konfirmasi penerimaan',
    catatan_pencairan TEXT NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_dana(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (dikonfirmasi_oleh) REFERENCES users(id)
);

-- Tabel LPJ (Laporan Pertanggungjawaban)
CREATE TABLE lpj (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_lpj VARCHAR(50) UNIQUE NOT NULL,
    pencairan_id BIGINT UNSIGNED,
    tanggal_lpj DATE NOT NULL,
    total_pengeluaran DECIMAL(15,2) NOT NULL,
    sisa_dana DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'submitted', 'reviewed', 'approved') DEFAULT 'draft',
    created_by BIGINT UNSIGNED,
    reviewed_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pencairan_id) REFERENCES pencairan_dana(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
);

-- Tabel Detail LPJ
CREATE TABLE detail_lpj (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lpj_id BIGINT UNSIGNED,
    item_pengeluaran VARCHAR(200) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    satuan VARCHAR(50),
    harga_satuan DECIMAL(15,2) NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    bukti_pengeluaran VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lpj_id) REFERENCES lpj(id)
);

-- Tabel Vendors (untuk penerima manfaat eksternal)
CREATE TABLE vendors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_vendor VARCHAR(50) UNIQUE NOT NULL,
    nama_vendor VARCHAR(200) NOT NULL,
    nama_kontak VARCHAR(100),
    email VARCHAR(100),
    telepon VARCHAR(20),
    alamat TEXT,
    nomor_npwp VARCHAR(30),
    nomor_rekening VARCHAR(50),
    nama_bank VARCHAR(50),
    kategori_vendor ENUM('barang', 'jasa', 'konsultan', 'lainnya') DEFAULT 'lainnya',
    status_vendor ENUM('active', 'inactive', 'blacklist') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Penerima Manfaat Lainnya (non-vendor, non-pegawai)
CREATE TABLE penerima_manfaat_lainnya (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_penerima VARCHAR(200) NOT NULL,
    jenis_identitas ENUM('ktp', 'sim', 'paspor', 'lainnya') DEFAULT 'ktp',
    nomor_identitas VARCHAR(50),
    email VARCHAR(100),
    telepon VARCHAR(20),
    alamat TEXT,
    nomor_rekening VARCHAR(50),
    nama_bank VARCHAR(50),
    kategori_penerima ENUM('speaker', 'peserta', 'mitra', 'donatur', 'lainnya') DEFAULT 'lainnya',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel PIC Kegiatan
CREATE TABLE pic_kegiatan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    kegiatan_id BIGINT UNSIGNED NULL COMMENT 'Reference ke program_kerja atau sub_program',
    nama_pic VARCHAR(200) NOT NULL,
    divisi_id BIGINT UNSIGNED,
    jabatan PIC VARCHAR(100),
    email VARCHAR(100),
    telepon VARCHAR(20),
    status_pic ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (divisi_id) REFERENCES divisi(id)
);

-- Tabel Notifications (untuk tracking notifikasi)
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(50) NOT NULL COMMENT 'pengajuan_baru, approval_required, approved, rejected, pencairan_siap',
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    data JSON NULL COMMENT 'Additional data like pengajuan_id, etc',
    is_read BOOLEAN DEFAULT FALSE,
    sent_via_email BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel Refund
CREATE TABLE refund (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_refund VARCHAR(50) UNIQUE NOT NULL,
    lpj_id BIGINT UNSIGNED,
    nominal_refund DECIMAL(15,2) NOT NULL,
    alasan TEXT,
    tanggal_refund DATE NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'processed') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lpj_id) REFERENCES lpj(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Tabel Audit Trail
CREATE TABLE audit_trails (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id BIGINT UNSIGNED NOT NULL,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## 4. Struktur Organisasi & Flow Process

### Role Definitions & Permissions:

#### 1. **Direktur Keuangan**
- **Perencanaan Penerimaan** ✅
  - Merencanakan sumber-sumber penerimaan tahunan
  - Estimasi nominal dan kategori penerimaan
- **Alokasi Pagu Anggaran** ✅
  - Menetapkan total pagu anggaran
  - Mengalokasikan pagu ke setiap divisi
  - Melakukan realokasi pagu antar divisi
- **Approval Khusus** ✅
  - Approval untuk alokasi > 1 Miliar
  - Final approval untuk semua transaksi besar

#### 2. **Kepala Divisi**
- **Perencanaan Program Kerja** ✅
  - Menyusun program kerja divisi
  - Budgeting estimasi biaya
- **Approval Pengajuan** ✅
  - Review & approve pengajuan dari divisi
  - Maximum approval limit: Rp 100 Juta
- **Monitoring** ✅
  - Lihat semua aktivitas divisi
  - Laporan penggunaan anggaran divisi

#### 3. **Staff Divisi (Diberi Izin)**
- **Pengajuan Dana** ✅
  - Membuat pengajuan dana untuk kebutuhan operasional
  - Maximum pengajuan: Rp 50 Juta
  - Wajib melalui approval Kepala Divisi
- **LPJ Penggunaan** ✅
  - Membuat laporan pertanggungjawaban
  - Upload bukti pengeluaran

#### 4. **Staff Keuangan/Kasir**
- **Pencatatan Penerimaan** ✅
  - Input penerimaan aktual
  - Upload bukti transaksi
- **Pencairan Dana** ✅
  - Proses pencairan setelah approval
  - Upload bukti transfer/pencairan
  - Generate bukti pencairan

### Complete Flow Process:

#### Fase 1: Perencanaan & Penetapan
1. **Direktur Keuangan** buat Perencanaan Penerimaan
   - Input semua sumber penerimaan yang diestimasi
   - Kategori: Donasi, Iuran, Usaha, Hibah, dll

2. **Direktur Keuangan** tetapkan Pagu Anggaran
   - Total pagu berdasarkan perencanaan penerimaan
   - Status: Draft → Approved (oleh Direktur)

3. **Direktur Keuangan** alokasikan Pagu per Divisi
   - Sesuai kebutuhan dan prioritas divisi
   - Track total alokasi tidak boleh melebihi pagu total

#### Fase 2: Perencanaan Program Divisi
4. **Kepala Divisi** buat Program Kerja
   - Berdasarkan pagu yang dialokasikan
   - Detail estimasi biaya dan timeline

#### Fase 3: Eksekusi & Transaksi
5. **Staff Divisi** ajukan Dana
   - Form pengajuan dengan detail kebutuhan
   - Route: Staff → Kepala Divisi (approval)

6. **Kepala Divisi** review & approve
   - Cek ketersediaan pagu
   - Catatan approval/rejection

7. **Staff Keuangan** proses Pencairan
   - Setelah approval selesai
   - Upload bukti transfer

#### Fase 4: Pelaporan
8. **Staff Divisi** buat LPJ
   - Upload bukti pengeluaran
   - Hitung sisa dana (jika ada)

9. **Staff Keuangan** proses Refund
   - Jika ada sisa dana
   - Return ke pagu divisi

### Permission Matrix:

| Feature | Direktur Keuangan | Kepala Divisi | Staff Divisi | Staff Keuangan |
|---------|------------------|---------------|--------------|----------------|
| **Perencanaan Penerimaan** | CRUD | View | - | View |
| **Pencatatan Penerimaan** | View | View | - | CRUD |
| **Pagu Anggaran** | CRUD | View Own | - | View |
| **Alokasi Pagu** | CRUD | View Own | - | View |
| **Program Kerja** | View | CRUD Own | View Own | View |
| **Pengajuan Dana** | View All | CRUD + Approve | CRUD | View |
| **Approval** | Final | Level 1 | - | - |
| **Pencairan Dana** | View | View | - | CRUD |
| **LPJ** | View All | View Own | CRUD Own | View |
| **Refund** | View | View | - | CRUD |
| **Reports** | All | Own | Own | View |

### Approval Flow Hierarchy:

```
Pengajuan Dana
    ↓
< Rp 10 Juta
    ↓
Kepala Divisi (Auto Approve jika < 1 Juta)

Rp 10 Juta - Rp 100 Juta
    ↓
Kepala Divisi (Manual Review)

> Rp 100 Juta
    ↓
Kepala Divisi → Direktur Keuangan (Final Approval)
```

## 5. Permission Middleware Implementation

### Create Role-based Middleware

```bash
php artisan make:middleware CheckRole
php artisan make:middleware CheckPermission
php artisan make:middleware DivisiAccess
```

### CheckRole Middleware (app/Http/Middleware/CheckRole.php)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has required role
        foreach ($roles as $role) {
            if ($user->role->name === $role) {
                return $next($request);
            }
        }

        // If user is director finance, allow all access
        if ($user->role->name === 'direktur_keuangan') {
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
```

### DivisiAccess Middleware (app/Http/Middleware/DivisiAccess.php)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DivisiAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Allow access to director finance and finance staff
        if ($user->hasRole(['direktur_keuangan', 'staff_keuangan'])) {
            return $next($request);
        }

        // For other roles, check divisi access
        $resourceDivisiId = $this->getResourceDivisiId($request);

        if ($resourceDivisiId && $user->divisi_id !== $resourceDivisiId) {
            abort(403, 'Anda tidak memiliki akses ke divisi ini');
        }

        return $next($request);
    }

    private function getResourceDivisiId($request)
    {
        // Get divisi_id from various resources
        if ($request->route('pengajuan')) {
            return $request->route('pengajuan')->program->alokasiPaguDivisi->divisi_id;
        }

        if ($request->route('program')) {
            return $request->route('program')->alokasiPaguDivisi->divisi_id;
        }

        if ($request->route('alokasi')) {
            return $request->route('alokasi')->divisi_id;
        }

        return $request->input('divisi_id');
    }
}
```

### Permission Service (app/Services/PermissionService.php)

```php
<?php

namespace App\Services;

use App\Models\User;

class PermissionService
{
    // Direktur Keuangan Permissions
    public static function canDirekturKeuangan($feature, $action = 'view')
    {
        $user = auth()->user();

        if (!$user || $user->role->name !== 'direktur_keuangan') {
            return false;
        }

        $permissions = [
            'perencanaan_penerimaan' => ['create', 'read', 'update', 'delete'],
            'pencatatan_penerimaan' => ['view'],
            'pagu_anggaran' => ['create', 'read', 'update', 'delete'],
            'alokasi_pagu' => ['create', 'read', 'update', 'delete', 'reallocate'],
            'program_kerja' => ['view'],
            'pengajuan_dana' => ['view_all'],
            'approval' => ['final'],
            'pencairan_dana' => ['view'],
            'lpj' => ['view_all'],
            'refund' => ['view'],
            'reports' => ['all']
        ];

        return in_array($action, $permissions[$feature] ?? []);
    }

    // Kepala Divisi Permissions
    public static function canKepalaDivisi($feature, $action = 'view', $resource = null)
    {
        $user = auth()->user();

        if (!$user || $user->role->name !== 'kepala_divisi') {
            return false;
        }

        $permissions = [
            'perencanaan_penerimaan' => ['view'],
            'pencatatan_penerimaan' => ['view'],
            'pagu_anggaran' => ['view_own'],
            'alokasi_pagu' => ['view_own'],
            'program_kerja' => ['create_own', 'read_own', 'update_own', 'delete_own'],
            'pengajuan_dana' => ['create_own', 'read_own', 'update_own', 'delete_own', 'approve'],
            'approval' => ['level_1'],
            'pencairan_dana' => ['view'],
            'lpj' => ['view_own', 'create_own', 'update_own'],
            'refund' => ['view'],
            'reports' => ['own']
        ];

        // Check ownership for 'own' actions
        if (in_array($action, ['create_own', 'read_own', 'update_own', 'delete_own', 'view_own'])) {
            if ($resource && isset($resource->divisi_id)) {
                return $resource->divisi_id === $user->divisi_id;
            }
        }

        return in_array(str_replace('_own', '', $action), $permissions[$feature] ?? []);
    }

    // Staff Divisi Permissions
    public static function canStaffDivisi($feature, $action = 'view', $resource = null)
    {
        $user = auth()->user();

        if (!$user || $user->role->name !== 'staff_divisi') {
            return false;
        }

        $permissions = [
            'pengajuan_dana' => ['create_own', 'read_own'],
            'lpj' => ['create_own', 'read_own', 'update_own'],
            'program_kerja' => ['view_own']
        ];

        // Check ownership for 'own' actions
        if ($resource && method_exists($resource, 'created_by')) {
            return $resource->created_by === $user->id && $resource->divisi_id === $user->divisi_id;
        }

        return in_array($action, $permissions[$feature] ?? []);
    }

    // Staff Keuangan Permissions
    public static function canStaffKeuangan($feature, $action = 'view')
    {
        $user = auth()->user();

        if (!$user || $user->role->name !== 'staff_keuangan') {
            return false;
        }

        $permissions = [
            'perencanaan_penerimaan' => ['view'],
            'pencatatan_penerimaan' => ['create', 'read', 'update', 'delete'],
            'pagu_anggaran' => ['view'],
            'alokasi_pagu' => ['view'],
            'pengajuan_dana' => ['view'],
            'pencairan_dana' => ['create', 'read', 'update'],
            'lpj' => ['view'],
            'refund' => ['create', 'read', 'update']
        ];

        return in_array($action, $permissions[$feature] ?? []);
    }
}
```

### Update User Model dengan Helper Methods

```php
// app/Models/User.php (tambahkan methods)
public function canAccess($feature, $action = 'view', $resource = null)
{
    switch($this->role->name) {
        case 'direktur_keuangan':
            return PermissionService::canDirekturKeuangan($feature, $action);
        case 'kepala_divisi':
            return PermissionService::canKepalaDivisi($feature, $action, $resource);
        case 'staff_divisi':
            return PermissionService::canStaffDivisi($feature, $action, $resource);
        case 'staff_keuangan':
            return PermissionService::canStaffKeuangan($feature, $action);
        default:
            return false;
    }
}

public function getMaximumApprovalLimit()
{
    switch($this->role->name) {
        case 'direktur_keuangan':
            return 999999999; // Unlimited
        case 'kepala_divisi':
            return 100000000; // 100 Juta
        default:
            return 0;
    }
}

public function getMaximumPengajuanLimit()
{
    switch($this->role->name) {
        case 'direktur_keuangan':
            return 999999999; // Unlimited
        case 'kepala_divisi':
            return 50000000; // 50 Juta
        case 'staff_divisi':
            return 50000000; // 50 Juta
        default:
            return 0;
    }
}
```

## 6. Laravel Routes with Permission Middleware

### Updated Routes (routes/web.php)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{...};

// Register middleware
Route::middleware(['role.check', 'auth'])->group(function () {

    // ===== DIREKTUR KEUANGAN ROUTES =====
    Route::middleware('role:direktur_keuangan')->prefix('finance-director')->name('finance-director.')->group(function () {
        // Perencanaan Penerimaan (only director)
        Route::resource('perencanaan-penerimaan', PerencanaanPenerimaanController::class);

        // Pagu Anggaran
        Route::resource('pagu-anggaran', PaguAnggaranController::class);
        Route::post('pagu-anggaran/{pagu}/approve', [PaguAnggaranController::class, 'approve'])->name('pagu.approve');

        // Alokasi Pagu
        Route::get('pagu-anggaran/{pagu}/alokasi', [AlokasiPaguController::class, 'index'])->name('alokasi.index');
        Route::post('pagu-anggaran/{pagu}/alokasi', [AlokasiPaguController::class, 'store'])->name('alokasi.store');
        Route::post('alokasi-pagu/{alokasi}/realokasi', [AlokasiPaguController::class, 'prosesRealokasi'])->name('alokasi.realokasi.proses');
    });

    // ===== KEPALA DIVISI ROUTES =====
    Route::middleware('role:kepala_divisi')->prefix('head-division')->name('head-division.')->group(function () {
        // Program Kerja (hanya divisi sendiri)
        Route::resource('program-kerja', ProgramKerjaController::class)
            ->middleware('divisi.access');

        // Approval Pengajuan (level 1)
        Route::get('approval/divisi', [ApprovalController::class, 'divisiIndex'])->name('approval.divisi');
        Route::post('approval/divisi/{pengajuan}', [ApprovalController::class, 'divisiApprove'])->name('approval.divisi.process');
    });

    // ===== STAFF DIVISI ROUTES =====
    Route::middleware('role:staff_divisi')->prefix('staff-division')->name('staff-division.')->group(function () {
        // Pengajuan Dana (hanya buat untuk divisi sendiri)
        Route::resource('pengajuan-dana', PengajuanDanaController::class)
            ->only(['create', 'store', 'index'])
            ->middleware('divisi.access');

        // LPJ (hanya untuk pengajuan sendiri)
        Route::resource('lpj', LpjController::class)
            ->only(['create', 'store', 'edit', 'update'])
            ->middleware('divisi.ownership');
    });

    // ===== STAFF KEUANGAN ROUTES =====
    Route::middleware('role:staff_keuangan')->prefix('finance-staff')->name('finance-staff.')->group(function () {
        // Pencatatan Penerimaan
        Route::resource('penerimaan-aktual', PenerimaanAktualController::class);

        // Pencairan Dana
        Route::get('pencairan-dana/approved', [PencairanDanaController::class, 'approvedList'])->name('pencairan.approved');
        Route::post('pencairan-dana/{pengajuan}/process', [PencairanDanaController::class, 'process'])->name('pencairan.process');

        // Refund
        Route::post('refund/{refund}/process', [RefundController::class, 'process'])->name('refund.process');
    });

    // ===== SHARED ROUTES (multiple roles) =====
    Route::middleware('role:direktur_keuangan,kepala_divisi,staff_divisi,staff_keuangan')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // View Only Resources
        Route::get('pengajuan-dana/{pengajuan}', [PengajuanDanaController::class, 'show'])->name('pengajuan-dana.show');
        Route::get('lpj/{lpj}', [LpjController::class, 'show'])->name('lpj.show');
        Route::get('pencairan-dana/{pencairan}', [PencairanDanaController::class, 'show'])->name('pencairan-dana.show');
    });
});
```

### Register Middleware (app/Http/Kernel.php)

```php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
    'signed' => \App\Http\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

    // Custom middleware
    'role.check' => \App\Http\Middleware\CheckRole::class,
    'divisi.access' => \App\Http\Middleware\DivisiAccess::class,
];
```

### Database Seeder untuk Roles & Divisi

```php
// database/seeders/RoleSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Divisi;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $roles = [
            ['name' => 'direktur_keuangan', 'description' => 'Direktur Keuangan - Akses penuh sistem'],
            ['name' => 'direktur_utama', 'description' => 'Direktur Utama - Final approval untuk transaksi besar'],
            ['name' => 'kepala_divisi', 'description' => 'Kepala Divisi - Manage program & approval level 1'],
            ['name' => 'manager', 'description' => 'Manager - Approval level 2'],
            ['name' => 'staff_divisi', 'description' => 'Staff Divisi - Pengajuan dana'],
            ['name' => 'staff_keuangan', 'description' => 'Staff Keuangan - Input penerimaan & pencairan'],
            ['name' => 'cto', 'description' => 'Chief Technology Officer - Approval khusus IT']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create Sample Divisi
        $divisis = [
            ['kode_divisi' => 'IT', 'nama_divisi' => 'Divisi Teknologi Informasi', 'singkatan' => 'IT'],
            ['kode_divisi' => 'HR', 'nama_divisi' => 'Divisi Human Resources', 'singkatan' => 'HR'],
            ['kode_divisi' => 'OPS', 'nama_divisi' => 'Divisi Operasional', 'singkatan' => 'OPS'],
            ['kode_divisi' => 'MKT', 'nama_divisi' => 'Divisi Marketing', 'singkatan' => 'MKT'],
            ['kode_divisi' => 'FIN', 'nama_divisi' => 'Divisi Keuangan', 'singkatan' => 'FIN']
        ];

        foreach ($divisis as $divisi) {
            Divisi::create($divisi);
        }

        // Create Sample Users
        $this->createSampleUsers();
    }

    private function createSampleUsers()
    {
        $users = [
            [
                'username' => 'director_finance',
                'email' => 'director@ebudget.com',
                'full_name' => 'Ahmad Wijaya',
                'password' => bcrypt('password'),
                'role_id' => 1, // direktur_keuangan
                'divisi_id' => 5 // FIN
            ],
            [
                'username' => 'head_it',
                'email' => 'headit@ebudget.com',
                'full_name' => 'Budi Santoso',
                'password' => bcrypt('password'),
                'role_id' => 2, // kepala_divisi
                'divisi_id' => 1 // IT
            ],
            [
                'username' => 'head_hr',
                'email' => 'headhr@ebudget.com',
                'full_name' => 'Citra Dewi',
                'password' => bcrypt('password'),
                'role_id' => 2, // kepala_divisi
                'divisi_id' => 2 // HR
            ],
            [
                'username' => 'staff_it',
                'email' => 'staffit@ebudget.com',
                'full_name' => 'Dodi Prasetyo',
                'password' => bcrypt('password'),
                'role_id' => 3, // staff_divisi
                'divisi_id' => 1 // IT
            ],
            [
                'username' => 'finance_staff',
                'email' => 'finance@ebudget.com',
                'full_name' => 'Eka Putri',
                'password' => bcrypt('password'),
                'role_id' => 4, // staff_keuangan
                'divisi_id' => 5 // FIN
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
```

## 7. Updated Controller Examples

### PerencanaanPenerimaanController (Direktur Keuangan Only)

```php
<?php

namespace App\Http\Controllers;

use App\Models\PerencanaanPenerimaan;
use App\Http\Requests\PerencanaanPenerimaanRequest;
use Illuminate\Http\Request;

class PerencanaanPenerimaanController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:direktur_keuangan')->except(['index', 'show']);
    }

    public function index()
    {
        // Only Direktur Keuangan can see all, others just view
        $user = auth()->user();

        if ($user->hasRole('direktur_keuangan')) {
            $perencanaan = PerencanaanPenerimaan::with('creator')
                ->orderBy('tahun_anggaran', 'desc')
                ->paginate(10);
        } else {
            $perencanaan = PerencanaanPenerimaan::with('creator')
                ->where('status', 'approved')
                ->orderBy('tahun_anggaran', 'desc')
                ->paginate(10);
        }

        return view('pages.perencanaan-penerimaan.index', compact('perencanaan'));
    }

    public function create()
    {
        return view('pages.perencanaan-penerimaan.create');
    }

    public function store(PerencanaanPenerimaanRequest $request)
    {
        $user = auth()->user();

        if (!$user->canAccess('perencanaan_penerimaan', 'create')) {
            abort(403);
        }

        PerencanaanPenerimaan::create([
            'tahun_anggaran' => $request->tahun_anggaran,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'estimasi_nominal' => $request->estimasi_nominal,
            'status' => 'draft',
            'created_by' => $user->id
        ]);

        return redirect()
            ->route('perencanaan-penerimaan.index')
            ->with('success', 'Perencanaan penerimaan berhasil dibuat');
    }
}
```

### PengajuanDanaController (Multi-Role with Permissions)

```php
<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDana;
use App\Models\SubProgram;
use App\Services\ApprovalService;
use App\Services\NumberingService;
use Illuminate\Http\Request;

class PengajuanDanaController extends Controller
{
    public function __construct()
    {
        // All authenticated users can view, but with different data scope
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Different query based on role
        $query = PengajuanDana::with([
            'subProgram.program.alokasiPaguDivisi.divisi',
            'creator',
            'approvals.approver'
        ]);

        // Direktur Keuangan: See all
        if ($user->hasRole('direktur_keuangan')) {
            // No filtering
        }
        // Kepala Divisi: See all from their division
        elseif ($user->hasRole('kepala_divisi')) {
            $query->whereHas('subProgram.program.alokasiPaguDivisi.divisi', function($q) use ($user) {
                $q->where('id', $user->divisi_id);
            });
        }
        // Staff Divisi: See only their own
        elseif ($user->hasRole('staff_divisi')) {
            $query->where('created_by', $user->id);
        }
        // Staff Keuangan: See all approved for processing
        elseif ($user->hasRole('staff_keuangan')) {
            $query->where('status_pengajuan', 'approved');
        }

        // Apply filters
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('kode_pengajuan', 'like', "%{$request->search}%")
                  ->orWhere('keperluan', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status_pengajuan', $request->status);
        }

        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.pengajuan-dana.index', compact('pengajuan'));
    }

    public function create()
    {
        $user = auth()->user();

        // Check permission
        if (!$user->canAccess('pengajuan_dana', 'create')) {
            abort(403, 'Anda tidak memiliki izin untuk membuat pengajuan');
        }

        // Get available sub programs from user's division
        $subPrograms = SubProgram::with('program.alokasiPaguDivisi.divisi')
            ->whereHas('program.alokasiPaguDivisi.divisi', function($q) use ($user) {
                $q->where('id', $user->divisi_id);
            })
            ->get();

        return view('pages.pengajuan-dana.create', compact('subPrograms'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate user permission
        if (!$user->canAccess('pengajuan_dana', 'create')) {
            abort(403);
        }

        // Check pengajuan limit
        $request->validate([
            'id_program' => 'required|exists:program,id',
            'jenis_pengajuan' => 'required|in:kegiatan,pengadaan,pembayaran,honorarium,sewa,konsumsi,lainnya',
            'keperluan' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'penerima_manfaat_type' => 'required|in:pengaju,pic_kegiatan,pegawai,vendor,non_pegawai,internal,external',
            'penerima_manfaat_id' => 'nullable|required_unless:penerima_manfaat_type,pengaju|integer',
            'penerima_manfaat_name' => 'nullable|required_if:penerima_manfaat_type,internal,external|string|max:200',
            'details' => 'required|array|min:1',
            'details.*.sub_program_id' => 'required|exists:sub_program,id',
            'details.*.item_name' => 'required|string',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0'
        ]);

        // Calculate total
        $totalNominal = collect($request->details)->sum(function($detail) {
            return $detail['quantity'] * $detail['harga_satuan'];
        });

        // Check against user limit
        if ($totalNominal > $user->getMaximumPengajuanLimit()) {
            return back()
                ->withErrors(['error' => 'Pengajuan melebihi batas maksimal (Rp ' .
                    number_format($user->getMaximumPengajuanLimit(), 0, ',', '.') . ')'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Prepare penerima manfaat data
        $penerimaManfaatService = new PenerimaManfaatService();
        $penerimaData = $penerimaManfaatService->prepareData(
            $request->penerima_manfaat_type,
            $request->only(['penerima_manfaat_id', 'penerima_manfaat_name', 'penerima_manfaat_detail']),
            $user->id
        );

        $pengajuan = PengajuanDana::create([
                'kode_pengajuan' => NumberingService::generateKodePengajuan(),
                'id_program' => $request->id_program,
                'jenis_pengajuan' => $request->jenis_pengajuan,
                'nominal_diajukan' => $totalNominal,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'keperluan' => $request->keperluan,
                'penerima_manfaat_type' => $penerimaData['penerima_manfaat_type'],
                'penerima_manfaat_id' => $penerimaData['penerima_manfaat_id'],
                'penerima_manfaat_name' => $penerimaData['penerima_manfaat_name'],
                'penerima_manfaat_detail' => $penerimaData['penerima_manfaat_detail'],
                'status_pengajuan' => 'pending',
                'created_by' => $user->id
            ]);

            // Insert details
            foreach ($request->details as $detail) {
                DetailPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'sub_program_id' => $detail['sub_program_id'],
                    'item_name' => $detail['item_name'],
                    'quantity' => $detail['quantity'],
                    'satuan' => $detail['satuan'] ?? '',
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['quantity'] * $detail['harga_satuan']
                ]);
            }

            // Auto-approval for amounts < 1 Juta
            $approvalService = new ApprovalService();
            $approvalService->processAutoApproval($pengajuan);

            DB::commit();

            // Send notification untuk approval process
            $notificationService = new EmailNotificationService();
            if ($pengajuan->status_pengajuan !== 'approved') {
                // Jika tidak auto-approve, kirim notifikasi ke approvers
                $notificationService->sendApprovalNotification($pengajuan);
            } else {
                // Jika auto-approve, kirim notifikasi ke staff keuangan
                $notificationService->sendAllApprovalsCompletedNotification($pengajuan);
            }

            return redirect()
                ->route('pengajuan-dana.show', $pengajuan)
                ->with('success', 'Pengajuan dana berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
```

### ApprovalService (app/Services/ApprovalService.php)

```php
<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\Approval;
use App\Models\User;

class ApprovalService
{
    public function processAutoApproval(PengajuanDana $pengajuan)
    {
        // Auto-approve if < 1 Juta
        if ($pengajuan->nominal_diajukan < 1000000) {
            $kepalaDivisi = $this->getKepalaDivisi($pengajuan);

            if ($kepalaDivisi) {
                Approval::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approver_id' => $kepalaDivisi->id,
                    'level_approval' => 1,
                    'status' => 'approved',
                    'catatan' => 'Auto-approved (amount < 1 Juta)',
                    'approved_at' => now()
                ]);

                $pengajuan->update(['status_pengajuan' => 'approved']);
            }
        }
        // If > 100 Juta, need Director approval
        elseif ($pengajuan->nominal_diajukan > 100000000) {
            $pengajuan->update(['status_pengajuan' => 'pending_director']);
        }
        // Otherwise, regular approval flow
        else {
            // stays pending for Kepala Divisi
        }
    }

    private function getKepalaDivisi(PengajuanDana $pengajuan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;

        return User::where('role_id', 2) // kepala_divisi
            ->where('divisi_id', $divisiId)
            ->where('is_active', true)
            ->first();
    }

    public function canApprove(User $user, PengajuanDana $pengajuan)
    {
        // Check if user is from same division
        if ($user->hasRole('kepala_divisi')) {
            $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
            return $user->divisi_id === $divisiId &&
                   $pengajuan->nominal_diajukan <= $user->getMaximumApprovalLimit();
        }

        // Director can approve anything
        return $user->hasRole('direktur_keuangan');
    }

    public function processApproval(User $user, PengajuanDana $pengajuan, $status, $catatan = null)
    {
        if (!$this->canApprove($user, $pengajuan)) {
            throw new \Exception('Anda tidak memiliki otoritas untuk menyetujui pengajuan ini');
        }

        $approvalLevel = $user->hasRole('direktur_keuangan') ? 2 : 1;

        Approval::updateOrCreate(
            [
                'pengajuan_id' => $pengajuan->id,
                'approver_id' => $user->id,
                'level_approval' => $approvalLevel
            ],
            [
                'status' => $status,
                'catatan' => $catatan,
                'approved_at' => now()
            ]
        );

        // Update pengajuan status
        if ($status === 'rejected') {
            $pengajuan->update(['status_pengajuan' => 'rejected']);
        } else {
            // Check if all required approvals completed
            $this->updateFinalStatus($pengajuan);
        }
    }

    private function updateFinalStatus(PengajuanDana $pengajuan)
    {
        if ($pengajuan->nominal_diajuan <= 100000000) {
            // Only need Kepala Divisi approval
            $pengajuan->update(['status_pengajuan' => 'approved']);
        } else {
            // Need both Kepala Divisi and Director approval
            $hasKepalaApproval = $pengajuan->approvals()
                ->where('level_approval', 1)
                ->where('status', 'approved')
                ->exists();

            $hasDirectorApproval = $pengajuan->approvals()
                ->where('level_approval', 2)
                ->where('status', 'approved')
                ->exists();

            if ($hasKepalaApproval && $hasDirectorApproval) {
                $pengajuan->update(['status_pengajuan' => 'approved']);
            }
        }
    }
}
```

### PenerimaanAktualController (Staff Keuangan Only)

```php
<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanAktual;
use App\Models\PerencanaanPenerimaan;
use Illuminate\Http\Request;

class PenerimaanAktualController extends Controller
{
    public function __construct()
    {
        // Only Staff Keuangan can create/update
        $this->middleware('role:staff_keuangan')->except(['index', 'show']);
    }

    public function index()
    {
        $user = auth()->user();

        $query = PenerimaanAktual::with('perencanaan');

        // Staff Keuangan: See all
        if ($user->hasRole('staff_keuangan')) {
            // No filtering
        }
        // Others: Only view
        else {
            $query->latest()->limit(50); // Limited view
        }

        $penerimaan = $query->orderBy('tanggal_penerimaan', 'desc')->paginate(20);

        return view('pages.penerimaan-aktual.index', compact('penerimaan'));
    }

    public function create()
    {
        $perencanaan = PerencanaanPenerimaan::where('status', 'approved')
            ->orderBy('tahun_anggaran', 'desc')
            ->get();

        return view('pages.penerimaan-aktual.create', compact('perencanaan'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->canAccess('pencatatan_penerimaan', 'create')) {
            abort(403);
        }

        $request->validate([
            'perencanaan_id' => 'required|exists:perencanaan_penerimaan,id',
            'tanggal_penerimaan' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string'
        ]);

        PenerimaanAktual::create([
            'perencanaan_id' => $request->perencanaan_id,
            'tanggal_penerimaan' => $request->tanggal_penerimaan,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('penerimaan-aktual.index')
            ->with('success', 'Penerimaan berhasil dicatat');
    }
}
```

    public function updatePenggunaan($alokasiPaguDivisiId) {
        $alokasi = AlokasiPaguDivisi::find($alokasiPaguDivisiId);

        $totalTerpakai = $alokasi->programKerja()
            ->where('status', 'completed')
            ->sum('estimasi_biaya');

        $alokasi->update(['nilai_terpakai' => $totalTerpakai]);
    }
}
```

### Contoh Query untuk Dashboard Divisi:

```sql
-- View untuk monitoring pagu per divisi
CREATE VIEW v_pagu_divisi_monitoring AS
SELECT
    d.nama_divisi,
    d.kode_divisi,
    pa.tahun_anggaran,
    apd.nilai_pagu,
    apd.nilai_terpakai,
    apd.nilai_sisa,
    (apd.nilai_terpakai / apd.nilai_pagu * 100) as persentase_terpakai,
    COUNT(pk.id) as jumlah_program,
    SUM(CASE WHEN pk.status = 'completed' THEN pk.estimasi_biaya ELSE 0 END) as realisasi_program
FROM alokasi_pagu_divisi apd
JOIN pagu_anggaran pa ON apd.pagu_id = pa.id
JOIN divisi d ON apd.divisi_id = d.id
LEFT JOIN program_kerja pk ON apd.id = pk.alokasi_pagu_divisi_id
WHERE pa.status = 'active'
GROUP BY d.id, pa.id, apd.id;
```

## 5. Laravel Routes

### Routes (routes/web.php)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerencanaanPenerimaanController;
use App\Http\Controllers\PenerimaanAktualController;
use App\Http\Controllers\PaguAnggaranController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\PengajuanDanaController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\PencairanDanaController;
use App\Http\Controllers\LpjController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\AlokasiPaguController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Divisi
    Route::resource('divisi', DivisiController::class);

    // Perencanaan Penerimaan
    Route::resource('perencanaan-penerimaan', PerencanaanPenerimaanController::class);

    // Pagu Anggaran & Alokasi
    Route::resource('pagu-anggaran', PaguAnggaranController::class);
    Route::get('pagu-anggaran/{pagu}/alokasi', [AlokasiPaguController::class, 'index'])->name('alokasi.index');
    Route::post('pagu-anggaran/{pagu}/alokasi', [AlokasiPaguController::class, 'store'])->name('alokasi.store');
    Route::get('alokasi-pagu/{alokasi}/edit', [AlokasiPaguController::class, 'edit'])->name('alokasi.edit');
    Route::put('alokasi-pagu/{alokasi}', [AlokasiPaguController::class, 'update'])->name('alokasi.update');
    Route::delete('alokasi-pagu/{alokasi}', [AlokasiPaguController::class, 'destroy'])->name('alokasi.destroy');
    Route::get('alokasi-pagu/{alokasi}/realokasi', [AlokasiPaguController::class, 'realokasi'])->name('alokasi.realokasi');

    // Penerimaan Aktual
    Route::resource('penerimaan-aktual', PenerimaanAktualController::class);

    // Pagu Anggaran
    Route::resource('pagu-anggaran', PaguAnggaranController::class);

    // Program Kerja
    Route::resource('program-kerja', ProgramKerjaController::class);
    Route::get('program-kerja/by-alokasi/{alokasi}', [ProgramKerjaController::class, 'byAlokasi']);
    Route::get('program-kerja/{program}/sub-program', [ProgramKerjaController::class, 'subProgram']);
    Route::post('program-kerja/{program}/sub-program', [ProgramKerjaController::class, 'storeSubProgram']);

    // Pengajuan Dana
    Route::resource('pengajuan-dana', PengajuanDanaController::class);
    Route::get('pengajuan-dana/{pengajuan}/detail', [PengajuanDanaController::class, 'detail']);

    // Approval
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::post('/{pengajuan}', [ApprovalController::class, 'process'])->name('process');
    });

    // Pencairan Dana
    Route::resource('pencairan-dana', PencairanDanaController::class);

    // Flow Khusus Pembayaran
    Route::post('pencairan-dana/{pencairan}/konfirmasi-penerimaan', [PencairanDanaController::class, 'konfirmasiPenerimaan'])->name('pencairan-dana.konfirmasi-penerimaan');
    Route::post('pencairan-dana/{pencairan}/verifikasi-pembayaran', [PencairanDanaController::class, 'verifikasiPembayaran'])->name('pencairan-dana.verifikasi-pembayaran');

    // LPJ
    Route::resource('lpj', LpjController::class);
    Route::get('lpj/{lpj}/detail', [LpjController::class, 'detail']);
    Route::post('lpj/{lpj}/submit', [LpjController::class, 'submit'])->name('lpj.submit');

    // Refund
    Route::resource('refund', RefundController::class);
    Route::post('refund/{refund}/approve', [RefundController::class, 'approve'])->name('refund.approve');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/anggaran', [ReportController::class, 'anggaran'])->name('anggaran');
        Route::get('/penggunaan-dana', [ReportController::class, 'penggunaanDana'])->name('penggunaan-dana');
        Route::get('/saldo', [ReportController::class, 'saldo'])->name('saldo');
        Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
    });

    // AJAX Routes
    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::get('/get-programs/{pagu}', [ProgramKerjaController::class, 'getPrograms']);
        Route::get('/get-sub-programs/{program}', [ProgramKerjaController::class, 'getSubPrograms']);
        Route::get('/get-pengajuan-detail/{id}', [PengajuanDanaController::class, 'ajaxDetail']);
        Route::post('/upload-document', [PengajuanDanaController::class, 'uploadDocument']);

        // Penerima Manfaat Routes
        Route::get('/penerima-manfaat-options/{jenisPengajuan}', [PengajuanDanaController::class, 'getPenerimaManfaatOptions']);
        Route::get('/penerima-manfaat-list/{type}', [PengajuanDanaController::class, 'getPenerimaManfaatList']);
        Route::get('/pegawai-rekening/{userId}', [PengajuanDanaController::class, 'getPegawaiRekening']);
    });
});
```

### API Routes for AJAX (routes/api.php)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PerencanaanPenerimaanAPIController;
use App\Http\Controllers\API\PengajuanDanaAPIController;
use App\Http\Controllers\API\ApprovalAPIController;

Route::middleware('auth:sanctum')->group(function () {
    // Perencanaan Penerimaan API
    Route::apiResource('perencanaan-penerimaan', PerencanaanPenerimaanAPIController::class);

    // Pengajuan Dana API
    Route::apiResource('pengajuan-dana', PengajuanDanaAPIController::class);

    // Approval API
    Route::post('approval/{pengajuan}', [ApprovalAPIController::class, 'process']);

    // Reports API
    Route::get('reports/dashboard-stats', [DashboardController::class, 'stats']);
});
```

## 5. Alpine.js Components Structure

### Main App JS (resources/js/app.js)

```javascript
import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'

// Components
import './components/modal.js'
import './components/dropdown.js'
import './components/datatable.js'
import './components/form-pengajuan.js'
import './components/form-lpj.js'
import './components/approval-form.js'
import './components/date-picker.js'
import './components/file-upload.js'
import './components/notification.js'

// Alpine.js plugins
Alpine.plugin(persist)

// Global data
Alpine.data('app', () => ({
    user: null,
    notifications: [],

    init() {
        // Initialize user data
        this.getUser()
        // Check for notifications
        this.checkNotifications()
    },

    getUser() {
        // Fetch user from API
    },

    checkNotifications() {
        // Fetch notifications
    },

    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(value)
    },

    formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID')
    }
}))

// Start Alpine
window.Alpine = Alpine
Alpine.start()
```

### Modal Component (resources/js/components/modal.js)

```javascript
export default () => ({
    show: false,
    title: '',
    content: '',

    open(title, content) {
        this.title = title
        this.content = content
        this.show = true
    },

    close() {
        this.show = false
    }
})
```

### Alokasi Pagu Component (resources/js/components/alokasi-pagu.js)

```javascript
export default () => ({
    form: {
        divisi_id: '',
        nilai_pagu: 0,
        catatan: ''
    },

    divisiOptions: [],
    totalPagu: 0,
    totalAlokasi: 0,
    sisaPagu: 0,
    alokasiList: [],

    init() {
        this.totalPagu = parseFloat(this.$el.dataset.totalPagu)
        this.totalAlokasi = parseFloat(this.$el.dataset.totalAlokasi)
        this.sisaPagu = this.totalPagu - this.totalAlokasi
        this.loadDivisi()
    },

    loadDivisi() {
        fetch('/api/divisi/active')
            .then(response => response.json())
            .then(data => {
                this.divisiOptions = data
            })
    },

    calculatePagu() {
        const formattedValue = this.formatCurrency(this.form.nilai_pagu)
        return formattedValue
    },

    checkPaguLimit() {
        if (this.form.nilai_pagu > this.sisaPagu) {
            alert('Nilai pagu melebihi sisa pagu yang tersedia!')
            this.form.nilai_pagu = this.sisaPagu
        }
    },

    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID').format(value)
    },

    submit() {
        this.checkPaguLimit()

        fetch(this.$el.dataset.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(this.form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload()
            } else {
                alert(data.message || 'Terjadi kesalahan')
            }
        })
    }
})
```

### Program Kerja per Divisi Component (resources/js/components/program-divisi.js)

```javascript
export default () => ({
    selectedAlokasi: null,
    programs: [],
    totalEstimasi: 0,
    sisaPagu: 0,
    showAddForm: false,

    init() {
        // Load initial data if alokasi_id provided
        const alokasiId = this.$el.dataset.alokasiId
        if (alokasiId) {
            this.loadPrograms(alokasiId)
        }
    },

    loadPrograms(alokasiId) {
        fetch(`/ajax/program-kerja/by-alokasi/${alokasiId}`)
            .then(response => response.json())
            .then(data => {
                this.programs = data.programs
                this.totalEstimasi = data.total_estimasi
                this.sisaPagu = data.sisa_pagu
            })
    },

    getPagupercentase() {
        if (!this.selectedAlokasi) return 0
        return Math.round((this.totalEstimasi / this.selectedAlokasi.nilai_pagu) * 100)
    },

    getProgressColor() {
        const percent = this.getPagupercentase()
        if (percent >= 100) return 'bg-red-500'
        if (percent >= 80) return 'bg-yellow-500'
        if (percent >= 60) return 'bg-blue-500'
        return 'bg-green-500'
    },

    formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(value)
    }
})
```

### Form Pengajuan Component (resources/js/components/form-pengajuan.js)

```javascript
export default () => ({
    form: {
        id_program: '',
        jenis_pengajuan: '',
        nominal_diajukan: 0,
        tanggal_pengajuan: '',
        keperluan: '',
        penerima_manfaat_type: 'pengaju',
        penerima_manfaat_id: null,
        penerima_manfaat_name: '',
        penerima_manfaat_detail: null,
        details: []
    },

    programs: [],
    subPrograms: [],
    penerimaManfaatOptions: [],
    availablePenerimaManfaat: [],
    totalNominal: 0,

    init() {
        this.loadPrograms()
    },

    loadPrograms() {
        fetch('/ajax/get-programs')
            .then(response => response.json())
            .then(data => {
                this.programs = data
            })
    },

    loadSubPrograms(programId) {
        fetch(`/ajax/get-sub-programs/${programId}`)
            .then(response => response.json())
            .then(data => {
                this.subPrograms = data
            })
    },

    async onJenisPengajuanChange() {
        // Load available penerima manfaat options based on jenis pengajuan
        try {
            const response = await fetch(`/ajax/penerima-manfaat-options/${this.form.jenis_pengajuan}`)
            const data = await response.json()
            this.penerimaManfaatOptions = data.options

            // Reset penerima manfaat selection
            this.form.penerima_manfaat_type = data.options[0]?.type || 'pengaju'
            this.form.penerima_manfaat_id = null
            this.form.penerima_manfaat_name = ''

            // Load available penerima manfaat list
            await this.loadPenerimaManfaatList()
        } catch (error) {
            console.error('Error loading penerima manfaat options:', error)
        }
    },

    async onPenerimaManfaatTypeChange() {
        await this.loadPenerimaManfaatList()
        // Reset selection
        this.form.penerima_manfaat_id = null
        this.form.penerima_manfaat_name = ''
        this.form.penerima_manfaat_detail = null
    },

    async loadPenerimaManfaatList() {
        if (!this.form.penerima_manfaat_type || this.form.penerima_manfaat_type === 'pengaju') {
            this.availablePenerimaManfaat = []
            return
        }

        try {
            const response = await fetch(`/ajax/penerima-manfaat-list/${this.form.penerima_manfaat_type}`)
            const data = await response.json()
            this.availablePenerimaManfaat = data
        } catch (error) {
            console.error('Error loading penerima manfaat list:', error)
            this.availablePenerimaManfaat = []
        }
    },

    selectPenerimaManfaat(penerima) {
        this.form.penerima_manfaat_id = penerima.id
        this.form.penerima_manfaat_name = penerima.name

        // If pegawai, need to load rekening info
        if (this.form.penerima_manfaat_type === 'pegawai') {
            this.loadPegawaiRekening(penerima.id)
        }
    },

    loadPegawaiRekening(userId) {
        fetch(`/ajax/pegawai-rekening/${userId}`)
            .then(response => response.json())
            .then(data => {
                this.form.penerima_manfaat_detail = {
                    rekening: data.rekening || null
                }
            })
            .catch(error => {
                console.error('Error loading pegawai rekening:', error)
            })
    },

    updatePegawaiRekening(field, value) {
        if (!this.form.penerima_manfaat_detail) {
            this.form.penerima_manfaat_detail = { rekening: {} }
        }
        if (!this.form.penerima_manfaat_detail.rekening) {
            this.form.penerima_manfaat_detail.rekening = {}
        }
        this.form.penerima_manfaat_detail.rekening[field] = value
    },

    addDetail() {
        this.form.details.push({
            sub_program_id: '',
            item_name: '',
            quantity: 1,
            satuan: '',
            harga_satuan: 0,
            total_harga: 0
        })
    },

    removeDetail(index) {
        this.form.details.splice(index, 1)
        this.calculateTotal()
    },

    calculateDetailTotal(index) {
        const detail = this.form.details[index]
        detail.total_harga = detail.quantity * detail.harga_satuan
        this.calculateTotal()
    },

    calculateTotal() {
        this.totalNominal = this.form.details.reduce((sum, detail) => {
            return sum + parseFloat(detail.total_harga || 0)
        }, 0)

        this.form.nominal_diajukan = this.totalNominal
    },

    submit() {
        fetch('/pengajuan-dana', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(this.form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/pengajuan-dana'
            } else {
                alert(data.message)
            }
        })
    }
})
```

### Datatable Component (resources/js/components/datatable.js)

```javascript
export default () => ({
    data: [],
    search: '',
    sortColumn: 'created_at',
    sortDirection: 'desc',
    perPage: 10,
    currentPage: 1,

    init() {
        this.loadData()
    },

    loadData() {
        const params = new URLSearchParams({
            search: this.search,
            sort: this.sortColumn,
            direction: this.sortDirection,
            per_page: this.perPage,
            page: this.currentPage
        })

        fetch(`${this.url}?${params}`)
            .then(response => response.json())
            .then(data => {
                this.data = data.data
            })
    },

    sort(column) {
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc'
        } else {
            this.sortColumn = column
            this.sortDirection = 'asc'
        }
        this.loadData()
    },

    deleteItem(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            fetch(`${this.url}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.loadData()
                }
            })
        }
    }
})
```

## 6. Blade Components

### Layout Component (resources/views/layouts/app.blade.php)

```php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Budget') - Aplikasi Budgeting</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- TailwindCSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body class="font-sans antialiased" x-data="app">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        @include('components.navigation')

        <!-- Main Content -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>

    <!-- Alpine.js -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
```

### Card Component (resources/views/components/card.blade.php)

```php
@props(['title', 'actions'])
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    @if($title)
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $title }}</h3>
            @if($actions)
                <div>{{ $actions }}</div>
            @endif
        </div>
    @endif

    <div class="{{ $title ? 'px-4 py-5 sm:p-6' : 'p-6' }}">
        {{ $slot }}
    </div>
</div>
```

### Alokasi Pagu View Example (resources/views/pages/alokasi-pagu/index.blade.php)

```php
@extends('layouts.app')

@section('title', 'Alokasi Pagu Divisi')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Alokasi Pagu Tahun {{ $pagu->tahun_anggaran }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Total Pagu: <span class="font-semibold">{{ formatCurrency($pagu->total_pagu) }}</span>
            </p>
        </div>
        <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ formatCurrency($totalAlokasi) }}</div>
                    <div class="text-sm text-gray-500">Total Teralokasi</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ formatCurrency($sisaPagu) }}</div>
                    <div class="text-sm text-gray-500">Sisa Pagu</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $alokasi->count() }}</div>
                    <div class="text-sm text-gray-500">Divisi Teralokasi</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alokasi List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($alokasi as $item)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $item->divisi->nama_divisi }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->divisi->kode_divisi }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $item->status_badge }}
                            <div class="relative">
                                <button class="text-gray-400 hover:text-gray-600" @click="showMenu{{ $item->id }} = !showMenu{{ $item->id }}">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div x-show="showMenu{{ $item->id }}" @click.away="showMenu{{ $item->id }} = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                    <a href="{{ route('alokasi.edit', $item) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                    <a href="{{ route('alokasi.realokasi', $item) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Realokasi</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress Penggunaan</span>
                            <span>{{ round(($item->nilai_terpakai / $item->nilai_pagu) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $item->pagu_warning_level == 'danger' ? 'bg-red-500' : ($item->pagu_warning_level == 'warning' ? 'bg-yellow-500' : 'bg-green-500') }}"
                                 style="width: {{ min(($item->nilai_terpakai / $item->nilai_pagu) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Amount Details -->
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <div class="text-gray-500">Total Pagu</div>
                            <div class="font-semibold">{{ $item->formatted_nilai_pagu }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Terpakai</div>
                            <div class="font-semibold text-red-600">{{ $item->formatted_nilai_terpakai }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Sisa</div>
                            <div class="font-semibold text-green-600">{{ $item->formatted_nilai_sisa }}</div>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            {{ $item->program_kerja_count }} Program Kerja
                        </div>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                           x-data
                           @click="loadPrograms({{ $item->id }})">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Alokasi Button (if ada sisa pagu) -->
    @if($sisaPagu > 0 && $divisiBelumAlokasi->count() > 0)
        <div class="mt-6">
            <button @click="showAddForm = true" class="btn-primary">
                <svg class="h-5 w-5 mr-2 -ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Alokasi
            </button>
        </div>
    @endif

    <!-- Add Alokasi Form -->
    <div x-show="showAddForm"
         x-data="alokasiPagu"
         data-total-pagu="{{ $pagu->total_pagu }}"
         data-total-alokasi="{{ $totalAlokasi }}"
         data-url="{{ route('alokasi.store', $pagu) }}"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         @click.self="showAddForm = false">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Alokasi Pagu</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Divisi</label>
                        <select x-model="form.divisi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Divisi</option>
                            @foreach($divisiBelumAlokasi as $divisi)
                                <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nilai Pagu</label>
                        <input type="number"
                               x-model.number="form.nilai_pagu"
                               @change="checkPaguLimit()"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                               placeholder="0">
                        <p class="text-sm text-gray-500 mt-1">
                            Sisa pagu tersedia: {{ formatCurrency($sisaPagu) }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea x-model="form.catatan" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showAddForm = false" class="btn-secondary">
                        Batal
                    </button>
                    <button @click="submit()" class="btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(value)
}

function loadPrograms(alokasiId) {
    // Load and show programs for selected alokasi
    fetch(`/ajax/program-kerja/by-alokasi/${alokasiId}`)
        .then(response => response.json())
        .then(data => {
            // Show modal or expand section with program details
            console.log(data)
        })
}
</script>
@endpush
@endsection
```

### Dashboard per Divisi Component (resources/views/pages/dashboard/index.blade.php)

```php
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- User Info & Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- User Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">{{ substr(auth()->user()->full_name, 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->full_name }}</h3>
                        <p class="text-sm text-gray-500">{{ auth()->user()->role->name }} @ {{ auth()->user()->divisi?->nama_divisi }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pagu Divisi -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pagu Divisi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ formatCurrency($totalPaguDivisi) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penggunaan Bulan Ini -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Penggunaan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ formatCurrency($penggunaanBulanIni) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagu Overview per Divisi (untuk admin/finance) -->
    @if(auth()->user()->hasRole(['admin', 'finance']))
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Overview Pagu per Divisi</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Tahun Anggaran {{ date('Y') }}</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="divide-y divide-gray-200">
                @foreach($alokasiPaguDivisi as $alokasi)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <div class="font-medium text-gray-900">{{ $alokasi->divisi->nama_divisi }}</div>
                                <div class="ml-2">
                                    {{ $alokasi->status_badge }}
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>{{ round(($alokasi->nilai_terpakai / $alokasi->nilai_pagu) * 100, 1)}% Terpakai</span>
                                    <span>{{ $alokasi->formatted_nilai_sisa }} Tersisa</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-300 {{ $alokasi->pagu_warning_level == 'danger' ? 'bg-red-500' : ($alokasi->pagu_warning_level == 'warning' ? 'bg-yellow-500' : 'bg-green-500') }}"
                                         style="width: {{ min(($alokasi->nilai_terpakai / $alokasi->nilai_pagu) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-6 text-right">
                            <div class="text-lg font-semibold text-gray-900">{{ $alokasi->formatted_nilai_pagu }}</div>
                            <div class="text-sm text-gray-500">{{ $alokasi->program_kerja_count }} Program</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Program Kerja Divisi Saya -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Program Kerja Divisi</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($programKerja as $program)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $program->nama_program }}</div>
                                <div class="text-sm text-gray-500">{{ $program->durasi_mulai }} - {{ $program->durasi_selesai }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $program->formatted_estimasi_biaya }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $program->status_badge !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('program-kerja.show', $program) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
```

## 7. Installation & Setup Guide

### Prerequisites
- PHP 8.1+
- MySQL/MariaDB 10.3+
- Composer
- Node.js 18+
- NPM/Yarn

### Installation Steps:

1. **Create New Laravel Project**
```bash
composer create-project laravel/laravel ebudget-sederhana
cd ebudget-sederhana
```

2. **Configure .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebudget
DB_USERNAME=root
DB_PASSWORD=
```

3. **Install Alpine.js Locally**
```bash
npm install alpinejs @alpinejs/persist @alpinejs/mask @alpinejs/anchor
```

4. **Install Additional Dependencies**
```bash
npm install tailwindcss postcss autoprefixer
npm install @heroicons/vue
```

5. **Configure Vite (vite.config.js)**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

6. **Run Database Migrations**
```bash
php artisan migrate
```

7. **Seed Database**
```bash
php artisan db:seed
```

8. **Compile Assets**
```bash
npm run build
```

9. **Create Storage Link**
```bash
php artisan storage:link
```

10. **Start Development Server**
```bash
php artisan serve
npm run dev
```

## 8. Flexible Approval Configuration

### Contoh Konfigurasi Approval yang Dapat Di-customize:

```sql
-- Level 1: Kepala Divisi (WAJIB untuk SEMUA pengajuan dari staff)
-- Setiap pengajuan dari staff HARUS melalui kepala divisi dulu
(0, 999999999, 1, 2, NULL, 1, 1), -- Role 2 = kepala_divisi, wajib untuk semua nominal

-- Level 2+: Approver di atas kepala divisi (CUSTOMIZABLE)
-- Untuk > 10 Juta butuh manager (bisa diganti dengan role lain)
(10000000, 999999999, 2, 5, NULL, 0, 2), -- Role 5 = manager, opsional (is_required = 0)

-- Untuk > 100 Juta butuh Direktur Keuangan (BISA DIGANTI dengan role lain)
(100000000, 999999999, 3, 1, NULL, 0, 3), -- Role 1 = direktur_keuangan, opsional

-- Untuk > 500 Juta butuh Direktur Utama (BISA DIGANTI dengan role lain)
(500000000, 999999999, 4, 6, NULL, 0, 4), -- Role 6 = direktur_utama, opsional

-- Contoh Alternative Approver di atas kepala divisi:
-- Ganti Direktur Keuangan dengan Senior Manager
(100000000, 999999999, 3, 8, NULL, 0, 3), -- Role 8 = senior_manager

-- Ganti dengan Komite Anggaran
(200000000, 999999999, 3, 9, NULL, 0, 3), -- Role 9 = komite_anggaran

-- Approval khusus untuk divisi IT (di atas kepala divisi):
-- CTO approval untuk > 50 Juta di IT
(50000000, 999999999, 2, 7, 1, 0, 2), -- Role 7 = cto, khusus divisi IT

-- CFO approval untuk > 200 Juta di Finance
(200000000, 999999999, 2, 10, 5, 0, 2), -- Role 10 = cfo, khusus divisi finance
```

### ApprovalService dengan Konfigurasi Dinamis

```php
<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\Approval;
use App\Models\ApprovalConfig;
use App\Models\User;

class ApprovalService
{
    public function getApprovalFlow($nominal, $divisiId = null, $isFromStaff = false)
    {
        // Get approval configurations based on nominal and division
        $query = ApprovalConfig::where('is_active', true)
            ->where(function($q) use ($nominal) {
                $q->where('minimal_nominal', '<=', $nominal)
                  ->where(function($subQuery) use ($nominal) {
                      $subQuery->whereNull('maximal_nominal')
                               ->orWhere('maximal_nominal', '>=', $nominal);
                  });
            });

        // Filter by division if specified
        if ($divisiId) {
            $query->where(function($q) use ($divisiId) {
                $q->whereNull('approver_divisi_id')
                  ->orWhere('approver_divisi_id', $divisiId);
            });
        }

        $approvalFlow = $query->orderBy('urutan')->get();

        // Jika pengajuan dari staff, PASTIKAN kepala divisi ada di level 1
        if ($isFromStaff) {
            $hasKepalaDivisi = $approvalFlow->contains('approver_role_id', 2); // 2 = kepala_divisi

            if (!$hasKepalaDivisi) {
                // Tambahkan kepala divisi sebagai level 1 wajib
                $approvalFlow->prepend((object)[
                    'id' => 0,
                    'minimal_nominal' => 0,
                    'maximal_nominal' => null,
                    'level_approval' => 1,
                    'approver_role_id' => 2,
                    'approver_divisi_id' => $divisiId,
                    'is_required' => 1,
                    'urutan' => 0,
                    'role' => (object)['name' => 'kepala_divisi']
                ]);
            }
        }

        // Re-number urutan
        $counter = 1;
        foreach ($approvalFlow as $config) {
            $config->urutan = $counter++;
        }

        return $approvalFlow;
    }

    public function processAutoApproval(PengajuanDana $pengajuan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId);

        // Check for auto-approval (amount below minimum for any approval)
        $minApprovalAmount = ApprovalConfig::where('is_active', true)
            ->where('is_required', true)
            ->min('minimal_nominal');

        if ($pengajuan->nominal_diajukan < $minApprovalAmount) {
            // Auto-approve with system
            Approval::create([
                'pengajuan_id' => $pengajuan->id,
                'approver_id' => null, // System auto-approve
                'level_approval' => 0,
                'status' => 'approved',
                'catatan' => 'Auto-approved (amount below minimum requirement)',
                'approved_at' => now()
            ]);

            $pengajuan->update(['status_pengajuan' => 'approved']);
            return true;
        }

        // Check for single-step approval scenarios
        $requiredApprovals = $approvalFlow->where('is_required', true);

        if ($requiredApprovals->count() === 1) {
            // Only one approval required, try to auto-assign
            $config = $requiredApprovals->first();
            $approver = $this->getApprover($config, $divisiId);

            if ($approver && $pengajuan->nominal_diajukan < 1000000) { // < 1 Juta
                Approval::create([
                    'pengajuan_id' => $pengajuan->id,
                    'approver_id' => $approver->id,
                    'level_approval' => $config->level_approval,
                    'status' => 'approved',
                    'catatan' => 'Auto-approved (amount < 1 Juta)',
                    'approved_at' => now()
                ]);

                $pengajuan->update(['status_pengajuan' => 'approved']);
                return true;
            }
        }

        return false; // Manual approval required
    }

    public function getRequiredApprovers(PengajuanDana $pengajuan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;

        // Check if pengajuan from staff (creator role = staff_divisi)
        $isFromStaff = $pengajuan->creator->role_id === 3; // 3 = staff_divisi

        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId, $isFromStaff);

        $approvers = [];
        foreach ($approvalFlow as $config) {
            $approver = $this->getApprover($config, $divisiId);
            if ($approver) {
                $approvers[] = [
                    'level' => $config->level_approval,
                    'role' => $config->approver_role_id,
                    'role_name' => $config->role->name ?? 'Unknown',
                    'approver' => $approver,
                    'is_required' => $config->is_required,
                    'urutan' => $config->urutan,
                    'note' => $config->level_approval === 1 && $isFromStaff
                        ? 'Wajib: Semua pengajuan staff harus melalui kepala divisi'
                        : null
                ];
            }
        }

        return $approvers;
    }

    private function getApprover($config, $divisiId)
    {
        $query = User::where('role_id', $config->approver_role_id)
            ->where('is_active', true);

        // If specific divisi required
        if ($config->approver_divisi_id) {
            $query->where('divisi_id', $config->approver_divisi_id);
        }

        // For kepala divisi, get kepala divisi dari divisi pengaju
        if ($config->approver_role_id == 2 && $divisiId) { // 2 = kepala_divisi
            $query->where('divisi_id', $divisiId);
        }

        return $query->first();
    }

    public function canApprove(User $user, PengajuanDana $pengajuan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId);

        // Check if user's role is in the approval flow
        $userApprovalConfig = $approvalFlow->firstWhere('approver_role_id', $user->role_id);

        if (!$userApprovalConfig) {
            return false;
        }

        // Check division requirement
        if ($userApprovalConfig->approver_divisi_id &&
            $userApprovalConfig->approver_divisi_id != $user->divisi_id) {
            return false;
        }

        // For kepala divisi, must be from same divisi as pengaju (unless specified otherwise)
        if ($user->role_id == 2 && $user->divisi_id != $divisiId) {
            // Exception: Kepala divisi bisa approve cross-division jika diizinkan
            $crossDivisionApproval = ApprovalConfig::where('approver_role_id', 2)
                ->whereNull('approver_divisi_id')
                ->where('is_active', true)
                ->exists();

            if (!$crossDivisionApproval) {
                return false;
            }
        }

        // Check if previous level is completed (sequential approval)
        if ($userApprovalConfig->urutan > 1) {
            $previousLevel = $approvalFlow
                ->where('urutan', $userApprovalConfig->urutan - 1)
                ->where('is_required', true)
                ->first();

            if ($previousLevel) {
                $previousApproval = Approval::where('pengajuan_id', $pengajuan->id)
                    ->where('level_approval', $previousLevel->level_approval)
                    ->where('status', 'approved')
                    ->exists();

                if (!$previousApproval) {
                    return false;
                }
            }
        }

        return true;
    }

    public function processApproval(User $user, PengajuanDana $pengajuan, $status, $catatan = null)
    {
        if (!$this->canApprove($user, $pengajuan)) {
            throw new \Exception('Anda tidak memiliki otoritas atau approval level belum terpenuhi');
        }

        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId);
        $userLevel = $approvalFlow->firstWhere('approver_role_id', $user->role_id);

        // Create or update approval
        Approval::updateOrCreate(
            [
                'pengajuan_id' => $pengajuan->id,
                'approver_id' => $user->id,
                'level_approval' => $userLevel->level_approval
            ],
            [
                'status' => $status,
                'catatan' => $catatan,
                'approved_at' => now()
            ]
        );

        // Update pengajuan status
        if ($status === 'rejected') {
            $pengajuan->update(['status_pengajuan' => 'rejected']);
        } else {
            $this->updateFinalStatus($pengajuan);
        }

        // Send notification to next approver if needed
        if ($status === 'approved') {
            $this->notifyNextApprover($pengajuan, $userLevel->urutan);
        }
    }

    private function updateFinalStatus(PengajuanDana $pengajuan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId);
        $requiredApprovals = $approvalFlow->where('is_required', true);

        $allCompleted = true;
        foreach ($requiredApprovals as $config) {
            $approval = Approval::where('pengajuan_id', $pengajuan->id)
                ->where('level_approval', $config->level_approval)
                ->first();

            if (!$approval || $approval->status !== 'approved') {
                $allCompleted = false;
                break;
            }
        }

        if ($allCompleted) {
            $pengajuan->update(['status_pengajuan' => 'approved']);
        }
    }

    private function notifyNextApprover(PengajuanDana $pengajuan, $currentUrutan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId);

        $nextLevel = $approvalFlow
            ->where('urutan', '>', $currentUrutan)
            ->where('is_required', true)
            ->first();

        if ($nextLevel) {
            $approver = $this->getApprover($nextLevel, $divisiId);
            if ($approver) {
                // Send notification (email, notification, etc.)
                // TODO: Implement notification system
            }
        }
    }

    public function getApprovalStatus(PengajuanDana $pengajuan)
    {
        $divisiId = $pengajuan->program->alokasiPaguDivisi->divisi_id;
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId);

        $status = [];
        foreach ($approvalFlow as $config) {
            $approval = Approval::where('pengajuan_id', $pengajuan->id)
                ->where('level_approval', $config->level_approval)
                ->first();

            $status[] = [
                'level' => $config->level_approval,
                'role_name' => $config->role->name,
                'urutan' => $config->urutan,
                'is_required' => $config->is_required,
                'status' => $approval ? $approval->status : 'pending',
                'approver' => $approval ? $approval->approver->full_name : null,
                'approved_at' => $approval ? $approval->approved_at : null,
                'catatan' => $approval ? $approval->catatan : null
            ];
        }

        return $status;
    }

    public function getNextApprover(PengajuanDana $pengajuan, $currentLevel)
    {
        $divisiId = $pengajuan->getDivisiId();
        $approvalFlow = $this->getApprovalFlow($pengajuan->nominal_diajukan, $divisiId, $pengajuan->isFromStaff());

        // Find next level after current
        $nextConfig = $approvalFlow
            ->where('level_approval', '>', $currentLevel)
            ->where('is_required', true)
            ->first();

        if ($nextConfig) {
            return $this->getApprover($nextConfig, $divisiId);
        }

        return null;
    }
}
```

### Flexible Approval Management Interface

#### ApprovalConfigController dengan Custom Approver

```php
<?php

namespace App\Http\Controllers;

use App\Models\ApprovalConfig;
use App\Models\Role;
use App\Models\Divisi;
use Illuminate\Http\Request;

class ApprovalConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:direktur_keuangan,direktur_utama');
    }

    public function index()
    {
        $configs = ApprovalConfig::with(['role', 'divisi'])
            ->orderBy('urutan')
            ->orderBy('minimal_nominal')
            ->paginate(15);

        // Group by level untuk better visualization
        $groupedConfigs = $configs->groupBy('level_approval');

        return view('pages.approval-config.index', compact('configs', 'groupedConfigs'));
    }

    public function create()
    {
        // Roles yang bisa approve (dapat dikustom)
        $approverRoles = Role::where('is_active', true)
            ->whereIn('id', [1, 2, 5, 6, 7, 8, 9, 10]) // Direktur, Kepala Divisi, Manager, dll
            ->get();

        $divisis = Divisi::where('is_active', true)->get();

        return view('pages.approval-config.create', compact('approverRoles', 'divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'minimal_nominal' => 'required|numeric|min:0',
            'maximal_nominal' => 'nullable|numeric|gt:minimal_nominal',
            'approver_role_id' => 'required|exists:roles,id',
            'approver_divisi_id' => 'nullable|exists:divisi,id',
            'is_required' => 'boolean',
            'urutan' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        ApprovalConfig::create([
            'minimal_nominal' => $request->minimal_nominal,
            'maximal_nominal' => $request->maximal_nominal,
            'level_approval' => $request->level_approval,
            'approver_role_id' => $request->approver_role_id,
            'approver_divisi_id' => $request->approver_divisi_id,
            'is_required' => $request->is_required ?? false,
            'urutan' => $request->urutan,
            'description' => $request->description
        ]);

        return redirect()
            ->route('approval-config.index')
            ->with('success', 'Konfigurasi approval berhasil ditambahkan');
    }

    public function edit(ApprovalConfig $approvalConfig)
    {
        $approverRoles = Role::where('is_active', true)
            ->whereIn('id', [1, 2, 5, 6, 7, 8, 9, 10])
            ->get();

        $divisis = Divisi::where('is_active', true)->get();

        return view('pages.approval-config.edit', compact('approvalConfig', 'approverRoles', 'divisis'));
    }

    public function update(Request $request, ApprovalConfig $approvalConfig)
    {
        $request->validate([
            'minimal_nominal' => 'required|numeric|min:0',
            'maximal_nominal' => 'nullable|numeric|gt:minimal_nominal',
            'approver_role_id' => 'required|exists:roles,id',
            'approver_divisi_id' => 'nullable|exists:divisi,id',
            'is_required' => 'boolean',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $approvalConfig->update($request->all());

        return redirect()
            ->route('approval-config.index')
            ->with('success', 'Konfigurasi approval berhasil diperbarui');
    }

    public function destroy(ApprovalConfig $approvalConfig)
    {
        // Jangan hapus kepala divisi config (level 1)
        if ($approvalConfig->level_approval === 1) {
            return back()
                ->withErrors(['error' => 'Konfigurasi Level 1 (Kepala Divisi) tidak dapat dihapus']);
        }

        if ($approvalConfig->approvals()->exists()) {
            return back()
                ->withErrors(['error' => 'Konfigurasi tidak dapat dihapus karena sudah digunakan']);
        }

        $approvalConfig->delete();

        return redirect()
            ->route('approval-config.index')
            ->with('success', 'Konfigurasi approval berhasil dihapus');
    }

    public function clone(ApprovalConfig $approvalConfig)
    {
        $newConfig = $approvalConfig->replicate();
        $newConfig->minimal_nominal = $approvalConfig->maximal_nominal + 1;
        $newConfig->save();

        return redirect()
            ->route('approval-config.edit', $newConfig)
            ->with('success', 'Konfigurasi berhasil di-clone');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0',
            'divisi_id' => 'required|exists:divisi,id',
            'is_staff' => 'boolean'
        ]);

        $nominal = $request->nominal;
        $divisiId = $request->divisi_id;
        $isFromStaff = $request->boolean('is_staff');

        $approvalService = new \App\Services\ApprovalService();
        $approvers = $approvalService->getRequiredApprovers([
            'nominal_diajukan' => $nominal,
            'sub_program' => (object)[
                'program' => (object)[
                    'alokasiPaguDivisi' => (object)[
                        'divisi_id' => $divisiId
                    ]
                ]
            ]);

        // Get creator role untuk simulation
        if ($isFromStaff) {
            $creator = (object)['role_id' => 3]; // staff_divisi
        } else {
            $creator = (object)['role_id' => 2]; // kepala_divisi
        }

        // Recalculate with proper role detection
        $isFromStaff = $creator->role_id === 3;
        $approvers = $approvalService->getRequiredApprovers([
            'nominal_diajukan' => $nominal,
            'sub_program' => (object)[
                'program' => (object)[
                    'alokasiPaguDivisi' => (object)[
                        'divisi_id' => $divisiId
                    ]
                ]
            ], true);

        // Add simulation info
        $simulation = [
            'nominal' => $nominal,
            'formatted_nominal' => 'Rp ' . number_format($nominal, 0, ',', '.'),
            'divisi' => Divisi::find($divisiId)->nama_divisi,
            'is_from_staff' => $isFromStaff,
            'total_levels' => count($approvers),
            'required_levels' => count(array_filter($approvers, fn($a) => $a['is_required'])),
            'staff_note' => $isFromStaff ?
                '✓ Semua pengajuan staff WAJIB melalui Kepala Divisi level 1' :
                'Pengajuan dari Kepala Divisi'
        ];

        return response()->json([
            'approvers' => $approvers,
            'simulation' => $simulation
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'configs' => 'required|array',
            'configs.*.id' => 'required|exists:approval_config,id',
            'configs.*.is_active' => 'boolean',
            'configs.*.is_required' => 'boolean'
        ]);

        foreach ($request->configs as $config) {
            $approvalConfig = ApprovalConfig::find($config['id']);

            // Jangan ubah kepala divisi required status
            if ($approvalConfig->level_approval === 1) {
                $config['is_required'] = 1;
            }

            $approvalConfig->update($config);
        }

        return response()->json([
            'success' => true,
            'message' => 'Konfigurasi berhasil diperbarui'
        ]);
    }

    // API untuk get available roles untuk dropdown
    public function getApproverRoles()
    {
        $roles = Role::where('is_active', true)
            ->whereNotIn('id', [3, 4]) // Exclude staff roles
            ->get(['id', 'name', 'description']);

        // Group roles by category
        $grouped = [
            'executive' => $roles->whereIn('name', ['direktur_utama', 'direktur_keuangan']),
            'management' => $roles->whereIn('name', ['manager', 'senior_manager']),
            'division' => $roles->whereIn('name', ['kepala_divisi']),
            'technical' => $roles->whereIn('name', ['cto']),
            'committee' => $roles->whereIn('name', ['komite_anggaran', 'komite_pengadaan'])
        ];

        return response()->json($grouped);
    }
}
```

### Form untuk Konfigurasi Approval (Blade Template)

```php
<!-- resources/views/pages/approval-config/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Konfigurasi Approval')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
                Tambah Konfigurasi Approval
            </h3>

            <form method="POST" action="{{ route('approval-config.store') }}">
                @csrf

                <!-- Nominal Range -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Minimal Nominal
                        </label>
                        <div class="mt-1">
                            <input type="number" name="minimal_nominal" required
                                   class="block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="0">
                            <p class="mt-1 text-sm text-gray-500">
                                Contoh: 10000000 (10 Juta)
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Maksimal Nominal (Opsional)
                        </label>
                        <div class="mt-1">
                            <input type="number" name="maximal_nominal"
                                   class="block w-full rounded-md border-gray-300 shadow-sm"
                                   placeholder="Kosongkan jika tidak ada batas">
                            <p class="mt-1 text-sm text-gray-500">
                                Kosongkan untuk "tidak ada batas atas"
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Approver Configuration -->
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Level Approval
                        </label>
                        <input type="number" name="level_approval" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                               placeholder="1">
                        <p class="mt-1 text-sm text-gray-500">
                            1 = Level pertama, 2 = Level kedua, dst
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Approver Role
                        </label>
                        <select name="approver_role_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }} - {{ $role->description }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Divisi (Opsional)
                        </label>
                        <select name="approver_divisi_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Semua Divisi</option>
                            @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            Kosongkan jika berlaku untuk semua divisi
                        </p>
                    </div>
                </div>

                <!-- Additional Settings -->
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Urutan Approval
                        </label>
                        <input type="number" name="urutan" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                               placeholder="1">
                        <p class="mt-1 text-sm text-gray-500">
                            Menentukan urutan dalam flow approval
                        </p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_required" id="is_required" value="1"
                               class="h-4 w-4 text-blue-600 rounded">
                        <label for="is_required" class="ml-2 block text-sm text-gray-900">
                            Wajib Approval
                        </label>
                        <span class="ml-4 text-sm text-gray-500">
                            Jika dicentang, level ini harus dipenuhi
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                  placeholder="Contoh: Approval untuk transaksi > 100 Juta"></textarea>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Preview Approval Flow</h4>
                    <div class="flex items-center space-x-2">
                        <input type="number" id="preview_nominal" placeholder="Nominal"
                               class="px-3 py-1 border rounded-md text-sm">
                        <select id="preview_divisi" class="px-3 py-1 border rounded-md text-sm">
                            @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                        <label class="flex items-center text-sm">
                            <input type="checkbox" id="preview_is_staff" class="mr-1">
                            Pengajuan dari Staff
                        </label>
                        <button type="button" onclick="previewApprovalFlow()"
                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            Preview
                        </button>
                    </div>
                    <div id="preview_result" class="mt-3 text-sm"></div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('approval-config.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewApprovalFlow() {
    const nominal = document.getElementById('preview_nominal').value;
    const divisiId = document.getElementById('preview_divisi').value;
    const isStaff = document.getElementById('preview_is_staff').checked;

    if (!nominal || !divisiId) {
        alert('Mohon isi nominal dan pilih divisi');
        return;
    }

    fetch('{{ route("approval-config.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            nominal: nominal,
            divisi_id: divisiId,
            is_staff: isStaff
        })
    })
    .then(response => response.json())
    .then(data => {
        let html = `
            <div class="font-semibold mb-2">${data.simulation.formatted_nominal} - ${data.simulation.divisi}</div>
            <div class="text-xs text-gray-600 mb-3">${data.simulation.staff_note}</div>
            <div class="space-y-2">
        `;

        data.approvers.forEach(approver => {
            const requiredBadge = approver.is_required ?
                '<span class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded-full">WAJIB</span>' :
                '<span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded-full">Opsional</span>';

            html += `
                <div class="flex items-center justify-between">
                    <span>Level ${approver.level}: ${approver.role_name}</span>
                    ${requiredBadge}
                </div>
            `;
        });

        html += `</div>`;
        document.getElementById('preview_result').innerHTML = html;
    });
}
</script>
@endpush
@endsection
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\ApprovalConfig;
use App\Models\Role;
use App\Models\Divisi;
use Illuminate\Http\Request;

class ApprovalConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:direktur_keuangan,direktur_utama');
    }

    public function index()
    {
        $configs = ApprovalConfig::with(['role', 'divisi'])
            ->orderBy('urutan')
            ->orderBy('minimal_nominal')
            ->paginate(15);

        return view('pages.approval-config.index', compact('configs'));
    }

    public function create()
    {
        $roles = Role::whereIn('id', [1, 2, 5, 6, 7]) // Roles yang bisa approve
            ->where('is_active', true)
            ->get();

        $divisis = Divisi::where('is_active', true)->get();

        return view('pages.approval-config.create', compact('roles', 'divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'minimal_nominal' => 'required|numeric|min:0',
            'maximal_nominal' => 'nullable|numeric|gt:minimal_nominal',
            'approver_role_id' => 'required|exists:roles,id',
            'approver_divisi_id' => 'nullable|exists:divisi,id',
            'is_required' => 'boolean',
            'urutan' => 'required|integer|min:1'
        ]);

        ApprovalConfig::create($request->all());

        return redirect()
            ->route('approval-config.index')
            ->with('success', 'Konfigurasi approval berhasil ditambahkan');
    }

    public function edit(ApprovalConfig $approvalConfig)
    {
        $roles = Role::whereIn('id', [1, 2, 5, 6, 7])
            ->where('is_active', true)
            ->get();

        $divisis = Divisi::where('is_active', true)->get();

        return view('pages.approval-config.edit', compact('approvalConfig', 'roles', 'divisis'));
    }

    public function update(Request $request, ApprovalConfig $approvalConfig)
    {
        $request->validate([
            'minimal_nominal' => 'required|numeric|min:0',
            'maximal_nominal' => 'nullable|numeric|gt:minimal_nominal',
            'approver_role_id' => 'required|exists:roles,id',
            'approver_divisi_id' => 'nullable|exists:divisi,id',
            'is_required' => 'boolean',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $approvalConfig->update($request->all());

        return redirect()
            ->route('approval-config.index')
            ->with('success', 'Konfigurasi approval berhasil diperbarui');
    }

    public function destroy(ApprovalConfig $approvalConfig)
    {
        // Check if this config is being used
        if ($approvalConfig->approvals()->exists()) {
            return back()
                ->withErrors(['error' => 'Konfigurasi tidak dapat dihapus karena sudah digunakan']);
        }

        $approvalConfig->delete();

        return redirect()
            ->route('approval-config.index')
            ->with('success', 'Konfigurasi approval berhasil dihapus');
    }

    public function preview(Request $request)
    {
        $nominal = $request->nominal;
        $divisiId = $request->divisi_id;

        $approvalService = new \App\Services\ApprovalService();
        $approvers = $approvalService->getRequiredApprovers([
            'nominal_diajukan' => $nominal,
            'sub_program' => (object) [
                'program' => (object) [
                    'alokasiPaguDivisi' => (object) [
                        'divisi_id' => $divisiId
                    ]
                ]
            ]
        ]);

        return response()->json($approvers);
    }
}
```

### Seeder untuk Approval Config

```php
// database/seeders/ApprovalConfigSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalConfig;

class ApprovalConfigSeeder extends Seeder
{
    public function run()
    {
        // Basic approval flow
        $configs = [
            [
                'minimal_nominal' => 0,
                'maximal_nominal' => null,
                'level_approval' => 1,
                'approver_role_id' => 2, // Kepala Divisi
                'approver_divisi_id' => null,
                'is_required' => true,
                'urutan' => 1
            ],
            [
                'minimal_nominal' => 10000000, // 10 Juta
                'maximal_nominal' => null,
                'level_approval' => 2,
                'approver_role_id' => 5, // Manager
                'approver_divisi_id' => null,
                'is_required' => true,
                'urutan' => 2
            ],
            [
                'minimal_nominal' => 100000000, // 100 Juta
                'maximal_nominal' => null,
                'level_approval' => 3,
                'approver_role_id' => 1, // Direktur Keuangan
                'approver_divisi_id' => null,
                'is_required' => true,
                'urutan' => 3
            ],
            [
                'minimal_nominal' => 1000000000, // 1 Miliar
                'maximal_nominal' => null,
                'level_approval' => 4,
                'approver_role_id' => 6, // Direktur Utama
                'approver_divisi_id' => null,
                'is_required' => true,
                'urutan' => 4
            ]
        ];

        foreach ($configs as $config) {
            ApprovalConfig::create($config);
        }
    }
}
```

## 9. Controller Examples

### DivisiController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisi = Divisi::withCount('users')->orderBy('nama_divisi')->paginate(10);
        return view('pages.divisi.index', compact('divisi'));
    }

    public function create()
    {
        return view('pages.divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_divisi' => 'required|unique:divisi,kode_divisi|max:20',
            'nama_divisi' => 'required|max:200',
            'singkatan' => 'nullable|max:50',
            'nama_kepala_divisi' => 'nullable|max:100',
            'deskripsi' => 'nullable|string'
        ]);

        Divisi::create($request->all());

        return redirect()
            ->route('divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan');
    }

    public function edit(Divisi $divisi)
    {
        $divisi->load('users');
        return view('pages.divisi.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'kode_divisi' => 'required|unique:divisi,kode_divisi,' . $divisi->id . '|max:20',
            'nama_divisi' => 'required|max:200',
            'singkatan' => 'nullable|max:50',
            'nama_kepala_divisi' => 'nullable|max:100',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $divisi->update($request->all());

        return redirect()
            ->route('divisi.index')
            ->with('success', 'Divisi berhasil diperbarui');
    }

    public function destroy(Divisi $divisi)
    {
        // Cek apakah ada user terkait
        if ($divisi->users()->count() > 0) {
            return back()
                ->withErrors(['error' => 'Tidak dapat menghapus divisi yang memiliki user']);
        }

        $divisi->delete();

        return redirect()
            ->route('divisi.index')
            ->with('success', 'Divisi berhasil dihapus');
    }
}
```

### AlokasiPaguController

```php
<?php

namespace App\Http\Controllers;

use App\Models\PaguAnggaran;
use App\Models\AlokasiPaguDivisi;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AlokasiPaguController extends Controller
{
    public function index(PaguAnggaran $pagu)
    {
        $alokasi = $pagu->alokasiPaguDivisi()
            ->with('divisi', 'creator')
            ->withCount('programKerja')
            ->get();

        $divisiBelumAlokasi = Divisi::whereNotIn('id', $alokasi->pluck('divisi_id'))
            ->where('is_active', true)
            ->get();

        $totalAlokasi = $alokasi->sum('nilai_pagu');
        $sisaPagu = $pagu->total_pagu - $totalAlokasi;

        return view('pages.alokasi-pagu.index', compact(
            'pagu',
            'alokasi',
            'divisiBelumAlokasi',
            'totalAlokasi',
            'sisaPagu'
        ));
    }

    public function store(Request $request, PaguAnggaran $pagu)
    {
        $request->validate([
            'divisi_id' => 'required|exists:divisi,id|unique:alokasi_pagu_divisi,divisi_id,NULL,NULL,pagu_id,'.$pagu->id,
            'nilai_pagu' => 'required|numeric|min:1',
            'catatan' => 'nullable|string'
        ]);

        // Check total alokasi tidak melebihi pagu
        $totalAlokasi = $pagu->alokasiPaguDivisi()->sum('nilai_pagu');
        $sisaPagu = $pagu->total_pagu - $totalAlokasi;

        if ($request->nilai_pagu > $sisaPagu) {
            return back()
                ->withErrors(['nilai_pagu' => 'Nilai pagu melebihi sisa pagu yang tersedia (Rp ' . number_format($sisaPagu, 0, ',', '.') . ')'])
                ->withInput();
        }

        AlokasiPaguDivisi::create([
            'pagu_id' => $pagu->id,
            'divisi_id' => $request->divisi_id,
            'nilai_pagu' => $request->nilai_pagu,
            'catatan' => $request->catatan,
            'created_by' => auth()->id()
        ]);

        return redirect()
            ->route('alokasi.index', $pagu)
            ->with('success', 'Alokasi pagu berhasil ditambahkan');
    }

    public function edit(AlokasiPaguDivisi $alokasi)
    {
        $alokasi->load(['pagu', 'divisi', 'programKerja']);
        $totalProgram = $alokasi->programKerja()->sum('estimasi_biaya');

        return view('pages.alokasi-pagu.edit', compact('alokasi', 'totalProgram'));
    }

    public function update(Request $request, AlokasiPaguDivisi $alokasi)
    {
        $request->validate([
            'nilai_pagu' => 'required|numeric|min:' . $alokasi->nilai_terpakai,
            'catatan' => 'nullable|string',
            'status' => ['required', Rule::in(['active', 'frozen', 'closed'])]
        ]);

        // Check tidak boleh kurang dari yang sudah terpakai
        if ($request->nilai_pagu < $alokasi->nilai_terpakai) {
            return back()
                ->withErrors(['nilai_pagu' => 'Nilai pagu tidak boleh kurang dari yang sudah terpakai (Rp ' . number_format($alokasi->nilai_terpakai, 0, ',', '.') . ')'])
                ->withInput();
        }

        $alokasi->update($request->all());

        return redirect()
            ->route('alokasi.index', $alokasi->pagu)
            ->with('success', 'Alokasi pagu berhasil diperbarui');
    }

    public function destroy(AlokasiPaguDivisi $alokasi)
    {
        // Cek apakah ada program terkait
        if ($alokasi->programKerja()->count() > 0) {
            return back()
                ->withErrors(['error' => 'Tidak dapat menghapus alokasi yang memiliki program kerja']);
        }

        $paguId = $alokasi->pagu_id;
        $alokasi->delete();

        return redirect()
            ->route('alokasi.index', $paguId)
            ->with('success', 'Alokasi pagu berhasil dihapus');
    }

    public function realokasi(AlokasiPaguDivisi $alokasi)
    {
        $alokasi->load(['pagu', 'divisi']);
        $alokasiLain = $alokasi->pagu->alokasiPaguDivisi()
            ->where('id', '!=', $alokasi->id)
            ->where('status', 'active')
            ->get();

        return view('pages.alokasi-pagu.realokasi', compact('alokasi', 'alokasiLain'));
    }

    public function prosesRealokasi(Request $request, AlokasiPaguDivisi $alokasi)
    {
        $request->validate([
            'target_alokasi_id' => 'required|exists:alokasi_pagu_divisi,id',
            'jumlah_realokasi' => 'required|numeric|min:1|max:' . ($alokasi->nilai_pagu - $alokasi->nilai_terpakai),
            'alasan' => 'required|string'
        ]);

        $targetAlokasi = AlokasiPaguDivisi::find($request->target_alokasi_id);

        try {
            DB::beginTransaction();

            // Kurangi pagu sumber
            $alokasi->update([
                'nilai_pagu' => $alokasi->nilai_pagu - $request->jumlah_realokasi
            ]);

            // Tambah ke pagu target
            $targetAlokasi->update([
                'nilai_pagu' => $targetAlokasi->nilai_pagu + $request->jumlah_realokasi
            ]);

            // Log realokasi (optional)
            DB::table('realokasi_pagu')->insert([
                'alokasi_sumber_id' => $alokasi->id,
                'alokasi_target_id' => $targetAlokasi->id,
                'jumlah' => $request->jumlah_realokasi,
                'alasan' => $request->alasan,
                'created_by' => auth()->id(),
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()
                ->route('alokasi.index', $alokasi->pagu)
                ->with('success', 'Realokasi pagu berhasil dilakukan');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
```

### ProgramKerjaController (Updated)

```php
<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use App\Models\SubProgram;
use App\Models\AlokasiPaguDivisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Filter berdasarkan divisi user (kecuali admin/finance)
        $query = ProgramKerja::with(['alokasiPaguDivisi.divisi', 'creator']);

        if (!$user->hasRole(['admin', 'finance'])) {
            $query->whereHas('alokasiPaguDivisi.divisi', function($q) use ($user) {
                $q->where('id', $user->divisi_id);
            });
        }

        $programs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.program-kerja.index', compact('programs'));
    }

    public function create()
    {
        $user = auth()->user();

        // Get alokasi pagu untuk divisi user
        $alokasiPagu = AlokasiPaguDivisi::with(['divisi', 'pagu'])
            ->where('status', 'active')
            ->when(!$user->hasRole(['admin', 'finance']), function($query) use ($user) {
                $query->where('divisi_id', $user->divisi_id);
            })
            ->get();

        return view('pages.program-kerja.create', compact('alokasiPagu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alokasi_pagu_divisi_id' => 'required|exists:alokasi_pagu_divisi,id',
            'nama_program' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'estimasi_biaya' => 'required|numeric|min:1',
            'durasi_mulai' => 'required|date',
            'durasi_selesai' => 'required|date|after:durasi_mulai'
        ]);

        // Check pagu availability
        $alokasi = AlokasiPaguDivisi::find($request->alokasi_pagu_divisi_id);
        $totalProgram = $alokasi->programKerja()
            ->whereIn('status', ['proposed', 'approved', 'active'])
            ->sum('estimasi_biaya');
        $sisaPagu = $alokasi->nilai_pagu - $totalProgram;

        if ($request->estimasi_biaya > $sisaPagu) {
            return back()
                ->withErrors(['estimasi_biaya' => 'Estimasi biaya melebihi sisa pagu divisi (Rp ' . number_format($sisaPagu, 0, ',', '.') . ')'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $kodeProgram = 'PRG-' . date('Y') . '-' . Str::upper(Str::random(6));

            ProgramKerja::create([
                'alokasi_pagu_divisi_id' => $request->alokasi_pagu_divisi_id,
                'kode_program' => $kodeProgram,
                'nama_program' => $request->nama_program,
                'deskripsi' => $request->deskripsi,
                'estimasi_biaya' => $request->estimasi_biaya,
                'durasi_mulai' => $request->durasi_mulai,
                'durasi_selesai' => $request->durasi_selesai,
                'status' => 'draft',
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()
                ->route('program-kerja.index')
                ->with('success', 'Program kerja berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function byAlokasi(AlokasiPaguDivisi $alokasi)
    {
        $programs = $alokasi->programKerja()
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalEstimasi = $programs->sum('estimasi_biaya');

        return response()->json([
            'programs' => $programs,
            'total_estimasi' => $totalEstimasi,
            'sisa_pagu' => $alokasi->nilai_pagu - $totalEstimasi
        ]);
    }
}
```

### PengajuanDanaController

```php
<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDana;
use App\Models\DetailPengajuan;
use App\Models\SubProgram;
use App\Services\NumberingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanDanaController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanDana::with(['subProgram.program', 'creator'])
            ->when(request('search'), function($query, $search) {
                $query->where('kode_pengajuan', 'like', "%{$search}%")
                      ->orWhere('keperluan', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.pengajuan-dana.index', compact('pengajuan'));
    }

    public function create()
    {
        $programs = SubProgram::with('program')->get();
        return view('pages.pengajuan-dana.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_program' => 'required|exists:program,id',
            'jenis_pengajuan' => 'required|in:kegiatan,pengadaan,pembayaran,honorarium,sewa,konsumsi,lainnya',
            'keperluan' => 'required|string',
            'tanggal_pengajuan' => 'required|date',
            'penerima_manfaat_type' => 'required|in:pengaju,pic_kegiatan,pegawai,vendor,non_pegawai,internal,external',
            'penerima_manfaat_id' => 'nullable|required_unless:penerima_manfaat_type,pengaju|integer',
            'penerima_manfaat_name' => 'nullable|required_if:penerima_manfaat_type,internal,external|string|max:200',
            'details' => 'required|array|min:1',
            'details.*.sub_program_id' => 'required|exists:sub_program,id',
            'details.*.item_name' => 'required|string',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.harga_satuan' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Generate kode pengajuan
            $kode_pengajuan = NumberingService::generateKodePengajuan();

            // Calculate total
            $total_nominal = collect($request->details)->sum(function($detail) {
                return $detail['quantity'] * $detail['harga_satuan'];
            });

            // Prepare penerima manfaat data
            $penerimaManfaatService = new PenerimaManfaatService();
            $penerimaData = $penerimaManfaatService->prepareData(
                $request->penerima_manfaat_type,
                $request->only(['penerima_manfaat_id', 'penerima_manfaat_name', 'penerima_manfaat_detail']),
                auth()->id()
            );

            $pengajuan = PengajuanDana::create([
                'kode_pengajuan' => $kode_pengajuan,
                'id_program' => $request->id_program,
                'jenis_pengajuan' => $request->jenis_pengajuan,
                'nominal_diajukan' => $total_nominal,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'keperluan' => $request->keperluan,
                'penerima_manfaat_type' => $penerimaData['penerima_manfaat_type'],
                'penerima_manfaat_id' => $penerimaData['penerima_manfaat_id'],
                'penerima_manfaat_name' => $penerimaData['penerima_manfaat_name'],
                'penerima_manfaat_detail' => $penerimaData['penerima_manfaat_detail'],
                'status_pengajuan' => 'pending',
                'created_by' => auth()->id()
            ]);

            // Insert details
            foreach ($request->details as $detail) {
                DetailPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'sub_program_id' => $detail['sub_program_id'],
                    'item_name' => $detail['item_name'],
                    'quantity' => $detail['quantity'],
                    'satuan' => $detail['satuan'] ?? '',
                    'harga_satuan' => $detail['harga_satuan'],
                    'total_harga' => $detail['quantity'] * $detail['harga_satuan']
                ]);
            }

            DB::commit();

            // Check if need approval
            $this->checkApprovalProcess($pengajuan);

            return redirect()
                ->route('pengajuan-dana.show', $pengajuan)
                ->with('success', 'Pengajuan dana berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    private function checkApprovalProcess($pengajuan)
    {
        $approvalConfigs = DB::table('approval_config')
            ->where('is_active', true)
            ->where('minimal_nominal', '<=', $pengajuan->nominal_diajukan)
            ->where(function($query) use ($pengajuan) {
                $query->whereNull('maximal_nominal')
                      ->orWhere('maximal_nominal', '>=', $pengajuan->nominal_diajukan);
            })
            ->orderBy('level_approval', 'asc')
            ->get();

        if ($approvalConfigs->isEmpty()) {
            $pengajuan->update(['status_pengajuan' => 'approved']);
        }
    }

    public function show(PengajuanDana $pengajuan)
    {
        $pengajuan->load(['details', 'subProgram.program', 'creator', 'approvals.approver']);
        return view('pages.pengajuan-dana.show', compact('pengajuan'));
    }

    public function ajaxDetail($id)
    {
        $pengajuan = PengajuanDana::with(['details', 'subProgram.program'])->find($id);

        return response()->json($pengajuan);
    }

    // Penerima Manfaat AJAX Methods
    public function getPenerimaManfaatOptions($jenisPengajuan)
    {
        $service = new PenerimaManfaatService();
        $options = $service->getAvailableOptions($jenisPengajuan, auth()->user()->divisi_id);

        return response()->json([
            'options' => $options
        ]);
    }

    public function getPenerimaManfaatList($type)
    {
        $service = new PenerimaManfaatService();
        $list = $service->getListByType($type, auth()->user()->divisi_id);

        return response()->json($list);
    }

    public function getPegawaiRekening($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get rekening info from user profile or create default structure
        $rekening = [
            'nomor' => $user->rekening_number ?? null,
            'bank' => $user->bank_name ?? null,
            'atas_nama' => $user->name
        ];

        return response()->json([
            'rekening' => $rekening
        ]);
    }
}
```

### PencairanDanaController (Updated untuk Flow Pembayaran)

```php
<?php

namespace App\Http\Controllers;

use App\Models\PencairanDana;
use App\Models\PengajuanDana;
use App\Services\NumberingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PencairanDanaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PencairanDana::with(['pengajuan.creator', 'pengajuan.program', 'creator']);

        // Filter berdasarkan role
        if ($user->hasRole('staff_keuangan')) {
            // Staff keuangan bisa lihat semua
        } elseif ($user->hasRole('direktur_keuangan')) {
            // Direktur keuangan bisa lihat semua
        } elseif ($user->hasRole('kepala_divisi')) {
            // Kepala divisi lihat yang dari divisinya
            $query->whereHas('pengajuan.program.alokasiPaguDivisi.divisi', function($q) use ($user) {
                $q->where('id', $user->divisi_id);
            });
        } else {
            // Lainnya hanya lihat yang diajuankan
            $query->whereHas('pengajuan', function($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status_pencairan', $request->status);
        }

        // Filter pembayaran only
        if ($request->pembayaran_only) {
            $query->whereHas('pengajuan', function($q) {
                $q->where('jenis_pengajuan', 'pembayaran');
            });
        }

        $pencairan = $query->latest()->paginate(10);

        return view('pages.pencairan-dana.index', compact('pencairan'));
    }

    public function create(Request $request)
    {
        $pengajuanId = $request->pengajuan_id;
        $pengajuan = PengajuanDana::findOrFail($pengajuanId);

        // Cek apakah sudah ada pencairan
        $existingPencairan = PencairanDana::where('pengajuan_id', $pengajuanId)->first();
        if ($existingPencairan) {
            return redirect()->route('pencairan-dana.show', $existingPencairan)
                ->with('error', 'Pencairan untuk pengajuan ini sudah ada.');
        }

        return view('pages.pencairan-dana.create', compact('pengajuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_dana,id',
            'nominal_dicairkan' => 'required|numeric|min:0',
            'tanggal_pencairan' => 'required|date',
            'metode_pencairan' => 'required|string',
            'bukti_pencairan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $pengajuan = PengajuanDana::findOrFail($request->pengajuan_id);

        // Check if pengajuan is approved
        if (!in_array($pengajuan->status_pengajuan, ['approved', 'processed'])) {
            return back()->with('error', 'Pengajuan harus disetujui terlebih dahulu.');
        }

        try {
            DB::beginTransaction();

            $kodePencairan = NumberingService::generateKodePencairan();

            $pencairanData = [
                'kode_pencairan' => $kodePencairan,
                'pengajuan_id' => $request->pengajuan_id,
                'nominal_dicairkan' => $request->nominal_dicairkan,
                'tanggal_pencairan' => $request->tanggal_pencairan,
                'metode_pencairan' => $request->metode_pencairan,
                'status_pencairan' => 'pending',
                'created_by' => auth()->id()
            ];

            // Upload bukti pencairan
            if ($request->hasFile('bukti_pencairan')) {
                $file = $request->file('bukti_pencairan');
                $filename = 'bukti_' . $kodePencairan . '.' . $file->getClientOriginalExtension();
                $file->storeAs('bukti_pencairan', $filename);
                $pencairanData['bukti_pencairan'] = $filename;
            }

            $pencairan = PencairanDana::create($pencairanData);

            // Auto process for pembayaran jenis
            if ($pengajuan->isPembayaranType()) {
                $pencairan->processPencairan([
                    'metode_pencairan' => $request->metode_pencairan,
                    'bukti_pencairan' => $pencairanData['bukti_pencairan'] ?? null,
                    'catatan_pencairan' => 'Pencairan otomatis untuk jenis pembayaran'
                ]);

                // Send notification untuk pembayaran flow
                $notificationService = new EmailNotificationService();
                if ($pencairan->needsKonfirmasiPenerima()) {
                    $notificationService->sendPembayaranNotification($pengajuan, 'konfirmasi_diperlukan');
                } elseif ($pencairan->needsVerifikasiPengaju()) {
                    $notificationService->sendPembayaranNotification($pengajuan, 'verifikasi_diperlukan');
                }
            }

            DB::commit();

            return redirect()->route('pencairan-dana.show', $pencairan)
                ->with('success', 'Pencairan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(PencairanDana $pencairan)
    {
        $pencairan->load(['pengajuan.creator', 'pengajuan.details', 'creator', 'konfirmasiBy']);
        return view('pages.pencairan-dana.show', compact('pencairan'));
    }

    public function approvedList()
    {
        // Get approved pengajuans for pencairan
        $pengajuans = PengajuanDana::with(['creator', 'program.alokasiPaguDivisi.divisi'])
            ->where('status_pengajuan', 'approved')
            ->whereDoesntHave('pencairan')
            ->latest()
            ->paginate(10);

        return view('pages.pencairan-dana.approved', compact('pengajuans'));
    }

    public function process(Request $request, PengajuanDana $pengajuan)
    {
        return redirect()->route('pencairan-dana.create', ['pengajuan_id' => $pengajuan->id]);
    }

    // Flow Khusus Pembayaran
    public function konfirmasiPenerimaan(Request $request, PencairanDana $pencairan)
    {
        $user = auth()->user();

        // Validasi: hanya penerima manfaat yang bisa konfirmasi
        if ($pencairan->pengajuan->penerima_manfaat_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak melakukan konfirmasi untuk pencairan ini.');
        }

        // Validasi: hanya untuk pembayaran yang memerlukan konfirmasi
        if (!$pencairan->needsKonfirmasiPenerima()) {
            return back()->with('error', 'Pencairan ini tidak memerlukan konfirmasi penerimaan.');
        }

        try {
            $pencairan->konfirmasiPenerimaan($user->id);

            // Send notification pembayaran completed
            $notificationService = new EmailNotificationService();
            $notificationService->sendPembayaranNotification($pencairan->pengajuan, 'pembayaran_completed');

            return back()->with('success', 'Konfirmasi penerimaan dana berhasil. Status pengajuan telah selesai.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifikasiPembayaran(Request $request, PencairanDana $pencairan)
    {
        $user = auth()->user();

        // Validasi: hanya pengaju yang bisa verifikasi
        if ($pencairan->pengajuan->created_by !== $user->id) {
            return back()->with('error', 'Hanya pengaju yang bisa melakukan verifikasi pembayaran.');
        }

        // Validasi: hanya untuk pembayaran yang memerlukan verifikasi
        if (!$pencairan->needsVerifikasiPengaju()) {
            return back()->with('error', 'Pencairan ini tidak memerlukan verifikasi pengaju.');
        }

        $request->validate([
            'catatan_verifikasi' => 'nullable|string'
        ]);

        try {
            $pencairan->verifikasiPembayaran($user->id, $request->catatan_verifikasi);

            // Send notification pembayaran completed
            $notificationService = new EmailNotificationService();
            $notificationService->sendPembayaranNotification($pencairan->pengajuan, 'pembayaran_completed');

            return back()->with('success', 'Verifikasi pembayaran berhasil. Status pengajuan telah selesai.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, PencairanDana $pencairan)
    {
        $request->validate([
            'status_pencairan' => 'required|in:pending,processed,completed',
            'catatan_pencairan' => 'nullable|string'
        ]);

        $user = auth()->user();

        // Only staff keuangan can update status
        if (!$user->hasRole('staff_keuangan')) {
            return back()->with('error', 'Hanya staff keuangan yang bisa mengubah status pencairan.');
        }

        try {
            $pencairan->update([
                'status_pencairan' => $request->status_pencairan,
                'catatan_pencairan' => $request->catatan_pencairan
            ]);

            return back()->with('success', 'Status pencairan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
```

### ApprovalController

```php
<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDana;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    protected $notificationService;

    public function __construct(EmailNotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $userRole = auth()->user()->role;

        // Get approval configs for user role
        $approvalLevels = DB::table('approval_config')
            ->where('approver_role_id', $userRole->id)
            ->where('is_active', true)
            ->pluck('level_approval');

        $pendingApprovals = PengajuanDana::with(['creator', 'subProgram.program'])
            ->where('status_pengajuan', 'pending')
            ->whereHas('approvals', function($query) use ($approvalLevels) {
                $query->whereIn('level_approval', $approvalLevels)
                      ->whereNull('status');
            })
            ->orWhereDoesntHave('approvals')
            ->paginate(10);

        return view('pages.approval.index', compact('pendingApprovals'));
    }

    public function process(Request $request, PengajuanDana $pengajuan)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string'
        ]);

        $userRole = auth()->user()->role;

        // Get approval level for this user role based on amount
        $approvalConfig = DB::table('approval_config')
            ->where('approver_role_id', $userRole->id)
            ->where('is_active', true)
            ->where('minimal_nominal', '<=', $pengajuan->nominal_diajukan)
            ->where(function($query) use ($pengajuan) {
                $query->whereNull('maximal_nominal')
                      ->orWhere('maximal_nominal', '>=', $pengajuan->nominal_diajukan);
            })
            ->first();

        if (!$approvalConfig) {
            return back()->withErrors(['error' => 'Anda tidak memiliki otoritas untuk menyetujui pengajuan ini']);
        }

        try {
            DB::beginTransaction();

            // Create or update approval record
            Approval::updateOrCreate(
                [
                    'pengajuan_id' => $pengajuan->id,
                    'approver_id' => auth()->id(),
                    'level_approval' => $approvalConfig->level_approval
                ],
                [
                    'status' => $request->status,
                    'catatan' => $request->catatan,
                    'approved_at' => now()
                ]
            );

            // Check if all required approvals are completed
            $this->updatePengajuanStatus($pengajuan);

            DB::commit();

            // Send notifications based on approval status
            $notificationService = new EmailNotificationService();
            if ($request->status == 'approved') {
                // Get next approver if any
                $approvalService = new ApprovalService();
                $nextApprover = $approvalService->getNextApprover($pengajuan, $approvalConfig->level_approval);

                $notificationService->sendApprovalGivenNotification($pengajuan, auth()->user(), $nextApprover);
            } else {
                // Send rejection notification
                $notificationService->sendRejectedNotification($pengajuan, auth()->user(), $request->catatan);
            }

            $message = $request->status == 'approved'
                ? 'Pengajuan telah disetujui'
                : 'Pengajuan telah ditolak';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    private function updatePengajuanStatus($pengajuan)
    {
        $approvalConfigs = DB::table('approval_config')
            ->where('is_active', true)
            ->where('minimal_nominal', '<=', $pengajuan->nominal_diajukan)
            ->where(function($query) use ($pengajuan) {
                $query->whereNull('maximal_nominal')
                      ->orWhere('maximal_nominal', '>=', $pengajuan->nominal_diajukan);
            })
            ->orderBy('level_approval', 'asc')
            ->get();

        $approvals = Approval::where('pengajuan_id', $pengajuan->id)->get();
        $rejectedCount = $approvals->where('status', 'rejected')->count();
        $approvedCount = $approvals->where('status', 'approved')->count();

        if ($rejectedCount > 0) {
            $pengajuan->update(['status_pengajuan' => 'rejected']);
        } elseif ($approvedCount == $approvalConfigs->count()) {
            $pengajuan->update(['status_pengajuan' => 'approved']);
        }
    }
}
```

## 9. Model Examples

### Divisi Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_divisi',
        'nama_divisi',
        'singkatan',
        'nama_kepala_divisi',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function alokasiPagu(): HasMany
    {
        return $this->hasMany(AlokasiPaguDivisi::class);
    }

    public function activeAlokasi(): HasMany
    {
        return $this->alokasiPagu()->where('status', 'active');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor
    public function getNamaKepalaDivisiAttribute($value)
    {
        return $value ?: 'Belum ditetapkan';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>'
            : '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Non-aktif</span>';
    }
}
```

### AlokasiPaguDivisi Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlokasiPaguDivisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pagu_id',
        'divisi_id',
        'nilai_pagu',
        'nilai_terpakai',
        'status',
        'catatan',
        'created_by'
    ];

    protected $casts = [
        'nilai_pagu' => 'decimal:2',
        'nilai_terpakai' => 'decimal:2',
        'nilai_sisa' => 'decimal:2'
    ];

    public function pagu(): BelongsTo
    {
        return $this->belongsTo(PaguAnggaran::class);
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function programKerja(): HasMany
    {
        return $this->hasMany(ProgramKerja::class);
    }

    // Accessor
    public function getFormattedNilaiPaguAttribute()
    {
        return 'Rp ' . number_format($this->nilai_pagu, 0, ',', '.');
    }

    public function getFormattedNilaiTerpakaiAttribute()
    {
        return 'Rp ' . number_format($this->nilai_terpakai, 0, ',', '.');
    }

    public function getFormattedNilaiSisaAttribute()
    {
        return 'Rp ' . number_format($this->nilai_sisa, 0, ',', '.');
    }

    public function getPersentaseTerpakaiAttribute()
    {
        if ($this->nilai_pagu == 0) return 0;
        return round(($this->nilai_terpakai / $this->nilai_pagu) * 100, 2);
    }

    public function getPaguWarningLevelAttribute()
    {
        $persentase = $this->persentase_terpakai;

        if ($persentase >= 100) {
            return 'danger';
        } elseif ($persentase >= 80) {
            return 'warning';
        } elseif ($persentase >= 60) {
            return 'info';
        }

        return 'success';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>',
            'frozen' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Dibekukan</span>',
            'closed' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Ditutup</span>'
        ];

        return $badges[$this->status] ?? '';
    }

    // Methods
    public function isPaguAvailable($amount)
    {
        return ($this->nilai_pagu - $this->nilai_terpakai) >= $amount;
    }

    public function getAvailablePagu()
    {
        return $this->nilai_pagu - $this->nilai_terpakai;
    }
}
```

### ProgramKerja Model (Updated)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'alokasi_pagu_divisi_id',
        'kode_program',
        'nama_program',
        'deskripsi',
        'estimasi_biaya',
        'durasi_mulai',
        'durasi_selesai',
        'status',
        'created_by'
    ];

    protected $casts = [
        'estimasi_biaya' => 'decimal:2',
        'durasi_mulai' => 'date',
        'durasi_selesai' => 'date'
    ];

    public function alokasiPaguDivisi(): BelongsTo
    {
        return $this->belongsTo(AlokasiPaguDivisi::class);
    }

    public function divisi()
    {
        return $this->hasOneThrough(
            Divisi::class,
            AlokasiPaguDivisi::class,
            'id',
            'id',
            'alokasi_pagu_divisi_id',
            'divisi_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subProgram(): HasMany
    {
        return $this->hasMany(SubProgram::class);
    }

    public function pengajuan(): HasMany
    {
        return $this->hasManyThrough(
            PengajuanDana::class,
            SubProgram::class,
            'program_id',
            'sub_program_id',
            'id',
            'id'
        );
    }

    // Accessor
    public function getFormattedEstimasiBiayaAttribute()
    {
        return 'Rp ' . number_format($this->estimasi_biaya, 0, ',', '.');
    }

    public function getDurasiAttribute()
    {
        $mulai = \Carbon\Carbon::parse($this->durasi_mulai);
        $selesai = \Carbon\Carbon::parse($this->durasi_selesai);
        return $mulai->diffInDays($selesai) . ' hari';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft</span>',
            'proposed' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Diajukan</span>',
            'approved' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'active' => '<span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Aktif</span>',
            'completed' => '<span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-800">Selesai</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Dibatalkan</span>'
        ];

        return $badges[$this->status] ?? '';
    }
}
```

### User Model (Updated)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'role_id',
        'divisi_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function createdPrograms(): HasMany
    {
        return $this->hasMany(ProgramKerja::class, 'created_by');
    }

    public function createdPengajuan(): HasMany
    {
        return $this->hasMany(PengajuanDana::class, 'created_by');
    }

    // Methods
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->role->name === $roles;
        }

        return in_array($this->role->name, $roles);
    }

    public function canAccessDivisi($divisiId)
    {
        return $this->hasRole(['admin', 'finance']) || $this->divisi_id === $divisiId;
    }
}
```

### PengajuanDana Model (Updated)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PengajuanDana extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pengajuan',
        'program_id',
        'jenis_pengajuan',
        'nominal_diajukan',
        'tanggal_pengajuan',
        'keperluan',
        'penerima_manfaat_type',
        'penerima_manfaat_id',
        'penerima_manfaat_name',
        'penerima_manfaat_detail',
        'status_pengajuan',
        'created_by'
    ];

    protected $casts = [
        'nominal_diajukan' => 'decimal:2',
        'tanggal_pengajuan' => 'date',
        'penerima_manfaat_detail' => 'json'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramKerja::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPengajuan::class);
    }

    public function subPrograms(): HasManyThrough
    {
        return $this->hasManyThrough(
            DetailPengajuan::class,
            SubProgram::class,
            'pengajuan_id', 'sub_program_id'
        );
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    public function pencairan(): HasMany
    {
        return $this->hasMany(PencairanDana::class);
    }

    public function divisi()
    {
        return $this->hasOneThrough(
            ProgramKerja::class,
            AlokasiPaguDivisi::class,
            'id', 'alokasi_pagu_divisi_id'
        );
    }

    // Accessor
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal_diajukan, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft</span>',
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'pending_director' => '<span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Menunggu Direktur</span>',
            'approved' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Disetujui</span>',
            'rejected' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Ditolak</span>',
            'processed' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Diproses</span>',
            'completed' => '<span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Selesai</span>'
        ];

        return $badges[$this->status_pengajuan] ?? '';
    }

    // Helper methods
    public function getTotalDetails()
    {
        return $this->details()->sum('total_harga');
    }

    public function isFromStaff()
    {
        return $this->creator && $this->creator->role_id === 3; // staff_divisi
    }

    public function getDivisiId()
    {
        return $this->program->alokasiPaguDivisi->divisi_id;
    }

    // Relationships for penerima manfaat
    public function penerimaManfaatUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penerima_manfaat_id');
    }

    public function penerimaManfaatVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'penerima_manfaat_id');
    }

    public function penerimaManfaatLainnya(): BelongsTo
    {
        return $this->belongsTo(PenerimaManfaatLainnya::class, 'penerima_manfaat_id');
    }

    public function penerimaManfaatPic(): BelongsTo
    {
        return $this->belongsTo(PicKegiatan::class, 'penerima_manfaat_id');
    }

    // Accessors for penerima manfaat
    public function getJenisPengajuanLabelAttribute()
    {
        $labels = [
            'kegiatan' => 'Kegiatan',
            'pengadaan' => 'Pengadaan Barang/Jasa',
            'pembayaran' => 'Pembayaran/Fee',
            'honorarium' => 'Honorarium/Narasumber',
            'sewa' => 'Sewa',
            'konsumsi' => 'Konsumsi',
            'lainnya' => 'Lainnya'
        ];

        return $labels[$this->jenis_pengajuan] ?? 'Tidak Diketahui';
    }

    public function getPenerimaManfaatLabelAttribute()
    {
        switch($this->penerima_manfaat_type) {
            case 'pengaju':
                return 'Pengaju (' . $this->creator->name . ')';
            case 'pic_kegiatan':
                return 'PIC Kegiatan: ' . ($this->penerimaManfaatPic->nama_pic ?? $this->penerima_manfaat_name);
            case 'pegawai':
                return 'Pegawai: ' . ($this->penerimaManfaatUser->name ?? $this->penerima_manfaat_name);
            case 'vendor':
                return 'Vendor: ' . ($this->penerimaManfaatVendor->nama_vendor ?? $this->penerima_manfaat_name);
            case 'non_pegawai':
                return 'Non-Pegawai: ' . ($this->penerimaManfaatLainnya->nama_penerima ?? $this->penerima_manfaat_name);
            case 'internal':
                return 'Internal: ' . $this->penerima_manfaat_name;
            case 'external':
                return 'External: ' . $this->penerima_manfaat_name;
            default:
                return $this->penerima_manfaat_name ?? 'Tidak Diketahui';
        }
    }

    public function getRekeningInfoAttribute()
    {
        $detail = $this->penerima_manfaat_detail;

        switch($this->penerima_manfaat_type) {
            case 'vendor':
                $vendor = $this->penerimaManfaatVendor;
                return $vendor ? [
                    'nomor_rekening' => $vendor->nomor_rekening,
                    'nama_bank' => $vendor->nama_bank,
                    'atas_nama' => $vendor->nama_vendor
                ] : null;

            case 'pegawai':
                $user = $this->penerimaManfaatUser;
                return $user && isset($detail['rekening']) ? [
                    'nomor_rekening' => $detail['rekening']['nomor'] ?? null,
                    'nama_bank' => $detail['rekening']['bank'] ?? null,
                    'atas_nama' => $user->name
                ] : null;

            case 'non_pegawai':
                $penerima = $this->penerimaManfaatLainnya;
                return $penerima ? [
                    'nomor_rekening' => $penerima->nomor_rekening,
                    'nama_bank' => $penerima->nama_bank,
                    'atas_nama' => $penerima->nama_penerima
                ] : null;

            default:
                return isset($detail['rekening']) ? $detail['rekening'] : null;
        }
    }

    // Methods khusus untuk flow pembayaran
    public function isPembayaranType()
    {
        return $this->jenis_pengajuan === 'pembayaran';
    }

    public function requiresKonfirmasiPenerimaan()
    {
        return $this->isPembayaranType() && in_array($this->penerima_manfaat_type, ['pegawai']);
    }

    public function requiresVerifikasiPengaju()
    {
        return $this->isPembayaranType() && in_array($this->penerima_manfaat_type, ['vendor', 'non_pegawai', 'external']);
    }

    public function canSkipLpj()
    {
        return $this->isPembayaranType();
    }

    public function getFlowLabelAttribute()
    {
        if ($this->isPembayaranType()) {
            return 'Pembayaran - Proses berakhir di pencairan';
        }
        return 'Pengajuan Reguler - Memerlukan LPJ';
    }

    public function getCurrentFlowStepAttribute()
    {
        if (!$this->isPembayaranType()) {
            return 'regular_flow';
        }

        // Check pencairan status
        $pencairan = $this->pencairan()->latest()->first();

        if (!$pencairan) {
            return 'menunggu_pencairan';
        }

        if ($pencairan->status_pencairan === 'pending') {
            return 'pencairan_diproses';
        }

        if ($pencairan->status_pencairan === 'processed') {
            if ($this->requiresKonfirmasiPenerimaan()) {
                return 'menunggu_konfirmasi_penerima';
            }
            if ($this->requiresVerifikasiPengaju()) {
                return 'menunggu_verifikasi_pengaju';
            }
            return 'pembayaran_selesai';
        }

        if ($pencairan->status_pencairan === 'confirmed' || $pencairan->status_pencairan === 'completed') {
            return 'pembayaran_selesai';
        }

        return 'unknown';
    }

    public function getNextActionAttribute()
    {
        if (!$this->isPembayaranType()) {
            return null;
        }

        $currentStep = $this->current_flow_step;
        $user = auth()->user();

        switch($currentStep) {
            case 'menunggu_pencairan':
                if ($user->hasRole('staff_keuangan')) {
                    return [
                        'action' => 'process_pencairan',
                        'label' => 'Proses Pencairan',
                        'description' => 'Proses pencairan dana pembayaran'
                    ];
                }
                return null;

            case 'menunggu_konfirmasi_penerima':
                if ($this->penerima_manfaat_id === $user->id) {
                    return [
                        'action' => 'konfirmasi_penerimaan',
                        'label' => 'Konfirmasi Penerimaan Dana',
                        'description' => 'Konfirmasi bahwa dana telah diterima'
                    ];
                }
                return null;

            case 'menunggu_verifikasi_pengaju':
                if ($this->created_by === $user->id) {
                    return [
                        'action' => 'verifikasi_pembayaran',
                        'label' => 'Verifikasi Pembayaran',
                        'description' => 'Verifikasi bahwa pembayaran ke pihak external telah dilakukan'
                    ];
                }
                return null;

            default:
                return null;
        }
    }

    public function getTimelineStepsAttribute()
    {
        if (!$this->isPembayaranType()) {
            return [];
        }

        $steps = [
            ['step' => 'pengajuan', 'label' => 'Pengajuan Diajukan', 'completed' => true, 'date' => $this->created_at],
            ['step' => 'approval', 'label' => 'Approval', 'completed' => in_array($this->status_pengajuan, ['approved', 'processed', 'completed']), 'date' => null],
            ['step' => 'pencairan', 'label' => 'Pencairan Dana', 'completed' => false, 'date' => null],
            ['step' => 'selesai', 'label' => 'Selesai', 'completed' => false, 'date' => null]
        ];

        // Update pencairan step
        $pencairan = $this->pencairan()->latest()->first();
        if ($pencairan) {
            $steps[2]['completed'] = true;
            $steps[2]['date'] = $pencairan->tanggal_pencairan;

            // Update selesai step
            if (in_array($pencairan->status_pencairan, ['confirmed', 'completed'])) {
                $steps[3]['completed'] = true;
                $steps[3]['date'] = $pencairan->tanggal_konfirmasi;
            }
        }

        return $steps;
    }
}
```

### DetailPengajuan Model (Updated)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'sub_program_id',
        'item_name',
        'quantity',
        'satuan',
        'harga_satuan',
        'total_harga'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    public function subProgram(): BelongsTo
    {
        return $this->belongsTo(SubProgram::class);
    }

    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}
```

### Vendor Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'nama_kontak',
        'email',
        'telepon',
        'alamat',
        'nomor_npwp',
        'nomor_rekening',
        'nama_bank',
        'kategori_vendor',
        'status_vendor'
    ];

    protected $casts = [
        'alamat' => 'string'
    ];

    public function pengajuans(): HasMany
    {
        return $this->hasMany(PengajuanDana::class, 'penerima_manfaat_id');
    }

    public function getKategoriVendorLabelAttribute()
    {
        $labels = [
            'barang' => 'Barang',
            'jasa' => 'Jasa',
            'konsultan' => 'Konsultan',
            'lainnya' => 'Lainnya'
        ];

        return $labels[$this->kategori_vendor] ?? 'Tidak Diketahui';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>',
            'inactive' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>',
            'blacklist' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Blacklist</span>'
        ];

        return $badges[$this->status_vendor] ?? '';
    }
}
```

### PenerimaManfaatLainnya Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerimaManfaatLainnya extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penerima',
        'jenis_identitas',
        'nomor_identitas',
        'email',
        'telepon',
        'alamat',
        'nomor_rekening',
        'nama_bank',
        'kategori_penerima'
    ];

    protected $casts = [
        'alamat' => 'string'
    ];

    public function pengajuans(): HasMany
    {
        return $this->hasMany(PengajuanDana::class, 'penerima_manfaat_id');
    }

    public function getKategoriPenerimaLabelAttribute()
    {
        $labels = [
            'speaker' => 'Speaker/Narasumber',
            'peserta' => 'Peserta',
            'mitra' => 'Mitra',
            'donatur' => 'Donatur',
            'lainnya' => 'Lainnya'
        ];

        return $labels[$this->kategori_penerima] ?? 'Tidak Diketahui';
    }
}
```

### PicKegiatan Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PicKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kegiatan_id',
        'nama_pic',
        'divisi_id',
        'jabatan',
        'email',
        'telepon',
        'status_pic'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function pengajuans(): HasMany
    {
        return $this->hasMany(PengajuanDana::class, 'penerima_manfaat_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>',
            'inactive' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>'
        ];

        return $badges[$this->status_pic] ?? '';
    }
}
```

### Notification Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'sent_via_email'
    ];

    protected $casts = [
        'data' => 'json',
        'is_read' => 'boolean',
        'sent_via_email' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute()
    {
        $icons = [
            'pengajuan_baru' => 'fas fa-file-invoice',
            'approval_required' => 'fas fa-user-check',
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            'pencairan_siap' => 'fas fa-money-bill-wave',
            'pembayaran_completed' => 'fas fa-check-double',
            'konfirmasi_diperlukan' => 'fas fa-hand-point-up',
            'verifikasi_diperlukan' => 'fas fa-search'
        ];

        return $icons[$this->type] ?? 'fas fa-bell';
    }

    public function getColorAttribute()
    {
        $colors = [
            'pengajuan_baru' => 'blue',
            'approval_required' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'pencairan_siap' => 'purple',
            'pembayaran_completed' => 'green',
            'konfirmasi_diperlukan' => 'orange',
            'verifikasi_diperlukan' => 'indigo'
        ];

        return $colors[$this->type] ?? 'gray';
    }
}
```

### PencairanDana Model (Updated untuk Flow Pembayaran)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencairanDana extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pencairan',
        'pengajuan_id',
        'nominal_dicairkan',
        'tanggal_pencairan',
        'metode_pencairan',
        'bukti_pencairan',
        'status_pencairan',
        'tanggal_konfirmasi',
        'dikonfirmasi_oleh',
        'catatan_pencairan',
        'created_by'
    ];

    protected $casts = [
        'nominal_dicairkan' => 'decimal:2',
        'tanggal_pencairan' => 'date',
        'tanggal_konfirmasi' => 'date'
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanDana::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function konfirmasiBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

    // Accessors
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal_dicairkan, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'processed' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Diproses</span>',
            'completed' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Selesai</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Dikonfirmasi</span>'
        ];

        return $badges[$this->status_pencairan] ?? '';
    }

    // Methods khusus untuk flow pembayaran
    public function isPembayaranType()
    {
        return $this->pengajuan && $this->pengajuan->isPembayaranType();
    }

    public function needsKonfirmasiPenerima()
    {
        return $this->isPembayaranType() && $this->pengajuan->requiresKonfirmasiPenerimaan();
    }

    public function needsVerifikasiPengaju()
    {
        return $this->isPembayaranType() && $this->pengajuan->requiresVerifikasiPengaju();
    }

    public function canMarkAsCompleted()
    {
        if (!$this->isPembayaranType()) {
            return $this->status_pencairan === 'processed';
        }

        // Untuk pembayaran, perlu konfirmasi/verifikasi
        if ($this->needsKonfirmasiPenerima()) {
            return $this->status_pencairan === 'confirmed';
        }

        if ($this->needsVerifikasiPengaju()) {
            return $this->status_pencairan === 'completed';
        }

        return $this->status_pencairan === 'processed';
    }

    public function processPencairan($data = [])
    {
        $this->update([
            'status_pencairan' => 'processed',
            'metode_pencairan' => $data['metode_pencairan'] ?? null,
            'bukti_pencairan' => $data['bukti_pencairan'] ?? null,
            'catatan_pencairan' => $data['catatan_pencairan'] ?? null
        ]);

        // Update status pengajuan
        $this->pengajuan->update(['status_pengajuan' => 'processed']);

        return $this;
    }

    public function konfirmasiPenerimaan($userId)
    {
        if (!$this->needsKonfirmasiPenerima()) {
            throw new \Exception('Pencairan ini tidak memerlukan konfirmasi penerimaan');
        }

        $this->update([
            'status_pencairan' => 'confirmed',
            'tanggal_konfirmasi' => now(),
            'dikonfirmasi_oleh' => $userId
        ]);

        // Update status pengajuan ke completed
        $this->pengajuan->update(['status_pengajuan' => 'completed']);

        return $this;
    }

    public function verifikasiPembayaran($userId, $catatan = null)
    {
        if (!$this->needsVerifikasiPengaju()) {
            throw new \Exception('Pencairan ini tidak memerlukan verifikasi pengaju');
        }

        $this->update([
            'status_pencairan' => 'completed',
            'tanggal_konfirmasi' => now(),
            'dikonfirmasi_oleh' => $userId,
            'catatan_pencairan' => $catatan
        ]);

        // Update status pengajuan ke completed
        $this->pengajuan->update(['status_pengajuan' => 'completed']);

        return $this;
    }
}
```

### PenerimaManfaatService (Business Logic)
```php
<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\User;
use App\Models\Vendor;
use App\Models\PenerimaManfaatLainnya;
use App\Models\PicKegiatan;

class PenerimaManfaatService
{
    /**
     * Get available penerima manfaat options based on jenis pengajuan
     */
    public function getAvailableOptions($jenisPengajuan, $divisiId = null)
    {
        $options = [];

        switch($jenisPengajuan) {
            case 'pengadaan':
                // For pengadaan, default to pengaju but can select vendor
                $options = [
                    ['type' => 'pengaju', 'label' => 'Pengaju (Default)', 'description' => 'Pengaju sebagai penerima manfaat'],
                    ['type' => 'vendor', 'label' => 'Vendor', 'description' => 'Pilih vendor yang sudah terdaftar'],
                    ['type' => 'non_pegawai', 'label' => 'Non-Pegawai', 'description' => 'Tambah penerima non-pegawai baru']
                ];
                break;

            case 'kegiatan':
                // For kegiatan, can select PIC or pengaju
                $options = [
                    ['type' => 'pengaju', 'label' => 'Pengaju', 'description' => 'Pengaju sebagai PIC'],
                    ['type' => 'pic_kegiatan', 'label' => 'PIC Kegiatan', 'description' => 'Pilih PIC dari daftar'],
                    ['type' => 'pegawai', 'label' => 'Pegawai Internal', 'description' => 'Pilih pegawai lain sebagai PIC'],
                    ['type' => 'non_pegawai', 'label' => 'Non-Pegawai', 'description' => 'Tambah PIC external']
                ];
                break;

            case 'honorarium':
            case 'pembayaran':
                // For honorarium/payment, can be pegawai, non-pegawai, or vendor
                $options = [
                    ['type' => 'pegawai', 'label' => 'Pegawai', 'description' => 'Bayar ke pegawai internal'],
                    ['type' => 'non_pegawai', 'label' => 'Non-Pegawai', 'description' => 'Bayar ke narasumber/speaker'],
                    ['type' => 'vendor', 'label' => 'Vendor', 'description' => 'Bayar ke vendor/konsultan']
                ];
                break;

            case 'sewa':
                // For sewa, usually vendor
                $options = [
                    ['type' => 'vendor', 'label' => 'Vendor', 'description' => 'Pilih vendor penyewa'],
                    ['type' => 'non_pegawai', 'label' => 'Pemilik Langsung', 'description' => 'Bayar ke pemilik bukan vendor']
                ];
                break;

            case 'konsumsi':
            case 'lainnya':
                // For konsumsi atau lainnya, flexible
                $options = [
                    ['type' => 'pengaju', 'label' => 'Pengaju', 'description' => 'Pengaju menangani langsung'],
                    ['type' => 'pegawai', 'label' => 'Pegawai', 'description' => 'Pegawai lain sebagai penanggung jawab'],
                    ['type' => 'vendor', 'label' => 'Vendor', 'description' => 'Bayar ke vendor'],
                    ['type' => 'non_pegawai', 'label' => 'Non-Pegawai', 'description' => 'Bayar ke pihak eksternal']
                ];
                break;
        }

        return $options;
    }

    /**
     * Get list of penerima manfaat by type
     */
    public function getListByType($type, $divisiId = null)
    {
        switch($type) {
            case 'pegawai':
                return User::whereHas('role', function($q) {
                    $q->whereIn('name', ['direktur_keuangan', 'kepala_divisi', 'staff_divisi', 'staff_keuangan']);
                })
                ->when($divisiId, function($q) use ($divisiId) {
                    $q->where('divisi_id', $divisiId);
                })
                ->get(['id', 'name', 'email', 'divisi_id'])
                ->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'divisi' => $user->divisi->nama_divisi ?? 'Tidak ada'
                    ];
                });

            case 'vendor':
                return Vendor::where('status_vendor', 'active')
                    ->get(['id', 'nama_vendor', 'email', 'telepon', 'kategori_vendor'])
                    ->map(function($vendor) {
                        return [
                            'id' => $vendor->id,
                            'name' => $vendor->nama_vendor,
                            'email' => $vendor->email,
                            'telepon' => $vendor->telepon,
                            'kategori' => $vendor->kategori_vendor_label
                        ];
                    });

            case 'pic_kegiatan':
                return PicKegiatan::with(['user', 'divisi'])
                    ->where('status_pic', 'active')
                    ->when($divisiId, function($q) use ($divisiId) {
                        $q->where('divisi_id', $divisiId);
                    })
                    ->get()
                    ->map(function($pic) {
                        return [
                            'id' => $pic->id,
                            'name' => $pic->nama_pic,
                            'email' => $pic->email,
                            'telepon' => $pic->telepon,
                            'jabatan' => $pic->jabatan,
                            'divisi' => $pic->divisi->nama_divisi ?? 'Tidak ada'
                        ];
                    });

            case 'non_pegawai':
                return PenerimaManfaatLainnya::get()
                    ->map(function($penerima) {
                        return [
                            'id' => $penerima->id,
                            'name' => $penerima->nama_penerima,
                            'email' => $penerima->email,
                            'telepon' => $penerima->telepon,
                            'kategori' => $penerima->kategori_penerima_label
                        ];
                    });

            default:
                return collect([]);
        }
    }

    /**
     * Validate penerima manfaat data
     */
    public function validateData($type, $data)
    {
        $rules = [];

        switch($type) {
            case 'pengaju':
                // No additional validation needed
                break;

            case 'pegawai':
                $rules = [
                    'penerima_manfaat_id' => 'required|exists:users,id',
                    'penerima_manfaat_detail.rekening.nomor' => 'required|string',
                    'penerima_manfaat_detail.rekening.bank' => 'required|string'
                ];
                break;

            case 'vendor':
                $rules = [
                    'penerima_manfaat_id' => 'required|exists:vendors,id'
                ];
                // Rekening info already in vendor data
                break;

            case 'non_pegawai':
                $rules = [
                    'penerima_manfaat_id' => 'nullable|exists:penerima_manfaat_lainnya,id',
                    'penerima_manfaat_name' => 'required_without:penerima_manfaat_id|string|max:200',
                    'penerima_manfaat_detail.email' => 'nullable|email',
                    'penerima_manfaat_detail.telepon' => 'nullable|string|max:20',
                    'penerima_manfaat_detail.rekening.nomor' => 'required|string',
                    'penerima_manfaat_detail.rekening.bank' => 'required|string'
                ];
                break;

            case 'pic_kegiatan':
                $rules = [
                    'penerima_manfaat_id' => 'required|exists:pic_kegiatan,id'
                ];
                break;
        }

        return $rules;
    }

    /**
     * Prepare penerima manfaat data for storage
     */
    public function prepareData($type, $data, $pengajuId)
    {
        $penerimaData = [
            'penerima_manfaat_type' => $type
        ];

        switch($type) {
            case 'pengaju':
                $penerimaData['penerima_manfaat_id'] = $pengajuId;
                $penerimaData['penerima_manfaat_name'] = null;
                $penerimaData['penerima_manfaat_detail'] = null;
                break;

            case 'pegawai':
                $penerimaData['penerima_manfaat_id'] = $data['penerima_manfaat_id'];
                $penerimaData['penerima_manfaat_name'] = null;
                $penerimaData['penerima_manfaat_detail'] = $data['penerima_manfaat_detail'] ?? null;
                break;

            case 'vendor':
            case 'pic_kegiatan':
                $penerimaData['penerima_manfaat_id'] = $data['penerima_manfaat_id'];
                $penerimaData['penerima_manfaat_name'] = null;
                $penerimaData['penerima_manfaat_detail'] = null;
                break;

            case 'non_pegawai':
                if (isset($data['penerima_manfaat_id'])) {
                    // Use existing
                    $penerimaData['penerima_manfaat_id'] = $data['penerima_manfaat_id'];
                    $penerimaData['penerima_manfaat_name'] = null;
                } else {
                    // Create new
                    $penerima = PenerimaManfaatLainnya::create([
                        'nama_penerima' => $data['penerima_manfaat_name'],
                        'email' => $data['penerima_manfaat_detail']['email'] ?? null,
                        'telepon' => $data['penerima_manfaat_detail']['telepon'] ?? null,
                        'nomor_rekening' => $data['penerima_manfaat_detail']['rekening']['nomor'] ?? null,
                        'nama_bank' => $data['penerima_manfaat_detail']['rekening']['bank'] ?? null,
                        'kategori_penerima' => 'lainnya'
                    ]);
                    $penerimaData['penerima_manfaat_id'] = $penerima->id;
                    $penerimaData['penerima_manfaat_name'] = null;
                }
                $penerimaData['penerima_manfaat_detail'] = $data['penerima_manfaat_detail'] ?? null;
                break;
        }

        return $penerimaData;
    }
}
```

### EmailNotificationService
```php
<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Send notification dan email untuk approval flow
     */
    public function sendApprovalNotification(PengajuanDana $pengajuan)
    {
        $approvalService = new ApprovalService();
        $approvalFlow = $approvalService->getApprovalFlow(
            $pengajuan->nominal_diajukan,
            $pengajuan->getDivisiId(),
            $pengajuan->isFromStaff()
        );

        foreach ($approvalFlow as $config) {
            $approver = $approvalService->getApprover($config, $pengajuan->getDivisiId());

            if ($approver) {
                // Buat notifikasi di database
                $this->createNotification([
                    'user_id' => $approver->id,
                    'type' => 'approval_required',
                    'title' => 'Pengajuan Dana Menunggu Approval',
                    'message' => "Pengajuan dana dengan kode {$pengajuan->kode_pengajuan} dari {$pengajuan->creator->name} menunggu approval Anda.",
                    'data' => [
                        'pengajuan_id' => $pengajuan->id,
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'nominal' => $pengajuan->formatted_nominal,
                        'pengaju' => $pengajuan->creator->name,
                        'approval_level' => $config->level_approval
                    ]
                ]);

                // Kirim email
                $this->sendEmailApprovalRequired($approver, $pengajuan, $config);
            }
        }
    }

    /**
     * Send notification saat approval diberikan
     */
    public function sendApprovalGivenNotification(PengajuanDana $pengajuan, $approver, $nextApprover = null)
    {
        // Notifikasi ke pengaju
        $this->createNotification([
            'user_id' => $pengajuan->created_by,
            'type' => 'approved',
            'title' => 'Pengajuan Dana Disetujui',
            'message' => "Pengajuan dana dengan kode {$pengajuan->kode_pengajuan} telah disetujui oleh {$approver->name}.",
            'data' => [
                'pengajuan_id' => $pengajuan->id,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'approved_by' => $approver->name,
                'approved_at' => now()->format('d/m/Y H:i')
            ]
        ]);

        // Kirim email ke pengaju
        $this->sendEmailApprovedToPengaju($pengajuan, $approver);

        // Jika masih ada approval selanjutnya, kirim notifikasi
        if ($nextApprover) {
            $this->createNotification([
                'user_id' => $nextApprover->id,
                'type' => 'approval_required',
                'title' => 'Pengajuan Dana Menunggu Approval',
                'message' => "Pengajuan dana dengan kode {$pengajuan->kode_pengajuan} telah disetujui dan menunggu approval Anda.",
                'data' => [
                    'pengajuan_id' => $pengajuan->id,
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'previous_approver' => $approver->name
                ]
            ]);

            $this->sendEmailApprovalRequired($nextApprover, $pengajuan);
        } else {
            // Jika ini approval terakhir
            $this->sendAllApprovalsCompletedNotification($pengajuan);
        }
    }

    /**
     * Send notification saat semua approval selesai
     */
    public function sendAllApprovalsCompletedNotification(PengajuanDana $pengajuan)
    {
        // Notifikasi ke pengaju bahwa semua approval selesai
        $this->createNotification([
            'user_id' => $pengajuan->created_by,
            'type' => 'approved',
            'title' => 'Semua Approval Selesai',
            'message' => "Pengajuan dana dengan kode {$pengajuan->kode_pengajuan} telah disetujui semua pihak dan siap untuk pencairan.",
            'data' => [
                'pengajuan_id' => $pengajuan->id,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'next_step' => 'pencairan'
            ]
        ]);

        // Kirim email ke pengaju
        $this->sendEmailAllApprovalsCompleted($pengajuan);

        // Notifikasi ke semua staff keuangan bahwa pencairan siap
        $this->notifyFinanceStaffPencairanReady($pengajuan);
    }

    /**
     * Send notification ke staff keuangan
     */
    public function notifyFinanceStaffPencairanReady(PengajuanDana $pengajuan)
    {
        $financeStaff = User::whereHas('role', function($query) {
            $query->where('name', 'staff_keuangan');
        })->get();

        foreach ($financeStaff as $staff) {
            $this->createNotification([
                'user_id' => $staff->id,
                'type' => 'pencairan_siap',
                'title' => 'Pencairan Dana Siap Diproses',
                'message' => "Pengajuan dana dengan kode {$pengajuan->kode_pengajuan} telah selesai approval dan siap untuk dicairkan.",
                'data' => [
                    'pengajuan_id' => $pengajuan->id,
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'nominal' => $pengajuan->formatted_nominal,
                    'penerima' => $pengajuan->penerima_manfaat_label
                ]
            ]);

            // Kirim email ke staff keuangan
            $this->sendEmailPencairanReady($staff, $pengajuan);
        }
    }

    /**
     * Send notification untuk pembayaran flow
     */
    public function sendPembayaranNotification(PengajuanDana $pengajuan, $eventType, $data = [])
    {
        switch ($eventType) {
            case 'konfirmasi_diperlukan':
                // Notifikasi ke user penerima untuk konfirmasi
                $this->createNotification([
                    'user_id' => $pengajuan->penerima_manfaat_id,
                    'type' => 'konfirmasi_diperlukan',
                    'title' => 'Konfirmasi Penerimaan Dana',
                    'message' => "Pengajuan pembayaran dengan kode {$pengajuan->kode_pengajuan} telah dicairkan. Silahkan konfirmasi penerimaan dana.",
                    'data' => [
                        'pengajuan_id' => $pengajuan->id,
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'nominal' => $pengajuan->formatted_nominal
                    ]
                ]);

                $this->sendEmailKonfirmasiRequired($pengajuan);
                break;

            case 'verifikasi_diperlukan':
                // Notifikasi ke pengaju untuk verifikasi
                $this->createNotification([
                    'user_id' => $pengajuan->created_by,
                    'type' => 'verifikasi_diperlukan',
                    'title' => 'Verifikasi Pembayaran External',
                    'message' => "Pengajuan pembayaran dengan kode {$pengajuan->kode_pengajuan} telah dicairkan. Silahkan verifikasi pembayaran ke pihak external.",
                    'data' => [
                        'pengajuan_id' => $pengajuan->id,
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'penerima' => $pengajuan->penerima_manfaat_label
                    ]
                ]);

                $this->sendEmailVerifikasiRequired($pengajuan);
                break;

            case 'pembayaran_completed':
                // Notifikasi ke semua pihak terkait
                $this->sendPembayaranCompletedNotification($pengajuan);
                break;
        }
    }

    /**
     * Send notification saat pembayaran selesai
     */
    public function sendPembayaranCompletedNotification(PengajuanDana $pengajuan)
    {
        // Notifikasi ke pengaju
        $this->createNotification([
            'user_id' => $pengajuan->created_by,
            'type' => 'pembayaran_completed',
            'title' => 'Pembayaran Selesai',
            'message' => "Pengajuan pembayaran dengan kode {$pengajuan->kode_pengajuan} telah selesai diproses.",
            'data' => [
                'pengajuan_id' => $pengajuan->id,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'completed_at' => now()->format('d/m/Y H:i')
            ]
        ]);

        // Notifikasi ke penerima (jika internal)
        if ($pengajuan->requiresKonfirmasiPenerimaan()) {
            $this->createNotification([
                'user_id' => $pengajuan->penerima_manfaat_id,
                'type' => 'pembayaran_completed',
                'title' => 'Pembayaran Selesai',
                'message' => "Pembayaran untuk pengajuan {$pengajuan->kode_pengajuan} telah selesai dan dikonfirmasi.",
                'data' => [
                    'pengajuan_id' => $pengajuan->id,
                    'nominal' => $pengajuan->formatted_nominal
                ]
            ]);
        }
    }

    /**
     * Create notification in database
     */
    private function createNotification($data)
    {
        return Notification::create($data);
    }

    /**
     * Email sending methods
     */
    private function sendEmailApprovalRequired($approver, $pengajuan, $config = null)
    {
        try {
            Mail::to($approver->email)->send(new \App\Mail\ApprovalRequired($pengajuan, $approver, $config));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email approval required: ' . $e->getMessage());
        }
    }

    private function sendEmailApprovedToPengaju($pengajuan, $approver)
    {
        try {
            Mail::to($pengajuan->creator->email)->send(new \App\Mail\ApprovalGiven($pengajuan, $approver));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email approval given: ' . $e->getMessage());
        }
    }

    private function sendEmailAllApprovalsCompleted($pengajuan)
    {
        try {
            Mail::to($pengajuan->creator->email)->send(new \App\Mail\AllApprovalsCompleted($pengajuan));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email all approvals completed: ' . $e->getMessage());
        }
    }

    private function sendEmailPencairanReady($staff, $pengajuan)
    {
        try {
            Mail::to($staff->email)->send(new \App\Mail\PencairanReady($pengajuan));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email pencairan ready: ' . $e->getMessage());
        }
    }

    private function sendEmailKonfirmasiRequired($pengajuan)
    {
        try {
            $penerima = $pengajuan->penerimaManfaatUser;
            if ($penerima) {
                Mail::to($penerima->email)->send(new \App\Mail\KonfirmasiPenerimaan($pengajuan));
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim email konfirmasi required: ' . $e->getMessage());
        }
    }

    private function sendEmailVerifikasiRequired($pengajuan)
    {
        try {
            Mail::to($pengajuan->creator->email)->send(new \App\Mail\VerifikasiPembayaran($pengajuan));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email verifikasi required: ' . $e->getMessage());
        }
    }

    /**
     * Send rejected notification
     */
    public function sendRejectedNotification(PengajuanDana $pengajuan, $rejecter, $alasan)
    {
        // Notifikasi ke pengaju
        $this->createNotification([
            'user_id' => $pengajuan->created_by,
            'type' => 'rejected',
            'title' => 'Pengajuan Dana Ditolak',
            'message' => "Pengajuan dana dengan kode {$pengajuan->kode_pengajuan} ditolak oleh {$rejecter->name}.",
            'data' => [
                'pengajuan_id' => $pengajuan->id,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'rejected_by' => $rejecter->name,
                'alasan' => $alasan
            ]
        ]);

        try {
            Mail::to($pengajuan->creator->email)->send(new \App\Mail\ApprovalRejected($pengajuan, $rejecter, $alasan));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email rejected: ' . $e->getMessage());
        }
    }
}
```

### NumberingService
```php
<?php

namespace App\Services;

use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use Illuminate\Support\Facades\DB;

class NumberingService
{
    /**
     * Generate kode pengajuan dengan format RF+YYMM+5digit (reset per bulan)
     */
    public static function generateKodePengajuan()
    {
        $yearMonth = date('ym');
        $prefix = 'RF';

        // Get last sequence for this month
        $lastKode = PengajuanDana::where('kode_pengajuan', 'like', $prefix . $yearMonth . '%')
            ->orderBy('kode_pengajuan', 'desc')
            ->value('kode_pengajuan');

        if ($lastKode) {
            // Extract last 5 digits and increment
            $lastSequence = intval(substr($lastKode, -5));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . $yearMonth . str_pad($newSequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode pencairan dengan format DF+YYMM+5digit (reset per bulan)
     */
    public static function generateKodePencairan()
    {
        $yearMonth = date('ym');
        $prefix = 'DF';

        // Get last sequence for this month
        $lastKode = PencairanDana::where('kode_pencairan', 'like', $prefix . $yearMonth . '%')
            ->orderBy('kode_pencairan', 'desc')
            ->value('kode_pencairan');

        if ($lastKode) {
            // Extract last 5 digits and increment
            $lastSequence = intval(substr($lastKode, -5));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . $yearMonth . str_pad($newSequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode LPJ dengan format RF+YYMM+5digit (reset per bulan)
     */
    public static function generateKodeLPJ()
    {
        $yearMonth = date('ym');
        $prefix = 'RF';

        // Get last sequence for this month from LPJ table
        $lastKode = DB::table('lpj')
            ->where('kode_lpj', 'like', $prefix . $yearMonth . '%')
            ->orderBy('kode_lpj', 'desc')
            ->value('kode_lpj');

        if ($lastKode) {
            // Extract last 5 digits and increment
            $lastSequence = intval(substr($lastKode, -5));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . $yearMonth . str_pad($newSequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode refund dengan format FR+YYMM+5digit (reset per bulan)
     */
    public static function generateKodeRefund()
    {
        $yearMonth = date('ym');
        $prefix = 'FR';

        // Get last sequence for this month from refund table
        $lastKode = DB::table('refund')
            ->where('kode_refund', 'like', $prefix . $yearMonth . '%')
            ->orderBy('kode_refund', 'desc')
            ->value('kode_refund');

        if ($lastKode) {
            // Extract last 5 digits and increment
            $lastSequence = intval(substr($lastKode, -5));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . $yearMonth . str_pad($newSequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate kode untuk tanggal tertentu (untuk bulk processing)
     */
    public static function generateKodePengajuanForDate($date)
    {
        $yearMonth = date('ym', strtotime($date));
        $prefix = 'RF';

        $lastKode = PengajuanDana::where('kode_pengajuan', 'like', $prefix . $yearMonth . '%')
            ->orderBy('kode_pengajuan', 'desc')
            ->value('kode_pengajuan');

        if ($lastKode) {
            $lastSequence = intval(substr($lastKode, -5));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . $yearMonth . str_pad($newSequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Parse kode untuk mendapatkan informasi
     */
    public static function parseKode($kode)
    {
        if (strlen($kode) < 9) {
            return null;
        }

        $prefix = substr($kode, 0, 2);
        $yearMonth = substr($kode, 2, 4);
        $sequence = substr($kode, -5);

        // Convert YYMM to readable format
        $year = '20' . substr($yearMonth, 0, 2);
        $month = substr($yearMonth, 2, 2);

        return [
            'prefix' => $prefix,
            'year_month' => $yearMonth,
            'sequence' => $sequence,
            'year' => $year,
            'month' => $month,
            'readable_date' => $month . '/' . $year,
            'type' => $this->getDokumenType($prefix)
        ];
    }

    /**
     * Get dokumen type dari prefix
     */
    private static function getDokumenType($prefix)
    {
        $types = [
            'RF' => 'Pengajuan Dana / LPJ',
            'DF' => 'Pencairan Dana',
            'FR' => 'Refund'
        ];

        return $types[$prefix] ?? 'Unknown';
    }

    /**
     * Validate kode format
     */
    public static function validateKode($kode, $expectedPrefix = null)
    {
        if (strlen($kode) !== 9) {
            return false;
        }

        $prefix = substr($kode, 0, 2);

        if ($expectedPrefix && $prefix !== $expectedPrefix) {
            return false;
        }

        if (!in_array($prefix, ['RF', 'DF', 'FR'])) {
            return false;
        }

        // Check if numeric after prefix
        $numericPart = substr($kode, 2);
        if (!is_numeric($numericPart)) {
            return false;
        }

        return true;
    }
}
```

## 10. TailwindCSS Configuration

### tailwind.config.js

```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                },
                success: {
                    50: '#f0fdf4',
                    500: '#22c55e',
                    600: '#16a34a',
                },
                warning: {
                    50: '#fffbeb',
                    500: '#f59e0b',
                    600: '#d97706',
                },
                danger: {
                    50: '#fef2f2',
                    500: '#ef4444',
                    600: '#dc2626',
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
```

## 11. Excel Export Configuration

### Install Laravel Excel

```bash
composer require maatwebsite/excel
```

### Example Export Class

```php
<?php

namespace App\Exports;

use App\Models\PengajuanDana;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengajuanExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = PengajuanDana::with(['creator', 'subProgram.program']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('tanggal_pengajuan', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('tanggal_pengajuan', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status_pengajuan', $this->filters['status']);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Kode Pengajuan',
            'Program',
            'Sub Program',
            'Nominal Diajukan',
            'Tanggal Pengajuan',
            'Keperluan',
            'Status',
            'Dibuat Oleh',
            'Tanggal Dibuat'
        ];
    }

    public function map($pengajuan): array
    {
        return [
            $pengajuan->kode_pengajuan,
            $pengajuan->program->nama_program ?? '',
            $pengajuan->details->first()->subProgram->nama_sub_program ?? '',
            number_format($pengajuan->nominal_diajukan, 2, ',', '.'),
            $pengajuan->tanggal_pengajuan->format('d/m/Y'),
            $pengajuan->keperluan,
            ucfirst($pengajuan->status_pengajuan),
            $pengajuan->creator->full_name,
            $pengajuan->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'EFF6FF']
                ]
            ]
        ];
    }
}
```

## 12. Testing Guide

### Create Tests

```bash
php artisan make:test PengajuanDanaTest
php artisan make:test ApprovalTest
```

### Example Test

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PengajuanDana;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanDanaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_pengajuan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/pengajuan-dana', [
                'sub_program_id' => 1,
                'keperluan' => 'Test pengajuan',
                'tanggal_pengajuan' => now()->format('Y-m-d'),
                'details' => [
                    [
                        'item_name' => 'Test Item',
                        'quantity' => 2,
                        'harga_satuan' => 100000
                    ]
                ]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pengajuan_dana', [
            'keperluan' => 'Test pengajuan'
        ]);
    }
}
```

## 13. Deployment Checklist

### Production Setup

1. **Environment Configuration**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=ebudget_prod
DB_USERNAME=ebudget_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password

FILESYSTEM_DISK=local
```

2. **SSL Certificate**
3. **Cron Job Setup**
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

4. **Queue Worker**
```bash
php artisan queue:work --daemon
```

5. **Backup Strategy**
```bash
php artisan backup:run --only-db
```

## 14. Maintenance & Updates

### Regular Tasks
- Update dependencies
- Database backups
- Log monitoring
- Performance optimization

### Feature Enhancements
- Mobile app version
- Advanced reporting
- Email notifications
- API documentation

### Konfigurasi Approval yang Dapat Dikustomisasi:

1. **Multi-level Approval**:
   - Level 1: Manager (untuk nominal < 10 juta)
   - Level 2: Director (untuk nominal 10-50 juta)
   - Level 3: CEO/Direktur Utama (untuk nominal > 50 juta)

2. **Configurable Threshold**:
   - Minimal dan maksimal nominal per level
   - Role yang dapat approve per level
   - Jumlah approver yang diperlukan

3. **Status Flow**:
   - Draft → Pending Approval → Approved → Processed → Completed
   - Draft → Pending Approval → Rejected → (Edit) → Pending Approval

4. **Email Notifications**:
   - Notifikasi saat pengajuan dibuat
   - Notifikasi saat approval dibutuhkan
   - Notifikasi saat status berubah

## 6. User Roles & Permissions

### Role Definitions:

1. **Admin**:
   - Full access to all modules
   - Configure system settings
   - Manage users and roles

2. **Finance Manager**:
   - Manage perencanaan penerimaan
   - Set pagu anggaran
   - Process pencairan
   - Generate reports

3. **Program Manager**:
   - Create program kerja
   - Make pengajuan dana
   - Create LPJ
   - View reports

4. **Approver**:
   - Review and approve/reject pengajuan
   - View pending approvals
   - Add notes on approval

5. **Staff**:
   - View assigned programs
   - View own pengajuan status
   - Create detail LPJ

## 7. UI/UX Design Guidelines

### Dashboard:
- Quick stats: Total anggaran, Total terpakai, Sisa anggaran
- Pending approvals count
- Recent activities
- Charts for visualization

### Key Pages:
1. **Login Page**: Simple and secure
2. **Dashboard**: Overview of all activities
3. **Perencanaan**: Form-based interface for planning
4. **Pengajuan**: Step-by-step wizard for submissions
5. **Approval**: List view with quick actions
6. **Laporan**: Filterable data tables with export options

### Design Principles:
- Clean and modern interface
- Mobile-responsive design
- Consistent color scheme (blue/gray for professionalism)
- Clear status indicators (color-coded)
- Accessible forms with validation

## 8. Security Considerations

### Authentication & Authorization:
- JWT token-based authentication
- Role-based access control (RBAC)
- Password encryption (bcrypt)
- Session timeout

### Data Security:
- Input validation on all forms
- SQL injection prevention
- XSS protection
- HTTPS required in production
- Audit trail for all financial transactions

## 9. Development Phases

### Phase 1: Core Features (4-6 weeks)
- User authentication
- Perencanaan penerimaan
- Pagu anggaran
- Basic program kerja

### Phase 2: Approval System (3-4 weeks)
- Pengajuan dana
- Multi-level approval
- Notification system

### Phase 3: Financial Flow (3-4 weeks)
- Pencairan dana
- LPJ system
- Refund mechanism

### Phase 4: Reporting & UI Enhancement (2-3 weeks)
- Advanced reports
- Excel export
- UI/UX improvements

### Phase 5: Testing & Deployment (2 weeks)
- Unit testing
- Integration testing
- User acceptance testing
- Production deployment

## 10. Hosting & Infrastructure

### Recommended Setup:
- **Frontend**: Vercel or Netlify
- **Backend**: Heroku, AWS EC2, or DigitalOcean
- **Database**: PostgreSQL on Amazon RDS or similar
- **File Storage**: AWS S3 for document uploads
- **Email**: SendGrid or AWS SES for notifications

### Monitoring:
- Application performance monitoring
- Error tracking (Sentry)
- Uptime monitoring
- Backup strategy for database

## 11. Maintenance & Support

### Regular Maintenance:
- Monthly security updates
- Database optimization
- Performance monitoring
- User feedback collection

### Support Plan:
- Help documentation
- User training sessions
- Bug fixing SLA
- Feature enhancement roadmap