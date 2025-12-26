<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\NumberingService;

class StoreRefundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasPermission('refund.create');
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
            'lpj_id' => 'nullable|exists:laporan_pertanggung_jawabans,id',
            'tanggal_refund' => 'required|date|before_or_equal:today',
            'jenis_refund' => 'required|in:kelebihan,sisa,dana_kembali',
            'nominal_refund' => 'required|numeric|min:1000',
            'alasan_refund' => 'required|string|max:1000',
            'cara_refund' => 'required|in:transfer,tunai,potongan_pengajuan',

            // Bank transfer details (required if cara_refund is transfer)
            'bank_tujuan' => 'required_if:cara_refund,transfer|string|max:100',
            'nomor_rekening' => 'required_if:cara_refund,transfer|string|max:50',
            'atas_nama' => 'required_if:cara_refund,transfer|string|max:255',

            // Refund details
            'details' => 'nullable|array|max:10',
            'details.*.uraian' => 'required|string|max:500',
            'details.*.nominal' => 'required|numeric|min:1000',

            // Attachments
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',

            // Notes
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
            'pengajuan_dana_id.required' => 'Pengajuan dana wajib dipilih',
            'pengajuan_dana_id.exists' => 'Pengajuan dana tidak valid',
            'lpj_id.exists' => 'LPJ tidak valid',
            'tanggal_refund.required' => 'Tanggal refund wajib diisi',
            'tanggal_refund.date' => 'Format tanggal tidak valid',
            'tanggal_refund.before_or_equal' => 'Tanggal refund tidak boleh lebih dari hari ini',
            'jenis_refund.required' => 'Jenis refund wajib dipilih',
            'jenis_refund.in' => 'Jenis refund tidak valid',
            'nominal_refund.required' => 'Nominal refund wajib diisi',
            'nominal_refund.numeric' => 'Nominal refund harus berupa angka',
            'nominal_refund.min' => 'Nominal refund minimal Rp 1.000',
            'alasan_refund.required' => 'Alasan refund wajib diisi',
            'alasan_refund.max' => 'Alasan refund maksimal 1000 karakter',
            'cara_refund.required' => 'Cara refund wajib dipilih',
            'cara_refund.in' => 'Cara refund tidak valid',

            'bank_tujuan.required_if' => 'Bank tujuan wajib diisi untuk transfer',
            'bank_tujuan.max' => 'Nama bank maksimal 100 karakter',
            'nomor_rekening.required_if' => 'Nomor rekening wajib diisi untuk transfer',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter',
            'atas_nama.required_if' => 'Atas nama rekening wajib diisi untuk transfer',
            'atas_nama.max' => 'Atas nama maksimal 255 karakter',

            'details.max' => 'Maksimal 10 detail refund',
            'details.*.uraian.required' => 'Uraian detail wajib diisi',
            'details.*.uraian.max' => 'Uraian detail maksimal 500 karakter',
            'details.*.nominal.required' => 'Nominal detail wajib diisi',
            'details.*.nominal.numeric' => 'Nominal detail harus berupa angka',
            'details.*.nominal.min' => 'Nominal detail minimal Rp 1.000',

            'attachments.max' => 'Maksimal 5 file lampiran',
            'attachments.*.file' => 'File harus berupa file yang valid',
            'attachments.*.mimes' => 'Format file tidak diizinkan',
            'attachments.*.max' => 'Ukuran file maksimal 2MB',

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
            $this->validateLpjStatus($validator);
            $this->validateRefundAmount($validator);
            $this->validateRefundAvailability($validator);
            $this->validateDetailsSum($validator);
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

        $validStatuses = ['dicairkan', 'lpj_approved', 'selesai'];
        if (!in_array($pengajuan->status, $validStatuses)) {
            $validator->errors()->add('pengajuan_dana_id', 'Refund hanya dapat dibuat untuk pengajuan yang telah dicairkan');
        }

        // Refund not allowed for pembayaran type
        if ($pengajuan->jenis_pengajuan === 'pembayaran') {
            $validator->errors()->add('pengajuan_dana_id', 'Refund tidak diperlukan untuk jenis pengajuan pembayaran');
        }
    }

    /**
     * Validate LPJ status if provided
     */
    protected function validateLpjStatus($validator)
    {
        $pengajuanId = $this->input('pengajuan_dana_id');
        $lpjId = $this->input('lpj_id');
        $jenisRefund = $this->input('jenis_refund');

        $pengajuan = \App\Models\PengajuanDana::find($pengajuanId);
        if (!$pengajuan) {
            return;
        }

        // For 'sisa' and 'kelebihan' types, LPJ must exist and be approved
        if (in_array($jenisRefund, ['sisa', 'kelebihan'])) {
            if (!$lpjId) {
                $validator->errors()->add('lpj_id', 'LPJ wajib dipilih untuk jenis refund ' . $jenisRefund);
                return;
            }

            $lpj = \App\Models\LaporanPertanggungJawaban::find($lpjId);
            if (!$lpj || $lpj->pengajuan_dana_id !== $pengajuanId) {
                $validator->errors()->add('lpj_id', 'LPJ tidak valid untuk pengajuan ini');
                return;
            }

            if ($lpj->status !== 'approved') {
                $validator->errors()->add('lpj_id', 'LPJ harus disetujui sebelum dapat membuat refund');
            }
        }
    }

    /**
     * Validate refund amount
     */
    protected function validateRefundAmount($validator)
    {
        $pengajuanId = $this->input('pengajuan_dana_id');
        $nominalRefund = $this->input('nominal_refund');
        $jenisRefund = $this->input('jenis_refund');
        $lpjId = $this->input('lpj_id');

        $pengajuan = \App\Models\PengajuanDana::find($pengajuanId);
        if (!$pengajuan) {
            return;
        }

        switch ($jenisRefund) {
            case 'sisa':
                $lpj = \App\Models\LaporanPertanggungJawaban::find($lpjId);
                if ($lpj && $nominalRefund > $lpj->sisa_dana) {
                    $validator->errors()->add('nominal_refund', 'Nominal refund tidak boleh melebihi sisa dana (Rp ' . number_format($lpj->sisa_dana, 0, ',', '.') . ')');
                }
                break;

            case 'kelebihan':
                // Kelebihan can be any reasonable amount, but validate against pencairan
                $pencairan = $pengajuan->pencairanDana;
                if ($pencairan && $nominalRefund > $pencairan->total_pencairan) {
                    $validator->errors()->add('nominal_refund', 'Nominal refund tidak boleh melebihi total pencairan (Rp ' . number_format($pencairan->total_pencairan, 0, ',', '.') . ')');
                }
                break;

            case 'dana_kembali':
                // Dana kembali usually limited to pencairan amount
                $pencairan = $pengajuan->pencairanDana;
                if ($pencairan && $nominalRefund > $pencairan->total_pencairan) {
                    $validator->errors()->add('nominal_refund', 'Nominal refund tidak boleh melebihi total pencairan (Rp ' . number_format($pencairan->total_pencairan, 0, ',', '.') . ')');
                }
                break;
        }
    }

    /**
     * Validate refund availability
     */
    protected function validateRefundAvailability($validator)
    {
        $pengajuanId = $this->input('pengajuan_dana_id');

        // Check if refund already exists for this pengajuan
        $existingRefund = \App\Models\Refund::where('pengajuan_dana_id', $pengajuanId)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingRefund) {
            $validator->errors()->add('pengajuan_dana_id', 'Refund sudah ada untuk pengajuan ini (No: ' . $existingRefund->nomor_refund . ')');
        }
    }

    /**
     * Validate that details sum matches nominal refund
     */
    protected function validateDetailsSum($validator)
    {
        $nominalRefund = $this->input('nominal_refund');
        $details = $this->input('details', []);

        if (!empty($details)) {
            $totalDetails = array_sum(array_column($details, 'nominal'));

            // Allow small difference due to rounding
            if (abs($nominalRefund - $totalDetails) > 100) {
                $validator->errors()->add('nominal_refund', 'Nominal refund tidak sesuai dengan jumlah detail');
            }
        }
    }

    /**
     * Validate bank details format
     */
    protected function validateBankDetails($validator)
    {
        $caraRefund = $this->input('cara_refund');
        $nomorRekening = $this->input('nomor_rekening');

        if ($caraRefund === 'transfer' && $nomorRekening) {
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
            'lpj_id' => 'LPJ',
            'tanggal_refund' => 'Tanggal Refund',
            'jenis_refund' => 'Jenis Refund',
            'nominal_refund' => 'Nominal Refund',
            'alasan_refund' => 'Alasan Refund',
            'cara_refund' => 'Cara Refund',
            'bank_tujuan' => 'Bank Tujuan',
            'nomor_rekening' => 'Nomor Rekening',
            'atas_nama' => 'Atas Nama',
            'details' => 'Detail Refund',
            'attachments' => 'Lampiran',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Get validated data with additional processing
     */
    public function validated(): array
    {
        $validated = parent::validated();

        // Generate nomor refund
        $validated['nomor_refund'] = NumberingService::generateNomorRefund();

        // Add created_by
        $validated['created_by'] = Auth::id();

        // Set default status
        $validated['status'] = 'pending';

        // Format dates
        if (isset($validated['tanggal_refund'])) {
            $validated['tanggal_refund'] = date('Y-m-d', strtotime($validated['tanggal_refund']));
        }

        return $validated;
    }
}