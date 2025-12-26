<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('formatTanggal')) {
    function formatTanggal($date)
    {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->translatedFormat('d F Y');
    }
}

if (!function_exists('formatTanggalWaktu')) {
    function formatTanggalWaktu($date)
    {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->translatedFormat('d F Y H:i');
    }
}

if (!function_exists('statusBadge')) {
    function statusBadge($status)
    {
        $badges = [
            'draft' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">Draft</span>',
            'proposed' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Diajukan</span>',
            'approved' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Disetujui</span>',
            'rejected' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Ditolak</span>',
            'pending' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Pending</span>',
            'completed' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Selesai</span>',
            'menunggu_approval' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Menunggu Approval</span>',
            'disetujui' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Disetujui</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Ditolak</span>',
        ];

        return $badges[$status] ?? '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        if ($bytes === null) return '-';
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
