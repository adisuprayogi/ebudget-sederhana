<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePencairanDanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $pencairan = $this->route('pencairan_dana');

        // Only staff keuangan can update
        if (!Auth::user()->hasPermission('pencairan_dana.edit')) {
            return false;
        }

        // Can only update pending pencairan
        return $pencairan->status === 'pending';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_pencairan' => 'sometimes|required|date|after_or_equal:today',
            'total_pencairan' => 'sometimes|required|numeric|min:1000',
            'cara_pencairan' => 'sometimes|required|in:transfer,tunai,cek,bilyet_giro',

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
            $this->validateEditable($validator);
            $this->validatePencairanAmount($validator);
            $this->validateBankDetails($validator);
        });
    }

    /**
     * Validate that pencairan can be edited
     */
    protected function validateEditable($validator)
    {
        $pencairan = $this->route('pencairan_dana');

        if ($pencairan->status !== 'pending') {
            $validator->errors()->add('status', 'Pencairan tidak dapat diedit karena status: ' . $pencairan->status);
        }
    }

    /**
     * Validate pencairan amount
     */
    protected function validatePencairanAmount($validator)
    {
        $totalPencairan = $this->input('total_pencairan');
        if (!$totalPencairan) {
            return;
        }

        $pencairan = $this->route('pencairan_dana');
        $pengajuan = $pencairan->pengajuanDana;

        if ($totalPencairan > $pengajuan->total_pengajuan) {
            $validator->errors()->add('total_pencairan', 'Total pencairan tidak boleh melebihi total pengajuan (Rp ' . number_format($pengajuan->total_pengajuan, 0, ',', '.') . ')');
        }
    }

    /**
     * Validate bank details format
     */
    protected function validateBankDetails($validator)
    {
        $caraPencairan = $this->input('cara_pencairan');
        if (!$caraPencairan) {
            $caraPencairan = $this->route('pencairan_dana')->cara_pencairan;
        }

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

        // Format dates
        if (isset($validated['tanggal_pencairan'])) {
            $validated['tanggal_pencairan'] = date('Y-m-d', strtotime($validated['tanggal_pencairan']));
        }

        return $validated;
    }
}