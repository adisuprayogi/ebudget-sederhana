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

        return Auth::user()->hasPermission('pencairan_dana.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'rekening_perusahaan_id' => 'sometimes|required|exists:rekening_perusahaans,id',
            'tanggal_pencairan' => 'sometimes|required|date|after_or_equal:today',
            'jumlah_pencairan' => 'sometimes|required|numeric|min:1000',
            'metode_pencairan' => 'sometimes|required|in:transfer,cash,reimburse',

            // Bank transfer details (required if metode_pencairan is transfer)
            'bank_id' => 'sometimes|required_if:metode_pencairan,transfer|nullable|exists:banks,id',
            'nomor_rekening' => 'sometimes|required_if:metode_pencairan,transfer|string|max:50',
            'atas_nama' => 'sometimes|required_if:metode_pencairan,transfer|string|max:255',

            // Lampiran attachments
            'lampiran' => 'nullable|array|max:5',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',

            // Remove existing lampiran
            'remove_lampiran' => 'nullable|array',
            'remove_lampiran.*' => 'integer|exists:pencairan_lampirans,id',

            // Notes and attachments
            'catatan' => 'nullable|string|max:500',
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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'rekening_perusahaan_id' => 'Rekening Perusahaan',
            'tanggal_pencairan' => 'Tanggal Pencairan',
            'jumlah_pencairan' => 'Jumlah Pencairan',
            'metode_pencairan' => 'Metode Pencairan',
            'bank_id' => 'Nama Bank Tujuan',
            'nomor_rekening' => 'Nomor Rekening Tujuan',
            'atas_nama' => 'Atas Nama Tujuan',
            'lampiran' => 'Lampiran',
            'catatan' => 'Catatan',
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
            $this->validatePencairanAmount($validator);
            $this->validateBankDetails($validator);
        });
    }

    /**
     * Validate pencairan amount
     */
    protected function validatePencairanAmount($validator)
    {
        $pencairan = $this->route('pencairanDana'); // Use correct parameter name
        $jumlahPencairan = $this->input('jumlah_pencairan');

        if (!$pencairan || !$jumlahPencairan) {
            return;
        }

        // Load the relationship to avoid null error
        $pengajuan = \App\Models\PengajuanDana::find($pencairan->pengajuan_dana_id);

        if ($pengajuan && $jumlahPencairan > $pengajuan->total_pengajuan) {
            $validator->errors()->add('jumlah_pencairan', 'Jumlah pencairan tidak boleh melebihi total pengajuan (' . formatRupiah($pengajuan->total_pengajuan) . ')');
        }
    }

    /**
     * Validate bank details format
     */
    protected function validateBankDetails($validator)
    {
        $pencairan = $this->route('pencairanDana'); // Use correct parameter name
        $metodePencairan = $this->input('metode_pencairan');
        if (!$metodePencairan && $pencairan) {
            $metodePencairan = $pencairan->metode_pencairan;
        }

        $nomorRekening = $this->input('nomor_rekening');

        if ($metodePencairan === 'transfer' && $nomorRekening) {
            // Check if account number contains only numbers and spaces
            if (!preg_match('/^[0-9\s]+$/', $nomorRekening)) {
                $validator->errors()->add('nomor_rekening', 'Nomor rekening hanya boleh mengandung angka');
            }

            // Check minimum length (without spaces)
            if (strlen(preg_replace('/\s+/', '', $nomorRekening)) < 5) {
                $validator->errors()->add('nomor_rekening', 'Nomor rekening minimal 5 digit');
            }
        }
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

        // Format dates
        if (isset($validated['tanggal_pencairan'])) {
            $validated['tanggal_pencairan'] = date('Y-m-d', strtotime($validated['tanggal_pencairan']));
        }

        return $validated;
    }
}
