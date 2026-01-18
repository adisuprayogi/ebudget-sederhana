<x-mail::message>
    <h2>Reminder: LPJ Belum Dibuat</h2>

    <div class="info-box warning">
        <strong>Pengingat:</strong> Anda belum membuat LPJ untuk pengajuan dana yang sudah dicairkan.
    </div>

    <p>Halo <strong>{{ $pengaju_name }}</strong>,</p>

    <p>Ini adalah pengingat bahwa Anda belum membuat Laporan Pertanggungjawaban (LPJ) untuk:</p>

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
                <td>Total Pengajuan</td>
                <td><strong>Rp {{ number_format($pengajuan_total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Pencairan</td>
                <td>{{ $pencairan_date ? $pencairan_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Hari Pencairan</td>
                <td><strong>{{ $days_since_pencairan }} hari</strong></td>
            </tr>
        </table>
    </div>

    <p>Silakan segera membuat LPJ untuk memenuhi kewajiban pelaporan.</p>

    <p>
        <a href="{{ $lpj_url }}" class="button">Buat LPJ Sekarang</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
