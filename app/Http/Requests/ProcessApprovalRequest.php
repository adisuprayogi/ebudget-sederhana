<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProcessApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $approval = $this->route('approval');
        $user = Auth::user();

        // Check if user is the approver
        if ($approval->approver_id !== $user->id) {
            return false;
        }

        // Check if approval is still pending
        if ($approval->status !== 'pending') {
            return false;
        }

        // Check if user has permission
        return $user->hasPermission('pengajuan_dana.approve');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $action = $this->input('action');

        $rules = [
            'action' => 'required|in:disetujui,ditolak',
            'notes' => 'nullable|string|max:1000',
        ];

        // Add conditional validation for rejection
        if ($action === 'ditolak') {
            $rules['notes'] = 'required|string|max:1000|min:10';
        }

        // Add conditional validation for certain approval levels
        $approval = $this->route('approval');
        if ($approval && in_array($approval->level, ['direktur_keuangan', 'direktur_utama'])) {
            $rules['notes'] = 'nullable|string|max:1000';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $action = $this->input('action');
        $messages = [
            'action.required' => 'Aksi persetujuan wajib dipilih',
            'action.in' => 'Aksi persetujuan tidak valid',
            'notes.string' => 'Catatan harus berupa teks',
            'notes.max' => 'Catatan maksimal 1000 karakter',
        ];

        if ($action === 'ditolak') {
            $messages['notes.required'] = 'Catatan penolakan wajib diisi';
            $messages['notes.min'] = 'Catatan penolakan minimal 10 karakter';
        }

        return $messages;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateApprovalConditions($validator);
            $this->validateActionConditions($validator);
        });
    }

    /**
     * Validate approval conditions
     */
    protected function validateApprovalConditions($validator)
    {
        $approval = $this->route('approval');
        $pengajuan = $approval->pengajuanDana;

        // Check if pengajuan is still in valid status
        if (!in_array($pengajuan->status, ['menunggu_approval', 'revisi'])) {
            $validator->errors()->add('status', 'Pengajuan tidak dapat diproses karena status: ' . $pengajuan->status);
        }

        // Check for workflow integrity
        $previousApprovals = \App\Models\Approval::where('pengajuan_dana_id', $pengajuan->id)
            ->where('level', '<', $approval->level)
            ->get();

        foreach ($previousApprovals as $prevApproval) {
            if ($prevApproval->status !== 'disetujui') {
                $validator->errors()->add('workflow', 'Approval level sebelumnya belum disetujui');
                break;
            }
        }
    }

    /**
     * Validate action-specific conditions
     */
    protected function validateActionConditions($validator)
    {
        $action = $this->input('action');
        $approval = $this->route('approval');
        $pengajuan = $approval->pengajuanDana;

        if ($action === 'disetujui') {
            // Check if user has sufficient authority for the amount
            if (!$this->hasAuthorityForAmount($approval->approver, $pengajuan->total_pengajuan)) {
                $validator->errors()->add('authority', 'Anda tidak memiliki wewenang untuk menyetujui pengajuan dengan nominal ini');
            }
        }
    }

    /**
     * Check if user has authority for the amount
     */
    protected function hasAuthorityForAmount($approver, $amount)
    {
        if (!$approver) {
            return false;
        }

        $approverRole = $approver->role?->name;

        switch ($approverRole) {
            case 'kepala_divisi':
                // Kepala divisi can approve up to certain limit (from approval config)
                $limit = \App\Models\ApprovalConfig::where('level', 'kepala_divisi')
                    ->where('jenis_pengajuan', 'all')
                    ->max('minimal_nominal');
                return $amount <= ($limit ?? 100000000); // Default 100 juta

            case 'direktur_keuangan':
                // Direktur keuangan can approve up to higher limit
                $limit = \App\Models\ApprovalConfig::where('level', 'direktur_keuangan')
                    ->where('jenis_pengajuan', 'all')
                    ->max('minimal_nominal');
                return $amount <= ($limit ?? 500000000); // Default 500 juta

            case 'direktur_utama':
                // Direktur utama has no limit
                return true;

            default:
                return false;
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'action' => 'Aksi Persetujuan',
            'notes' => 'Catatan',
        ];
    }

    /**
     * Get validated data with proper type casting
     */
    public function validated(): array
    {
        $validated = parent::validated();

        // Cast action to proper status
        if (isset($validated['action'])) {
            $validated['action'] = $validated['action'] === 'disetujui' ? 'disetujui' : 'ditolak';
        }

        return $validated;
    }
}