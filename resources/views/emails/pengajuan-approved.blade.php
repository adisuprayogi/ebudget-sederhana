<x-mail::message>
    <h2>Pengajuan Dana Disetujui</h2>

    <div class="info-box success">
        <strong>Selamat!</strong> Pengajuan dana Anda telah disetujui.
    </div>

    <p>Halo <strong>{{ $pengaju_name }}</strong>,</p>

    <p>Pengajuan dana Anda telah disetujui:</p>

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
                <td>Tanggal Disetujui</td>
                <td>{{ $approved_at ? $approved_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    <p>Pengajuan Anda sekarang siap untuk diproses lebih lanjut oleh staff keuangan.</p>

    <p>
        <a href="{{ $pengajuan_url }}" class="button">Lihat Detail Pengajuan</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
