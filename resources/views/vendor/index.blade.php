<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Manajemen Vendor</h1>
                <p class="text-secondary-600 mt-1">Kelola daftar vendor dan mitra kerja</p>
            </div>
            <a href="{{ route('vendors.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Vendor Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <form method="GET" action="{{ route('vendors.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Kode, nama, NPWP..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        @foreach($filterOptions['statuses'] ?? [] as $status)
                            <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Jenis Vendor</label>
                    <select name="jenis_vendor" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="supplier" {{ ($filters['jenis_vendor'] ?? '') === 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="kontraktor" {{ ($filters['jenis_vendor'] ?? '') === 'kontraktor' ? 'selected' : '' }}>Kontraktor</option>
                        <option value="konsultan" {{ ($filters['jenis_vendor'] ?? '') === 'konsultan' ? 'selected' : '' }}>Konsultan</option>
                        <option value="lainnya" {{ ($filters['jenis_vendor'] ?? '') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Kota</label>
                    <select name="kota" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Kota</option>
                        @foreach($filterOptions['kotas'] ?? [] as $kota)
                            <option value="{{ $kota }}" {{ ($filters['kota'] ?? '') === $kota ? 'selected' : '' }}>{{ $kota }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Vendors List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50 border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nama Vendor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($vendors ?? [] as $vendor)
                            <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-primary-600">{{ $vendor->kode_vendor }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-secondary-900">{{ $vendor->nama_vendor }}</div>
                                    @if($vendor->npwp)
                                        <div class="text-sm text-secondary-500">NPWP: {{ $vendor->npwp }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                        {{ ucfirst($vendor->jenis_vendor) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-secondary-700">{{ $vendor->kota ?? '-' }}</div>
                                    @if($vendor->propinsi)
                                        <div class="text-xs text-secondary-500">{{ $vendor->propinsi }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-secondary-700">{{ $vendor->telepon ?? '-' }}</div>
                                    @if($vendor->kontak_person)
                                        <div class="text-xs text-secondary-500">{{ $vendor->kontak_person }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $vendor->rating ? 'text-amber-400' : 'text-secondary-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($vendor->status === 'active')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                                    @elseif($vendor->status === 'inactive')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">Non-Aktif</span>
                                    @elseif($vendor->status === 'blacklisted')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Blacklist</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('vendors.show', $vendor) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('vendors.edit', $vendor) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-secondary-500">Belum ada vendor</p>
                                        <a href="{{ route('vendors.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                            Tambah Vendor Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($vendors) && $vendors->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $vendors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
