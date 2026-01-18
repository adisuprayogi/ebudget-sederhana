<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('refund.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Pilih LPJ untuk Refund</h1>
                <p class="text-secondary-600 mt-1">Pilih LPJ yang memiliki sisa dana untuk dilakukan refund</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Form -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <form method="GET" action="{{ route('refund.select-lpj') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor LPJ atau uraian..."
                        class="w-full px-4 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <select name="divisi_id" class="w-full px-4 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Divisi</option>
                        @foreach($divisis ?? \App\Models\Divisi::orderBy('nama_divisi')->get() as $divisi)
                            <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="periode_anggaran_id" class="w-full px-4 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Periode</option>
                        @foreach($periodeAnggarans ?? \App\Models\PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get() as $periode)
                            <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>{{ $periode->nama_periode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('refund.select-lpj') }}" class="px-4 py-2 border border-secondary-200 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- LPJ List -->
        @if($lpjs->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-secondary-50 border-b border-secondary-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor LPJ</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Uraian Kegiatan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Total Pencairan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Digunakan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Sisa Dana</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal Approved</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-100">
                            @foreach($lpjs as $lpj)
                            <tr class="hover:bg-secondary-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-semibold text-primary-600">{{ $lpj->nomor_lpj }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">
                                    @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana)
                                        {{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">Rp {{ number_format($lpj->pencairanDana->jumlah_pencairan ?? 0, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-900">Rp {{ number_format($lpj->total_digunakan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold @if($lpj->sisa_dana > 0) text-blue-600 @else text-red-600 @endif">Rp {{ number_format($lpj->sisa_dana, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->approved_at ? \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('refund.create', ['lpj_id' => $lpj->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Buat Refund
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                    <p class="text-sm text-secondary-600">
                        Menampilkan {{ $lpjs->firstItem() }} sampai {{ $lpjs->lastItem() }} dari {{ $lpjs->total() }} LPJ
                    </p>
                    {{ $lpjs->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada LPJ dengan Sisa Dana</h3>
                <p class="text-secondary-500">Belum ada LPJ yang disetujui dengan sisa dana yang bisa direfund.</p>
            </div>
        @endif
    </div>
</x-app-layout>
