<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\PencairanService;

class StorePencairanDanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasPermission('pencairan_dana.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $pengajuanId = $this->input('pengajuan_dana_id');
        $pengajuan = \App\Models\PengajuanDana::find($pengajuanId);
        $isHonorarium = $pengajuan && $pengajuan->jenis_pengajuan === 'honorarium';

        $rules = [
            'pengajuan_dana_id' => 'required|exists:pengajuan_danas,id',
            'tanggal_pencairan' => 'required|date|after_or_equal:today',
            'jumlah_pencairan' => 'required|numeric|min:1000',

            // Lampiran attachments
            'lampiran' => 'nullable|array|max:5',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',

            // Notes and attachments
            'catatan' => 'nullable|string|max:500',
        ];

        if ($isHonorarium) {
            // Honorarium-specific rules
            $rules['honorarium_ids'] = 'required|array|min:1';
            $rules['honorarium_ids.*'] = 'exists:honorarium_details,id';
            $rules['metode_pencairan'] = 'required|in:transfer,cash';
            $rules['rekening_perusahaan_id'] = 'required_if:metode_pencairan,transfer|nullable|exists:rekening_perusahaans,id';
            // Optional lampiran per honorarium recipient
            $rules['lampiran_honorarium'] = 'nullable|array';
            $rules['lampiran_honorarium.*'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        } else {
            // Non-honorarium rules
            $rules['rekening_perusahaan_id'] = 'required|exists:rekening_perusahaans,id';
            $rules['metode_pencairan'] = 'required|in:transfer,cash,reimburse';

            // Bank transfer details (required if metode_pencairan is transfer)
            $rules['bank_id'] = 'required_if:metode_pencairan,transfer|nullable|exists:banks,id';
            $rules['nomor_rekening'] = 'required_if:metode_pencairan,transfer|string|max:50';
            $rules['atas_nama'] = 'required_if:metode_pencairan,transfer|string|max:255';

            // Lampiran attachments for non-honorarium
            $rules['lampiran'] = 'nullable|array|max:5';
            $rules['lampiran.*'] = 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120';
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
        return [
            'pengajuan_dana_id.required' => 'Pengajuan dana wajib dipilih',
            'pengajuan_dana_id.exists' => 'Pengajuan dana tidak valid',
            'rekening_perusahaan_id.required' => 'Rekening sumber wajib dipilih',
            'rekening_perusahaan_id.exists' => 'Rekening sumber tidak valid',
            'tanggal_pencairan.required' => 'Tanggal pencairan wajib diisi',
            'tanggal_pencairan.date' => 'Format tanggal tidak valid',
            'tanggal_pencairan.after_or_equal' => 'Tanggal pencairan tidak boleh kurang dari hari ini',
            'jumlah_pencairan.required' => 'Jumlah pencairan wajib diisi',
            'jumlah_pencairan.numeric' => 'Jumlah pencairan harus berupa angka',
            'jumlah_pencairan.min' => 'Jumlah pencairan minimal Rp 1.000',
            'metode_pencairan.required' => 'Metode pencairan wajib dipilih',
            'metode_pencairan.in' => 'Metode pencairan tidak valid',

            'bank_id.required_if' => 'Nama bank tujuan wajib dipilih untuk transfer',
            'bank_id.exists' => 'Bank tujuan tidak valid',
            'nomor_rekening.required_if' => 'Nomor rekening tujuan wajib diisi untuk transfer',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter',
            'atas_nama.required_if' => 'Atas nama tujuan wajib diisi untuk transfer',
            'atas_nama.max' => 'Atas nama maksimal 255 karakter',

            'lampiran.max' => 'Maksimal 5 file lampiran',
            'lampiran.*.mimes' => 'Format file harus PDF, JPG, JPEG, PNG, DOC, atau DOCX',
            'lampiran.*.max' => 'Ukuran file maksimal 5MB',

            'catatan.string' => 'Catatan harus berupa teks',
            'catatan.max' => 'Catatan maksimal 500 karakter',
        ];
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
            $this->validatePengajuanStatus($validator);
            $this->validatePencairanAvailability($validator);
            $this->validatePencairanAmount($validator);
            $this->validateBankDetails($validator);
        });
    }

    /**
     * Validate pengajuan status
     */
    protected function validatePengajuanStatus($validator)
    {
        $pengajuanId = $this->input('pengajuan_dana_id');
        $pengajuan = \App\Models\PengajuanDana::find($pengajuanId);

        if (!$pengajuan) {
            return;
        }

        if ($pengajuan->status !== 'menunggu_pencairan') {
            $validator->errors()->add('pengajuan_dana_id', 'Pengajuan harus dalam status menunggu pencairan');
        }

        // Check if active (non-cancelled) pencairan already exists
        if ($pengajuan->activePencairan) {
            $validator->errors()->add('pengajuan_dana_id', 'Pengajuan ini sudah memiliki pencairan aktif');
        }
    }

    /**
     * Validate pencairan availability
     */
    protected function validatePencairanAvailability($validator)
    {
        $pengajuanId = $this->input('pengajuan_dana_id');

        if (!PencairanService::canCreatePencairan(\App\Models\PengajuanDana::find($pengajuanId))) {
            $validator->errors()->add('pengajuan_dana_id', 'Pencairan tidak dapat dibuat untuk pengajuan ini');
        }
    }

    /**
     * Validate pencairan amount
     */
    protected function validatePencairanAmount($validator)
    {
        $pengajuanId = $this->input('pengajuan_dana_id');
        $jumlahPencairan = $this->input('jumlah_pencairan');

        $pengajuan = \App\Models\PengajuanDana::find($pengajuanId);

        if ($pengajuan && $jumlahPencairan > $pengajuan->total_pengajuan) {
            $validator->errors()->add('jumlah_pencairan', 'Jumlah pencairan tidak boleh melebihi total pengajuan (' . formatRupiah($pengajuan->total_pengajuan) . ')');
        }
    }

    /**
     * Validate bank details format
     */
    protected function validateBankDetails($validator)
    {
        $metodePencairan = $this->input('metode_pencairan');
        $nomorRekening = $this->input('nomor_rekening');
        $nomorRekeningSumber = $this->input('nomor_rekening_sumber');

        if ($metodePencairan === 'transfer') {
            if ($nomorRekening) {
                // Check if account number contains only numbers and spaces
                if (!preg_match('/^[0-9\s]+$/', $nomorRekening)) {
                    $validator->errors()->add('nomor_rekening', 'Nomor rekening hanya boleh mengandung angka');
                }

                // Check minimum length (without spaces)
                if (strlen(preg_replace('/\s+/', '', $nomorRekening)) < 5) {
                    $validator->errors()->add('nomor_rekening', 'Nomor rekening minimal 5 digit');
                }
            }

            if ($nomorRekeningSumber) {
                // Check if account number contains only numbers and spaces
                if (!preg_match('/^[0-9\s]+$/', $nomorRekeningSumber)) {
                    $validator->errors()->add('nomor_rekening_sumber', 'Nomor rekening sumber hanya boleh mengandung angka');
                }

                // Check minimum length (without spaces)
                if (strlen(preg_replace('/\s+/', '', $nomorRekeningSumber)) < 5) {
                    $validator->errors()->add('nomor_rekening_sumber', 'Nomor rekening sumber minimal 5 digit');
                }
            }
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
            'pengajuan_dana_id' => 'Pengajuan Dana',
            'tanggal_pencairan' => 'Tanggal Pencairan',
            'jumlah_pencairan' => 'Jumlah Pencairan',
            'metode_pencairan' => 'Metode Pencairan',
            'bank_id' => 'Nama Bank Tujuan',
            'nomor_rekening' => 'Nomor Rekening Tujuan',
            'atas_nama' => 'Atas Nama Tujuan',
            'nama_bank_sumber' => 'Nama Bank Sumber',
            'nomor_rekening_sumber' => 'Nomor Rekening Sumber',
            'lampiran' => 'Lampiran',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Get validated data with additional processing
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // If a specific key is requested, return it directly
        if ($key !== null) {
            return $validated;
        }

        // Add created_by
        $validated['created_by'] = Auth::id();

        // Set default status
        $validated['status'] = 'pending';

        // Format dates
        if (isset($validated['tanggal_pencairan'])) {
            $validated['tanggal_pencairan'] = date('Y-m-d', strtotime($validated['tanggal_pencairan']));
        }

        return $validated;
    }
}
