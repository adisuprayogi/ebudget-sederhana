<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\PengajuanDana;
use App\Models\PencairanDana;
use App\Models\LaporanPertanggungJawaban;
use App\Models\Refund;

class NumberingService
{
    /**
     * Generate nomor pengajuan dengan format RF+YYMM+5digit
     * Reset setiap bulan
     */
    public static function generateNomorPengajuan()
    {
        $prefix = 'RF' . Carbon::now()->format('ym');

        // Ambil nomor terakhir di bulan yang sama
        $lastNomor = PengajuanDana::where('nomor_pengajuan', 'like', $prefix . '%')
            ->orderBy('nomor_pengajuan', 'desc')
            ->value('nomor_pengajuan');

        if ($lastNomor) {
            // Extract 5 digit nomor urut
            $lastNumber = substr($lastNomor, -5);
            $nextNumber = str_pad((int)$lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Generate nomor pencairan dengan format DF+YYMM+5digit
     * Reset setiap bulan
     */
    public static function generateNomorPencairan()
    {
        $prefix = 'DF' . Carbon::now()->format('ym');

        // Ambil nomor terakhir di bulan yang sama
        $lastNomor = PencairanDana::where('nomor_pencairan', 'like', $prefix . '%')
            ->orderBy('nomor_pencairan', 'desc')
            ->value('nomor_pencairan');

        if ($lastNomor) {
            // Extract 5 digit nomor urut
            $lastNumber = substr($lastNomor, -5);
            $nextNumber = str_pad((int)$lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Generate nomor LPJ dengan format RF+YYMM+5digit
     * Reset setiap bulan
     */
    public static function generateNomorLPJ()
    {
        $prefix = 'RF' . Carbon::now()->format('ym');

        // Ambil nomor terakhir di bulan yang sama
        $lastNomor = LaporanPertanggungJawaban::where('nomor_lpj', 'like', $prefix . '%')
            ->orderBy('nomor_lpj', 'desc')
            ->value('nomor_lpj');

        if ($lastNomor) {
            // Extract 5 digit nomor urut
            $lastNumber = substr($lastNomor, -5);
            $nextNumber = str_pad((int)$lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Generate nomor refund dengan format FR+YYMM+5digit
     * Reset setiap bulan
     */
    public static function generateNomorRefund()
    {
        $prefix = 'FR' . Carbon::now()->format('ym');

        // Ambil nomor terakhir di bulan yang sama
        $lastNomor = Refund::where('nomor_refund', 'like', $prefix . '%')
            ->orderBy('nomor_refund', 'desc')
            ->value('nomor_refund');

        if ($lastNomor) {
            // Extract 5 digit nomor urut
            $lastNumber = substr($lastNomor, -5);
            $nextNumber = str_pad((int)$lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Format nomor untuk display
     */
    public static function formatNomor($nomor)
    {
        if (!$nomor) {
            return '-';
        }

        // Format: RF25120001 -> RF 2512 0001
        if (strlen($nomor) === 10) {
            return substr($nomor, 0, 2) . ' ' . substr($nomor, 2, 4) . ' ' . substr($nomor, 6);
        }

        return $nomor;
    }

    /**
     * Extract information dari nomor
     */
    public static function extractNomorInfo($nomor)
    {
        if (!$nomor || strlen($nomor) !== 10) {
            return null;
        }

        $prefix = substr($nomor, 0, 2);
        $yearMonth = substr($nomor, 2, 4);
        $sequence = substr($nomor, 6);

        try {
            $year = '20' . substr($yearMonth, 0, 2);
            $month = substr($yearMonth, 2, 2);
            $date = Carbon::create($year, $month - 1, 1);

            return [
                'prefix' => $prefix,
                'year_month' => $yearMonth,
                'sequence' => $sequence,
                'year' => $year,
                'month' => $month,
                'formatted_date' => $date->format('F Y'),
                'type' => self::getJenisDokumen($prefix)
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get jenis dokumen dari prefix
     */
    private static function getJenisDokumen($prefix)
    {
        $types = [
            'RF' => 'Pengajuan Dana',
            'DF' => 'Pencairan Dana',
            'FR' => 'Refund',
        ];

        return $types[$prefix] ?? 'Unknown';
    }

    /**
     * Validate format nomor
     */
    public static function validateNomor($nomor, $jenis = 'pengajuan')
    {
        if (!$nomor) {
            return false;
        }

        $expectedPrefix = match($jenis) {
            'pengajuan' => 'RF',
            'pencairan' => 'DF',
            'lpj' => 'RF',
            'refund' => 'FR',
            default => null
        };

        if (!$expectedPrefix) {
            return false;
        }

        // Check format: prefix + YYMM + 5 digit
        return preg_match('/^' . $expectedPrefix . '\d{2}\d{5}$/', $nomor);
    }
}