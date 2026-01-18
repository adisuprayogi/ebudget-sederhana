<x-mail::message>
    <h2>Daily Summary - {{ $summary_date }}</h2>

    <p>Halo <strong>{{ $recipient_name }}</strong>,</p>

    <p>Berikut adalah ringkasan aktivitas harian {{ config('app.name') }} untuk tanggal <strong>{{ $summary_date }}</strong>:</p>

    <div class="info-box">
        <table>
            <tr>
                <td>Pengajuan Baru</td>
                <td><strong>{{ $pengajuan_count }}</strong> pengajuan</td>
            </tr>
            <tr>
                <td>Disetujui</td>
                <td><strong>{{ $approved_count }}</strong> pengajuan</td>
            </tr>
            <tr>
                <td>Ditolak</td>
                <td><strong>{{ $rejected_count }}</strong> pengajuan</td>
            </tr>
            <tr>
                <td>Pencairan Diproses</td>
                <td><strong>{{ $pencairan_count }}</strong> pencairan</td>
            </tr>
            <tr>
                <td>Total Nilai Pengajuan</td>
                <td><strong>Rp {{ number_format($total_pengajuan, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Total Nilai Pencairan</td>
                <td><strong>Rp {{ number_format($total_pencairan, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <p>Login ke dashboard untuk melihat detail lebih lanjut.</p>

    <p>
        <a href="{{ $dashboard_url }}" class="button">Lihat Dashboard</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
