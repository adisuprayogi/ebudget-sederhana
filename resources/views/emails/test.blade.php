<x-mail::message>
    <h2>Test Email</h2>

    <p>Halo,</p>

    <p>Ini adalah email test dari {{ $app_name }}.</p>

    <div class="info-box">
        <table>
            <tr>
                <td>Waktu Test</td>
                <td>{{ $test_time }}</td>
            </tr>
            <tr>
                <td>Nama Aplikasi</td>
                <td>{{ $app_name }}</td>
            </tr>
            <tr>
                <td>URL Aplikasi</td>
                <td>{{ $app_url }}</td>
            </tr>
        </table>
    </div>

    <p>Jika Anda menerima email ini, konfigurasi email sudah berjalan dengan baik.</p>

    <p>
        <a href="{{ $app_url }}" class="button">Login ke Aplikasi</a>
    </p>

    <p>Terima kasih.</p>
</x-mail::message>
