<x-mail::message>
    <h2>Refund Diproses</h2>

    <div class="info-box success">
        <strong>Info:</strong> Refund Anda telah diproses.
    </div>

    <p>Halo <strong>{{ $pengaju_name }}</strong>,</p>

    <p>Refunt untuk LPJ Anda telah diproses:</p>

    <div class="info-box">
        <table>
            <tr>
                <td>Nomor Refund</td>
                <td>{{ $refund_nomor }}</td>
            </tr>
            <tr>
                <td>Nomor Pengajuan</td>
                <td>{{ $pengajuan_nomor }}</td>
            </tr>
            <tr>
                <td>Judul Pengajuan</td>
                <td>{{ $pengajuan_judul }}</td>
            </tr>
            <tr>
                <td>Nominal Refund</td>
                <td><strong>Rp {{ number_format($refund_nominal, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Jenis Refund</td>
                <td>{{ ucfirst(str_replace('_', ' ', $refund_jenis)) }}</td>
            </tr>
            <tr>
                <td>Tanggal Diproses</td>
                <td>{{ $processed_at ? $processed_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    <p>
        <a href="{{ $refund_url }}" class="button">Lihat Detail Refund</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
