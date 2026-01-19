<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Daftar Approval</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $approvals->total() ?? 0 }} pengajuan menunggu approval</p>
            </div>
            <a href="{{ route('approvals.history') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-gray-700 hover:bg-blue-50 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat
            </a>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['pending'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menunggu</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['approved'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Disetujui</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['rejected'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Ditolak</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ number_format($statistics['approval_rate'] ?? 0, 1) }}%</p>
                    <p class="text-xs text-gray-500">Rate Approval</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('approvals.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau judul..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
            </div>
            <div class="min-w-[140px]">
                <select name="level" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Level</option>
                    @foreach($filterOptions['levels'] ?? [] as $level)
                        <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>Level {{ $level }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <select name="divisi_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach($filterOptions['divisis'] ?? [] as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if(request()->hasAny(['search', 'level', 'divisi_id']))
                <a href="{{ route('approvals.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-blue-50 border-b border-blue-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase w-10">
                        <input type="checkbox" id="select-all" class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Judul</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jenis</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengaju</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Total</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Level</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($approvals ?? [] as $approval)
                    @php
                        $jenisLabels = [
                            'kegiatan' => 'Kegiatan',
                            'pengadaan' => 'Pengadaan',
                            'pembayaran' => 'Pembayaran',
                            'honorarium' => 'Honorarium',
                            'sewa' => 'Sewa',
                            'konsumsi' => 'Konsumsi',
                            'konsumi' => 'Konsumsi',
                            'reimbursement' => 'Reimbursement',
                            'lainnya' => 'Lainnya',
                        ];
                        $jenisColors = [
                            'kegiatan' => 'bg-blue-100 text-blue-700',
                            'pengadaan' => 'bg-emerald-100 text-emerald-700',
                            'pembayaran' => 'bg-amber-100 text-amber-700',
                            'honorarium' => 'bg-violet-100 text-violet-700',
                            'sewa' => 'bg-orange-100 text-orange-700',
                            'konsumsi' => 'bg-pink-100 text-pink-700',
                            'konsumi' => 'bg-pink-100 text-pink-700',
                            'reimbursement' => 'bg-cyan-100 text-cyan-700',
                            'lainnya' => 'bg-gray-100 text-gray-700',
                        ];
                        $jenis = $approval->pengajuanDana->jenis_pengajuan;
                        $label = $jenisLabels[$jenis] ?? ucfirst($jenis);
                        $colorClass = $jenisColors[$jenis] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <input type="checkbox" name="approval_ids[]" value="{{ $approval->id }}" class="approval-checkbox rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-semibold rounded bg-blue-100 text-blue-700">
                                {{ $approval->pengajuanDana->nomor_pengajuan }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-900 text-sm">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                            @if($approval->pengajuanDana->program_kerja)
                                <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $approval->pengajuanDana->programKerja->nama_program }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded {{ $colorClass }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($approval->pengajuanDana->createdBy->name ?? '-', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm text-gray-700">{{ $approval->pengajuanDana->createdBy->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700">
                                {{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-mono font-semibold text-gray-900 text-sm">Rp {{ number_format($approval->pengajuanDana->total_pengajuan, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-sm font-bold text-blue-700">
                                {{ $approval->level }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($approval->created_at)->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('approvals.show', $approval) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-medium">Tidak ada approval</p>
                                <p class="text-gray-400 text-sm mt-1">Tidak ada pengajuan yang menunggu approval</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bulk Actions -->
        @if(isset($approvals) && $approvals->count() > 0)
        <div id="bulk-actions" class="hidden bg-blue-50/50 px-4 py-3 border-t border-blue-100">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600"><span id="selected-count">0</span> item dipilih</span>
                <div class="flex items-center gap-2">
                    <button onclick="bulkApprove()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Setujui
                    </button>
                    <button onclick="bulkReject()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($approvals) && $approvals->hasPages())
        <div class="mt-4 flex items-center justify-between text-sm">
            <span class="text-gray-500">
                Menampilkan {{ $approvals->firstItem() ?? 0 }}–{{ $approvals->lastItem() ?? 0 }} dari {{ $approvals->total() }}
            </span>
            {{ $approvals->appends(request()->query())->links() }}
        </div>
    @endif

    <script>
        const selectAllCheckbox = document.getElementById('select-all');
        const approvalCheckboxes = document.querySelectorAll('.approval-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkActions() {
            const selected = document.querySelectorAll('.approval-checkbox:checked');
            if (selected.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = selected.length;
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        selectAllCheckbox?.addEventListener('change', function() {
            approvalCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        approvalCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function getSelectedIds() {
            const selected = document.querySelectorAll('.approval-checkbox:checked');
            return Array.from(selected).map(cb => cb.value);
        }

        function bulkApprove() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;

            if (confirm(`Anda yakin ingin menyetujui ${ids.length} pengajuan?`)) {
                processBulkAction(ids, 'disetujui');
            }
        }

        function bulkReject() {
            const ids = getSelectedIds();
            if (ids.length === 0) return;

            const notes = prompt('Alasan penolakan (opsional):');
            if (notes !== null) {
                processBulkAction(ids, 'ditolak', notes);
            }
        }

        function processBulkAction(ids, action, notes = '') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('approvals.bulk-process') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'approval_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            if (notes) {
                const notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'notes';
                notesInput.value = notes;
                form.appendChild(notesInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-app-layout>
