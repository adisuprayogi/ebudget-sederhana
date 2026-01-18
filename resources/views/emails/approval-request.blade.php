<x-mail::message>
    <h2>Pengajuan Dana Menunggu Approval</h2>

    <p>Halo <strong>{{ $approver_name }}</strong>,</p>

    <p>Ada pengajuan dana baru yang membutuhkan persetujuan Anda:</p>

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
                <td>Pengaju</td>
                <td>{{ $pengaju_name }}</td>
            </tr>
            <tr>
                <td>Level Approval</td>
                <td>{{ ucfirst(str_replace('_', ' ', $approval_level)) }}</td>
            </tr>
        </table>
    </div>

    <p>Silakan login ke sistem untuk memeriksa dan memberikan persetujuan.</p>

    <p>
        <a href="{{ $approval_url }}" class="button">Lihat Detail Approval</a>
    </p>

    <p>Terima kasih atas perhatian Anda.</p>
</x-mail::message>
