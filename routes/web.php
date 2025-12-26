<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeriodeAnggaranController;
use App\Http\Controllers\SumberDanaController;
use App\Http\Controllers\PengajuanDanaController;
use App\Http\Controllers\DetailPengajuanController;
use App\Http\Controllers\PencairanDanaController;
use App\Http\Controllers\LpjController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ApprovalConfigController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\SubProgramController;
use App\Http\Controllers\DetailAnggaranController;
use App\Http\Controllers\EstimasiPengeluaranController;
use App\Http\Controllers\PenetapanPaguController;
use App\Http\Controllers\PerencanaanPenerimaanController;
use App\Http\Controllers\PencatatanPenerimaanController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// E-Budget Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // ============================================================
    // PERIODE ANGGARAN ROUTES (direktur_keuangan & staff_keuangan only)
    // ============================================================
    Route::prefix('periode-anggaran')->name('periode-anggaran.')->middleware('role:direktur_keuangan,staff_keuangan')->group(function () {
        Route::get('/', [PeriodeAnggaranController::class, 'index'])->name('index');
        Route::get('/create', [PeriodeAnggaranController::class, 'create'])->name('create');
        Route::post('/', [PeriodeAnggaranController::class, 'store'])->name('store');
        Route::get('/{periodeAnggaran}', [PeriodeAnggaranController::class, 'show'])->name('show');
        Route::get('/{periodeAnggaran}/edit', [PeriodeAnggaranController::class, 'edit'])->name('edit');
        Route::put('/{periodeAnggaran}', [PeriodeAnggaranController::class, 'update'])->name('update');
        Route::delete('/{periodeAnggaran}', [PeriodeAnggaranController::class, 'destroy'])->name('destroy');
        Route::post('/{periodeAnggaran}/activate', [PeriodeAnggaranController::class, 'activate'])->name('activate');
        Route::post('/{periodeAnggaran}/close', [PeriodeAnggaranController::class, 'close'])->name('close');
        Route::get('/api/options', [PeriodeAnggaranController::class, 'options'])->name('options');
        Route::get('/api/dashboard-summary', [PeriodeAnggaranController::class, 'dashboardSummary'])->name('dashboard-summary');
        Route::get('/{periodeAnggaran}/statistics', [PeriodeAnggaranController::class, 'statistics'])->name('statistics');
    });

    // ============================================================
    // SUMBER DANA ROUTES (direktur_keuangan & staff_keuangan only)
    // ============================================================
    Route::prefix('sumber-dana')->name('sumber-dana.')->middleware('role:direktur_keuangan,staff_keuangan')->group(function () {
        Route::get('/', [SumberDanaController::class, 'index'])->name('index');
        Route::get('/create', [SumberDanaController::class, 'create'])->name('create');
        Route::post('/', [SumberDanaController::class, 'store'])->name('store');
        Route::get('/{sumberDana}', [SumberDanaController::class, 'show'])->name('show');
        Route::get('/{sumberDana}/edit', [SumberDanaController::class, 'edit'])->name('edit');
        Route::put('/{sumberDana}', [SumberDanaController::class, 'update'])->name('update');
        Route::delete('/{sumberDana}', [SumberDanaController::class, 'destroy'])->name('destroy');
        Route::post('/{sumberDana}/toggle-status', [SumberDanaController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ============================================================
    // PROGRAM KERJA ROUTES
    // ============================================================
    Route::prefix('program-kerja')->name('program-kerja.')->group(function () {
        Route::get('/', [ProgramKerjaController::class, 'index'])->name('index');
        Route::get('/{divisi}', [ProgramKerjaController::class, 'divisiShow'])->name('divisi-show');
        Route::get('/{divisi}/create', [ProgramKerjaController::class, 'create'])->name('create');
        Route::post('/{divisi}', [ProgramKerjaController::class, 'store'])->name('store');
        Route::get('/{divisi}/{programKerja}', [ProgramKerjaController::class, 'show'])->name('show');
        Route::get('/{divisi}/{programKerja}/edit', [ProgramKerjaController::class, 'edit'])->name('edit');
        Route::put('/{divisi}/{programKerja}', [ProgramKerjaController::class, 'update'])->name('update');
        Route::delete('/{divisi}/{programKerja}', [ProgramKerjaController::class, 'destroy'])->name('destroy');
        // Additional routes
        Route::get('/{divisi}/{programKerja}/sub-programs', [ProgramKerjaController::class, 'subPrograms'])->name('sub-programs');
        Route::post('/{divisi}/{programKerja}/activate', [ProgramKerjaController::class, 'activate'])->name('activate');
        Route::post('/{divisi}/{programKerja}/suspend', [ProgramKerjaController::class, 'suspend'])->name('suspend');
        Route::get('/{divisi}/api/statistics', [ProgramKerjaController::class, 'statistics'])->name('statistics');

        // Sub Program Routes (nested under program-kerja)
        Route::prefix('{divisi}/{programKerja}/sub-programs')->name('sub-programs.')->group(function () {
            Route::post('/', [SubProgramController::class, 'store'])->name('store');
            Route::get('/{subProgram}/detail-anggarans', [SubProgramController::class, 'detailAnggarans'])->name('detail-anggarans');
            Route::put('/{subProgram}', [SubProgramController::class, 'update'])->name('update');
            Route::delete('/{subProgram}', [SubProgramController::class, 'destroy'])->name('destroy');

            // Detail Anggaran Routes (nested under sub-programs)
            Route::prefix('{subProgram}/detail-anggaran')->name('detail-anggaran.')->group(function () {
                Route::post('/', [DetailAnggaranController::class, 'store'])->name('store');
                Route::put('/{detailAnggaran}', [DetailAnggaranController::class, 'update'])->name('update');
                Route::delete('/{detailAnggaran}', [DetailAnggaranController::class, 'destroy'])->name('destroy');

                // Estimasi Pengeluaran Routes (nested under detail-anggaran)
                Route::prefix('{detailAnggaran}/estimasi-pengeluaran')->name('estimasi-pengeluaran.')->group(function () {
                    Route::put('/{estimasi}', [EstimasiPengeluaranController::class, 'update'])->name('update');
                    Route::post('/bulk-update-status', [EstimasiPengeluaranController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
                });
            });
        });
    });

    // ============================================================
    // PENETAPAN PAGU ROUTES
    // ============================================================
    Route::prefix('penetapan-pagu')->name('penetapan-pagu.')->group(function () {
        Route::get('/', [PenetapanPaguController::class, 'index'])->name('index');
        Route::get('/create', [PenetapanPaguController::class, 'create'])->name('create');
        Route::post('/', [PenetapanPaguController::class, 'store'])->name('store');
        Route::get('/{penetapanPagu}', [PenetapanPaguController::class, 'show'])->name('show');
        Route::get('/{penetapanPagu}/edit', [PenetapanPaguController::class, 'edit'])->name('edit');
        Route::put('/{penetapanPagu}', [PenetapanPaguController::class, 'update'])->name('update');
        Route::delete('/{penetapanPagu}', [PenetapanPaguController::class, 'destroy'])->name('destroy');
        Route::get('/api/statistics', [PenetapanPaguController::class, 'statistics'])->name('statistics');
    });

    // ============================================================
    // PERENCANAAN PENERIMAAN ROUTES
    // ============================================================
    Route::prefix('perencanaan-penerimaan')->name('perencanaan-penerimaan.')->group(function () {
        Route::get('/', [PerencanaanPenerimaanController::class, 'index'])->name('index');
        Route::get('/create', [PerencanaanPenerimaanController::class, 'create'])->name('create');
        Route::post('/', [PerencanaanPenerimaanController::class, 'store'])->name('store');
        Route::get('/{perencanaanPenerimaan}', [PerencanaanPenerimaanController::class, 'show'])->name('show');
        Route::get('/{perencanaanPenerimaan}/edit', [PerencanaanPenerimaanController::class, 'edit'])->name('edit');
        Route::put('/{perencanaanPenerimaan}', [PerencanaanPenerimaanController::class, 'update'])->name('update');
        Route::delete('/{perencanaanPenerimaan}', [PerencanaanPenerimaanController::class, 'destroy'])->name('destroy');
        Route::get('/api/months', [PerencanaanPenerimaanController::class, 'getMonths'])->name('getMonths');
        Route::get('/api/statistics', [PerencanaanPenerimaanController::class, 'statistics'])->name('statistics');
    });

    // ============================================================
    // PENCATATAN PENERIMAAN ROUTES
    // ============================================================
    Route::prefix('pencatatan-penerimaan')->name('pencatatan-penerimaan.')->group(function () {
        Route::get('/', [PencatatanPenerimaanController::class, 'index'])->name('index');
        Route::get('/create', [PencatatanPenerimaanController::class, 'create'])->name('create');
        Route::post('/', [PencatatanPenerimaanController::class, 'store'])->name('store');
        Route::get('/{pencatatanPenerimaan}', [PencatatanPenerimaanController::class, 'show'])->name('show');
        Route::get('/{pencatatanPenerimaan}/edit', [PencatatanPenerimaanController::class, 'edit'])->name('edit');
        Route::put('/{pencatatanPenerimaan}', [PencatatanPenerimaanController::class, 'update'])->name('update');
        Route::delete('/{pencatatanPenerimaan}', [PencatatanPenerimaanController::class, 'destroy'])->name('destroy');
        Route::post('/{pencatatanPenerimaan}/verify', [PencatatanPenerimaanController::class, 'verify'])->name('verify');
        Route::get('/api/statistics', [PencatatanPenerimaanController::class, 'statistics'])->name('statistics');
        Route::get('/export', [PencatatanPenerimaanController::class, 'export'])->name('export');
    });

    // ============================================================
    // PENGAJUAN DANA ROUTES
    // ============================================================
    Route::prefix('pengajuan-dana')->name('pengajuan-dana.')->group(function () {
        Route::get('/', [PengajuanDanaController::class, 'index'])->name('index');
        Route::get('/select-jenis', [PengajuanDanaController::class, 'selectJenis'])->name('select-jenis');
        Route::get('/create', [PengajuanDanaController::class, 'create'])->name('create');
        Route::post('/', [PengajuanDanaController::class, 'store'])->name('store');
        Route::get('/{pengajuanDana}', [PengajuanDanaController::class, 'show'])->name('show');
        Route::get('/{pengajuanDana}/edit', [PengajuanDanaController::class, 'edit'])->name('edit');
        Route::put('/{pengajuanDana}', [PengajuanDanaController::class, 'update'])->name('update');
        Route::delete('/{pengajuanDana}', [PengajuanDanaController::class, 'destroy'])->name('destroy');
        Route::post('/{pengajuanDana}/submit', [PengajuanDanaController::class, 'submit'])->name('submit');
        Route::post('/{pengajuanDana}/cancel', [PengajuanDanaController::class, 'cancel'])->name('cancel');
        Route::get('/api/statistics', [PengajuanDanaController::class, 'statistics'])->name('statistics');
        Route::get('/{pengajuanDana}/print', [PengajuanDanaController::class, 'print'])->name('print');

        // Detail Pengajuan Routes (nested)
        Route::prefix('{pengajuanDana}/details')->name('details.')->group(function () {
            Route::get('/', [DetailPengajuanController::class, 'index'])->name('index');
            Route::get('/create', [DetailPengajuanController::class, 'create'])->name('create');
            Route::post('/', [DetailPengajuanController::class, 'store'])->name('store');
            Route::post('/bulk', [DetailPengajuanController::class, 'bulkStore'])->name('bulk-store');
            Route::get('/{detailPengajuan}', [DetailPengajuanController::class, 'show'])->name('show');
            Route::get('/{detailPengajuan}/edit', [DetailPengajuanController::class, 'edit'])->name('edit');
            Route::put('/{detailPengajuan}', [DetailPengajuanController::class, 'update'])->name('update');
            Route::delete('/{detailPengajuan}', [DetailPengajuanController::class, 'destroy'])->name('destroy');
            Route::delete('/bulk', [DetailPengajuanController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/statistics', [DetailPengajuanController::class, 'statistics'])->name('statistics');
        });
    });

    // ============================================================
    // PENCAIRAN DANA ROUTES
    // ============================================================
    Route::prefix('pencairan-dana')->name('pencairan-dana.')->group(function () {
        Route::get('/', [PencairanDanaController::class, 'index'])->name('index');
        Route::get('/create', [PencairanDanaController::class, 'create'])->name('create');
        Route::post('/', [PencairanDanaController::class, 'store'])->name('store');
        Route::get('/{pencairanDana}', [PencairanDanaController::class, 'show'])->name('show');
        Route::get('/{pencairanDana}/edit', [PencairanDanaController::class, 'edit'])->name('edit');
        Route::put('/{pencairanDana}', [PencairanDanaController::class, 'update'])->name('update');
        Route::delete('/{pencairanDana}', [PencairanDanaController::class, 'destroy'])->name('destroy');
        Route::post('/{pencairanDana}/submit', [PencairanDanaController::class, 'submit'])->name('submit');
        Route::post('/{pencairanDana}/process', [PencairanDanaController::class, 'process'])->name('process');
        Route::post('/{pencairanDana}/verify', [PencairanDanaController::class, 'verify'])->name('verify');
        Route::post('/{pencairanDana}/cancel', [PencairanDanaController::class, 'cancel'])->name('cancel');
        Route::get('/api/statistics', [PencairanDanaController::class, 'statistics'])->name('statistics');
        Route::get('/{pencairanDana}/print', [PencairanDanaController::class, 'print'])->name('print');
    });

    // ============================================================
    // LPJ ROUTES
    // ============================================================
    Route::prefix('lpj')->name('lpj.')->group(function () {
        Route::get('/', [LpjController::class, 'index'])->name('index');
        Route::get('/create', [LpjController::class, 'create'])->name('create');
        Route::post('/', [LpjController::class, 'store'])->name('store');
        Route::get('/{lpj}', [LpjController::class, 'show'])->name('show');
        Route::get('/{lpj}/edit', [LpjController::class, 'edit'])->name('edit');
        Route::put('/{lpj}', [LpjController::class, 'update'])->name('update');
        Route::delete('/{lpj}', [LpjController::class, 'destroy'])->name('destroy');
        Route::post('/{lpj}/submit', [LpjController::class, 'submit'])->name('submit');
        Route::post('/{lpj}/verify', [LpjController::class, 'verify'])->name('verify');
        Route::post('/{lpj}/approve', [LpjController::class, 'approve'])->name('approve');
        Route::get('/api/statistics', [LpjController::class, 'statistics'])->name('statistics');
    });

    // ============================================================
    // REFUND ROUTES
    // ============================================================
    Route::prefix('refund')->name('refund.')->group(function () {
        Route::get('/', [RefundController::class, 'index'])->name('index');
        Route::get('/create', [RefundController::class, 'create'])->name('create');
        Route::post('/', [RefundController::class, 'store'])->name('store');
        Route::get('/{refund}', [RefundController::class, 'show'])->name('show');
        Route::get('/{refund}/edit', [RefundController::class, 'edit'])->name('edit');
        Route::put('/{refund}', [RefundController::class, 'update'])->name('update');
        Route::delete('/{refund}', [RefundController::class, 'destroy'])->name('destroy');
        Route::post('/{refund}/submit', [RefundController::class, 'submit'])->name('submit');
        Route::post('/{refund}/approve', [RefundController::class, 'approve'])->name('approve');
        Route::post('/{refund}/process', [RefundController::class, 'process'])->name('process');
        Route::get('/api/statistics', [RefundController::class, 'statistics'])->name('statistics');
    });

    // ============================================================
    // APPROVAL ROUTES
    // ============================================================
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/history', [ApprovalController::class, 'history'])->name('history');
        Route::get('/{approval}', [ApprovalController::class, 'show'])->name('show');
        Route::post('/{approval}/process', [ApprovalController::class, 'process'])->name('process');
        Route::post('/bulk-process', [ApprovalController::class, 'bulkProcess'])->name('bulk-process');
        Route::get('/{approval}/print', [ApprovalController::class, 'print'])->name('print');
        Route::get('/api/statistics', [ApprovalController::class, 'statistics'])->name('statistics');
        Route::get('/api/pending-count', [ApprovalController::class, 'pendingCount'])->name('pending-count');
    });

    // ============================================================
    // REPORT ROUTES
    // ============================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/pengajuan', [ReportController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/pencairan', [ReportController::class, 'pencairan'])->name('pencairan');
        Route::get('/lpj', [ReportController::class, 'lpj'])->name('lpj');
        Route::get('/refund', [ReportController::class, 'refund'])->name('refund');
        Route::get('/budget-realization', [ReportController::class, 'budgetRealization'])->name('budget-realization');
        Route::get('/executive-summary', [ReportController::class, 'executiveSummary'])->name('executive-summary');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });

    // ============================================================
    // NOTIFICATION ROUTES
    // ============================================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/recent', [NotificationController::class, 'recent'])->name('recent');
        Route::get('/statistics', [NotificationController::class, 'statistics'])->name('statistics');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
        Route::post('/bulk', [NotificationController::class, 'bulkStore'])->name('bulk-store');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/{notification}/mark-unread', [NotificationController::class, 'markAsUnread'])->name('mark-unread');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/mark-all-unread', [NotificationController::class, 'markAllAsUnread'])->name('mark-all-unread');
        Route::delete('/read', [NotificationController::class, 'destroyRead'])->name('destroy-read');
        Route::delete('/all', [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // ============================================================
    // ADMIN ROUTES (Superadmin & Direktur Utama only)
    // ============================================================
    Route::middleware('role:superadmin,direktur_utama')->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');

            // Job Positions Management
            Route::get('/{user}/job-positions', [UserController::class, 'jobPositions'])->name('job-positions');
            Route::post('/{user}/job-positions', [UserController::class, 'assignJobPosition'])->name('job-positions.assign');
            Route::delete('/{user}/job-positions/{userJobPosition}', [UserController::class, 'removeJobPosition'])->name('job-positions.remove');
            Route::post('/{user}/job-positions/{userJobPosition}/set-primary', [UserController::class, 'setPrimaryJobPosition'])->name('job-positions.set-primary');
        });

        // Role Management
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])->name('show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });

        // Divisi Management
        Route::prefix('divisi')->name('divisi.')->group(function () {
            Route::get('/', [DivisiController::class, 'index'])->name('index');
            Route::get('/create', [DivisiController::class, 'create'])->name('create');
            Route::post('/', [DivisiController::class, 'store'])->name('store');
            Route::get('/{divisi}', [DivisiController::class, 'show'])->name('show');
            Route::get('/{divisi}/edit', [DivisiController::class, 'edit'])->name('edit');
            Route::put('/{divisi}', [DivisiController::class, 'update'])->name('update');
            Route::delete('/{divisi}', [DivisiController::class, 'destroy'])->name('destroy');

            // Job Positions Management
            Route::get('/{divisi}/job-positions', [DivisiController::class, 'jobPositions'])->name('job-positions');
            Route::post('/{divisi}/job-positions', [DivisiController::class, 'storeJobPosition'])->name('job-positions.store');
            Route::put('/{divisi}/job-positions/{jobPosition}', [DivisiController::class, 'updateJobPosition'])->name('job-positions.update');
            Route::delete('/{divisi}/job-positions/{jobPosition}', [DivisiController::class, 'destroyJobPosition'])->name('job-positions.destroy');
        });

        // Vendor Management
        Route::prefix('vendors')->name('vendors.')->group(function () {
            Route::get('/', [VendorController::class, 'index'])->name('index');
            Route::get('/create', [VendorController::class, 'create'])->name('create');
            Route::post('/', [VendorController::class, 'store'])->name('store');
            Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
            Route::get('/{vendor}/edit', [VendorController::class, 'edit'])->name('edit');
            Route::put('/{vendor}', [VendorController::class, 'update'])->name('update');
            Route::delete('/{vendor}', [VendorController::class, 'destroy'])->name('destroy');
            Route::post('/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/api/options', [VendorController::class, 'options'])->name('options');
            Route::get('/api/statistics', [VendorController::class, 'statistics'])->name('statistics');
            Route::get('/export', [VendorController::class, 'export'])->name('export');
        });

        // Approval Config Management
        Route::prefix('approval-configs')->name('approval-configs.')->group(function () {
            Route::get('/', [ApprovalConfigController::class, 'index'])->name('index');
            Route::get('/create', [ApprovalConfigController::class, 'create'])->name('create');
            Route::post('/', [ApprovalConfigController::class, 'store'])->name('store');
            Route::get('/{approvalConfig}', [ApprovalConfigController::class, 'show'])->name('show');
            Route::get('/{approvalConfig}/edit', [ApprovalConfigController::class, 'edit'])->name('edit');
            Route::put('/{approvalConfig}', [ApprovalConfigController::class, 'update'])->name('update');
            Route::delete('/{approvalConfig}', [ApprovalConfigController::class, 'destroy'])->name('destroy');
            Route::post('/{approvalConfig}/toggle-status', [ApprovalConfigController::class, 'toggleStatus'])->name('toggle-status');
        });
    });
});

require __DIR__.'/auth.php';
