<x-mail::message>
    <h2>LPJ Baru Submitted</h2>

    <div class="info-box">
        <strong>Info:</strong> LPJ baru telah disubmit dan memerlukan verifikasi.
    </div>

    <p>Halo Staff Keuangan,</p>

    <p>LPJ baru telah disubmit dan memerlukan verifikasi Anda:</p>

    <div class="info-box">
        <table>
            <tr>
                <td>Nomor LPJ</td>
                <td>{{ $lpj_nomor }}</td>
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
                <td>Total Digunakan</td>
                <td><strong>Rp {{ number_format($lpj_total_digunakan, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Sisa Dana</td>
                <td><strong>Rp {{ number_format($lpj_sisa, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Submit</td>
                <td>{{ $submitted_at ? $submitted_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    <p>Silakan login ke sistem untuk memverifikasi LPJ.</p>

    <p>
        <a href="{{ $lpj_url }}" class="button">Verifikasi LPJ</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
