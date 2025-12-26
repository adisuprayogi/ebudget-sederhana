<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Daftar Approval</h1>
                <p class="text-secondary-600 mt-1">Kelola persetujuan pengajuan dana yang membutuhkan tindakan Anda</p>
            </div>
            <a href="{{ route('approvals.history') }}" class="inline-flex items-center px-4 py-2 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Approval
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Menunggu Approval</div>
                        <div class="text-2xl font-bold text-amber-600">{{ $statistics['pending'] ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Disetujui</div>
                        <div class="text-2xl font-bold text-green-600">{{ $statistics['approved'] ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Ditolak</div>
                        <div class="text-2xl font-bold text-red-600">{{ $statistics['rejected'] ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Rate Persetujuan</div>
                        <div class="text-2xl font-bold text-primary-600">{{ number_format($statistics['approval_rate'] ?? 0, 1) }}%</div>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('approvals.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Level</label>
                    <select name="level" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Level</option>
                        @foreach($filterOptions['levels'] ?? [] as $level)
                            <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>Level {{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Divisi</label>
                    <select name="divisi_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Divisi</option>
                        @foreach($filterOptions['divisis'] ?? [] as $divisi)
                            <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor atau judul..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Approval List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50 border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($approvals ?? [] as $approval)
                            <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="approval_ids[]" value="{{ $approval->id }}" class="approval-checkbox rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-primary-600">{{ $approval->pengajuanDana->nomor_pengajuan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-secondary-900">{{ $approval->pengajuanDana->judul_pengajuan }}</div>
                                    @if($approval->pengajuanDana->program_kerja)
                                        <div class="text-sm text-secondary-500">{{ $approval->pengajuanDana->programKerja->nama_program }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-secondary-700">{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-secondary-900">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Level {{ $approval->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600">
                                    {{ \Carbon\Carbon::parse($approval->created_at)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('approvals.show', $approval) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-secondary-500">Tidak ada approval yang menunggu</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Actions -->
            @if(isset($approvals) && $approvals->count() > 0)
            <div id="bulk-actions" class="hidden bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-secondary-700"><span id="selected-count">0</span> item dipilih</span>
                    <div class="flex space-x-2">
                        <button onclick="bulkApprove()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Setujui Semua
                        </button>
                        <button onclick="bulkReject()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak Semua
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pagination -->
            @if(isset($approvals) && $approvals->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $approvals->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Select all functionality
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
