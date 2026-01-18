<x-mail::message>
    <h2>Pengajuan Siap Dicairkan</h2>

    <div class="info-box">
        <strong>Info:</strong> Pengajuan dana baru yang sudah disetujui penuh dan siap untuk dicairkan.
    </div>

    <p>Halo Staff Keuangan,</p>

    <p>Ada pengajuan dana yang telah disetujui penuh dan siap untuk diproses pencairan:</p>

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

    @if($penerima_manfaat)
    <div class="info-box">
        <strong>Penerima Manfaat:</strong><br>
        {!! $penerima_manfaat !!}
    </div>
    @endif

    <p>Silakan login ke sistem untuk memproses pencairan dana.</p>

    <p>
        <a href="{{ $pencairan_url }}" class="button">Proses Pencairan</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
