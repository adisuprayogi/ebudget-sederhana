<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Jabatan - {{ $user->full_name }}</h1>
                <p class="text-gray-600 mt-1">Kelola jabatan yang dimiliki user ini</p>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Assign Job Position Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Jabatan ke User</h3>
            <form method="POST" action="{{ route('admin.users.job-positions.assign', $user) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jabatan</label>
                    <select name="job_position_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($allJobPositions as $divisi => $positions)
                            <optgroup label="{{ $divisi }}">
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}">
                                        {{ $position->nama_jabatan }} ({{ $position->kode_jabatan }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="assigned_at" value="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex items-center h-full">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_primary" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Jadikan Utama</span>
                        </label>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tambah
                    </button>
                </div>
            </form>
        </div>

        <!-- User Job Positions List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Jabatan Saat Ini</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Mulai</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($userJobPositions as $userJobPosition)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ $userJobPosition->jobPosition->divisi->nama_divisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $userJobPosition->jobPosition->nama_jabatan }}
                                    @if($userJobPosition->is_primary)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Utama</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ $userJobPosition->jobPosition->kode_jabatan }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($userJobPosition->jobPosition->level == 1) bg-purple-100 text-purple-700
                                    @elseif($userJobPosition->jobPosition->level == 2) bg-blue-100 text-blue-700
                                    @elseif($userJobPosition->jobPosition->level == 3) bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    Level {{ $userJobPosition->jobPosition->level }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $userJobPosition->assigned_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($userJobPosition->is_primary)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Jabatan Utama</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Jabatan Tambahan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if(!$userJobPosition->is_primary)
                                        <form method="POST" action="{{ route('admin.users.job-positions.set-primary', [$user, $userJobPosition]) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-lg" title="Jadikan Utama">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.users.job-positions.remove', [$user, $userJobPosition]) }}" onsubmit="return confirm('Yakin ingin menghapus jabatan ini dari user?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500">User belum memiliki jabatan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
