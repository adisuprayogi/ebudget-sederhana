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
        return [
            'pengajuan_dana_id' => 'required|exists:pengajuan_danas,id',
            'tanggal_pencairan' => 'required|date|after_or_equal:today',
            'total_pencairan' => 'required|numeric|min:1000',
            'cara_pencairan' => 'required|in:transfer,tunai,cek,bilyet_giro',

            // Bank transfer details (required if cara_pencairan is transfer)
            'bank_tujuan' => 'required_if:cara_pencairan,transfer|string|max:100',
            'nomor_rekening' => 'required_if:cara_pencairan,transfer|string|max:50',
            'atas_nama' => 'required_if:cara_pencairan,transfer|string|max:255',

            // Check/bilyet giro details
            'nomor_cek' => 'nullable|required_if:cara_pencairan,cek|string|max:50',
            'nomor_giro' => 'nullable|required_if:cara_pencairan,bilyet_giro|string|max:50',
            'bank_cek_giro' => 'nullable|required_if:cara_pencairan,cek,bilyet_giro|string|max:100',

            // Notes and attachments
            'catatan' => 'nullable|string|max:500',
            'bukti_pencairan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
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
            'tanggal_pencairan.required' => 'Tanggal pencairan wajib diisi',
            'tanggal_pencairan.date' => 'Format tanggal tidak valid',
            'tanggal_pencairan.after_or_equal' => 'Tanggal pencairan tidak boleh kurang dari hari ini',
            'total_pencairan.required' => 'Total pencairan wajib diisi',
            'total_pencairan.numeric' => 'Total pencairan harus berupa angka',
            'total_pencairan.min' => 'Total pencairan minimal Rp 1.000',
            'cara_pencairan.required' => 'Cara pencairan wajib dipilih',
            'cara_pencairan.in' => 'Cara pencairan tidak valid',

            'bank_tujuan.required_if' => 'Bank tujuan wajib diisi untuk transfer',
            'bank_tujuan.max' => 'Nama bank maksimal 100 karakter',
            'nomor_rekening.required_if' => 'Nomor rekening wajib diisi untuk transfer',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter',
            'atas_nama.required_if' => 'Atas nama rekening wajib diisi untuk transfer',
            'atas_nama.max' => 'Atas nama maksimal 255 karakter',

            'nomor_cek.required_if' => 'Nomor cek wajib diisi untuk pembayaran dengan cek',
            'nomor_cek.max' => 'Nomor cek maksimal 50 karakter',
            'nomor_giro.required_if' => 'Nomor giro wajib diisi untuk pembayaran dengan bilyet giro',
            'nomor_giro.max' => 'Nomor giro maksimal 50 karakter',
            'bank_cek_giro.required_if' => 'Bank cek/giro wajib diisi',
            'bank_cek_giro.max' => 'Nama bank maksimal 100 karakter',

            'catatan.string' => 'Catatan harus berupa teks',
            'catatan.max' => 'Catatan maksimal 500 karakter',
            'bukti_pencairan.file' => 'File bukti pencairan harus berupa file yang valid',
            'bukti_pencairan.mimes' => 'Format file bukti pencairan tidak diizinkan',
            'bukti_pencairan.max' => 'Ukuran file bukti pencairan maksimal 2MB',
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

        if ($pengajuan->status !== 'disetujui') {
            $validator->errors()->add('pengajuan_dana_id', 'Pengajuan harus disetujui sebelum dapat dicairkan');
        }

        if ($pengajuan->pencairanDana) {
            $validator->errors()->add('pengajuan_dana_id', 'Pengajuan ini sudah memiliki pencairan');
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
        $totalPencairan = $this->input('total_pencairan');

        $pengajuan = \App\Models\PengajuanDana::find($pengajuanId);

        if ($pengajuan && $totalPencairan > $pengajuan->total_pengajuan) {
            $validator->errors()->add('total_pencairan', 'Total pencairan tidak boleh melebihi total pengajuan (Rp ' . number_format($pengajuan->total_pengajuan, 0, ',', '.') . ')');
        }
    }

    /**
     * Validate bank details format
     */
    protected function validateBankDetails($validator)
    {
        $caraPencairan = $this->input('cara_pencairan');
        $nomorRekening = $this->input('nomor_rekening');

        if ($caraPencairan === 'transfer' && $nomorRekening) {
            // Check if account number contains only numbers
            if (!preg_match('/^[0-9]+$/', $nomorRekening)) {
                $validator->errors()->add('nomor_rekening', 'Nomor rekening hanya boleh mengandung angka');
            }

            // Check minimum length
            if (strlen($nomorRekening) < 5) {
                $validator->errors()->add('nomor_rekening', 'Nomor rekening minimal 5 digit');
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
            'total_pencairan' => 'Total Pencairan',
            'cara_pencairan' => 'Cara Pencairan',
            'bank_tujuan' => 'Bank Tujuan',
            'nomor_rekening' => 'Nomor Rekening',
            'atas_nama' => 'Atas Nama',
            'nomor_cek' => 'Nomor Cek',
            'nomor_giro' => 'Nomor Giro',
            'bank_cek_giro' => 'Bank Cek/Giro',
            'catatan' => 'Catatan',
            'bukti_pencairan' => 'Bukti Pencairan',
        ];
    }

    /**
     * Get validated data with additional processing
     */
    public function validated(): array
    {
        $validated = parent::validated();

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