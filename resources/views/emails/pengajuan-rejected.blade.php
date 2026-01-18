<x-mail::message>
    <h2>Pengajuan Dana Ditolak</h2>

    <div class="info-box error">
        <strong>Mohon Maaf.</strong> Pengajuan dana Anda ditolak.
    </div>

    <p>Halo <strong>{{ $pengaju_name }}</strong>,</p>

    <p>Pengajuan dana Anda telah ditolak:</p>

    <div class="info-box">
        <table>
            <tr>
                <td>Nomor Pengajuan</td>
                <td>{{ $pengajuan_nomor }}</td>
            </tr>
            <tr>
                <td>Judul</td>
                <td>{{ $pengajuan_judul }}</td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>{{ ucfirst(str_replace('_', ' ', $pengajuan_jenis)) }}</td>
            </tr>
            <tr>
                <td>Total Pengajuan</td>
                <td><strong>Rp {{ number_format($pengajuan_total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Ditolak Oleh</td>
                <td>{{ $approver_name }} ({{ ucfirst(str_replace('_', ' ', $approval_level)) }})</td>
            </tr>
            <tr>
                <td>Tanggal Ditolak</td>
                <td>{{ $rejected_at ? $rejected_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    @if($rejection_notes)
    <div class="info-box warning">
        <strong>Alasan Penolakan:</strong><br>
        {{ $rejection_notes }}
    </div>
    @endif

    <p>Silakan periksa kembali pengajuan Anda atau buat pengajuan baru jika diperlukan.</p>

    <p>
        <a href="{{ $pengajuan_url }}" class="button">Lihat Detail Pengajuan</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
