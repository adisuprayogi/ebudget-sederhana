<x-mail::message>
    <h2>Pencairan Dana Diproses</h2>

    <div class="info-box success">
        <strong>Info:</strong> Pencairan dana untuk pengajuan Anda telah diproses.
    </div>

    <p>Halo <strong>{{ $pengaju_name }}</strong>,</p>

    <p>Pencairan dana untuk pengajuan Anda telah diproses:</p>

    <div class="info-box">
        <table>
            <tr>
                <td>Nomor Pencairan</td>
                <td>{{ $pencairan_nomor }}</td>
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
                <td>Total Pencairan</td>
                <td><strong>Rp {{ number_format($pencairan_total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Pencairan</td>
                <td>{{ $pencairan_tanggal ? $pencairan_tanggal->format('d/m/Y') : '-' }}</td>
            </tr>
        </table>
    </div>

    @if($penerima_manfaat)
    <div class="info-box">
        <strong>Dana dicairkan ke:</strong><br>
        {!! $penerima_manfaat !!}
    </div>
    @endif

    <p>Harap segera membuat LPJ (Laporan Pertanggungjawaban) setelah penggunaan dana selesai.</p>

    <p>
        <a href="{{ $pencairan_url }}" class="button">Lihat Detail Pencairan</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
