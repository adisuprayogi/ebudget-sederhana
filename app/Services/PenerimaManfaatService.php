<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use App\Models\PicKegiatan;
use App\Models\PenerimaManfaatLainnya;

class PenerimaManfaatService
{
    /**
     * Get penerima manfaat options based on jenis pengajuan
     */
    public static function getPenerimaManfaatOptions($jenisPengajuan)
    {
        $options = [];

        switch ($jenisPengajuan) {
            case 'kegiatan':
                $options['pic_kegiatan'] = self::getPicKegiatanOptions();
                break;

            case 'pengadaan':
                $options['pengaju'] = 'Pengaju (Pembuat Pengajuan)';
                break;

            case 'pembayaran':
                $options['pegawai'] = self::getUserOptions();
                $options['internal'] = 'Internal User';
                $options['external'] = 'External User';
                break;

            case 'honorarium':
                $options['pegawai'] = self::getUserOptions();
                $options['non_pegawai'] = 'Non Pegawai';
                break;

            case 'sewa':
                $options['vendor'] = self::getVendorOptions();
                $options['non_pegawai'] = 'Non Pegawai';
                break;

            case 'konsumsi':
                $options['pegawai'] = self::getUserOptions();
                $options['non_pegawai'] = 'Non Pegawai';
                break;

            case 'lainnya':
                $options['pegawai'] = self::getUserOptions();
                $options['non_pegawai'] = 'Non Pegawai';
                $options['vendor'] = self::getVendorOptions();
                $options['internal'] = 'Internal User';
                $options['external'] = 'External User';
                break;

            default:
                $options['pegawai'] = self::getUserOptions();
                $options['non_pegawai'] = 'Non Pegawai';
                break;
        }

        return $options;
    }

    /**
     * Get user options for select dropdown
     */
    private static function getUserOptions()
    {
        return User::where('is_active', true)
            ->whereHas('role', function ($query) {
                $query->whereIn('name', ['kepala_divisi', 'staff_divisi', 'direktur_keuangan', 'direktur_utama']);
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->full_name,
                    'email' => $user->email,
                    'divisi' => $user->divisi->nama_divisi ?? 'Tidak ada divisi',
                    'role' => $user->role->name
                ];
            })
            ->toArray();
    }

    /**
     * Get vendor options for select dropdown
     */
    private static function getVendorOptions()
    {
        return Vendor::where('is_active', true)
            ->get()
            ->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'text' => $vendor->nama_vendor,
                    'email' => $vendor->email,
                    'telepon' => $vendor->telepon
                ];
            })
            ->toArray();
    }

    /**
     * Get PIC Kegiatan options for select dropdown
     */
    private static function getPicKegiatanOptions()
    {
        return PicKegiatan::where('is_active', true)
            ->get()
            ->map(function ($pic) {
                return [
                    'id' => $pic->id,
                    'text' => $pic->nama_pic,
                    'email' => $pic->email,
                    'telepon' => $pic->telepon,
                    'keahlian' => $pic->keahlian
                ];
            })
            ->toArray();
    }

    /**
     * Validate penerima manfaat selection based on jenis pengajuan
     */
    public static function validatePenerimaManfaat($jenisPengajuan, $penerimaType, $penerimaId = null)
    {
        $validTypes = array_keys(self::getPenerimaManfaatOptions($jenisPengajuan));

        if (!in_array($penerimaType, $validTypes)) {
            return false;
        }

        // Check if ID is required and provided
        if (in_array($penerimaType, ['pegawai', 'vendor', 'pic_kegiatan']) && !$penerimaId) {
            return false;
        }

        return true;
    }

    /**
     * Get penerima manfaat details for display
     */
    public static function getPenerimaManfaatDetails($penerimaType, $penerimaId = null, $penerimaName = null, $penerimaDetail = null)
    {
        switch ($penerimaType) {
            case 'pegawai':
                if ($penerimaId) {
                    $user = User::find($penerimaId);
                    if ($user) {
                        return [
                            'name' => $user->full_name,
                            'email' => $user->email,
                            'divisi' => $user->divisi->nama_divisi ?? null,
                            'role' => $user->role->name ?? null
                        ];
                    }
                }
                break;

            case 'vendor':
                if ($penerimaId) {
                    $vendor = Vendor::find($penerimaId);
                    if ($vendor) {
                        return [
                            'name' => $vendor->nama_vendor,
                            'email' => $vendor->email,
                            'telepon' => $vendor->telepon,
                            'npwp' => $vendor->npwp
                        ];
                    }
                }
                break;

            case 'pic_kegiatan':
                if ($penerimaId) {
                    $pic = PicKegiatan::find($penerimaId);
                    if ($pic) {
                        return [
                            'name' => $pic->nama_pic,
                            'email' => $pic->email,
                            'telepon' => $pic->telepon,
                            'keahlian' => $pic->keahlian
                        ];
                    }
                }
                break;

            case 'pengaju':
                // Pengaju adalah user yang membuat pengajuan, details diambil dari auth user
                return null;

            case 'internal':
            case 'external':
            case 'non_pegawai':
                return [
                    'name' => $penerimaName,
                    'detail' => $penerimaDetail ? json_decode($penerimaDetail, true) : null
                ];
        }

        return null;
    }

    /**
     * Format penerima manfaat for display in views
     */
    public static function formatPenerimaManfaat($pengajuanDana)
    {
        if (!$pengajuanDana) {
            return '-';
        }

        $details = self::getPenerimaManfaatDetails(
            $pengajuanDana->penerima_manfaat_type,
            $pengajuanDana->penerima_manfaat_id,
            $pengajuanDana->penerima_manfaat_name,
            $pengajuanDana->penerima_manfaat_detail
        );

        if ($details) {
            if ($pengajuanDana->penerima_manfaat_type === 'pegawai' && $details['divisi']) {
                return "{$details['name']} ({$details['divisi']})";
            }

            if ($details['telepon']) {
                return "{$details['name']} ({$details['telepon']})";
            }

            return $details['name'];
        }

        return $pengajuanDana->penerima_manfaat_name ?? '-';
    }
}