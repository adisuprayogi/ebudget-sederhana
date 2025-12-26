<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\LpjService;

class StoreLpjRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasPermission('lpj.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'pencairan_dana_id' => 'required|exists:pencairan_danas,id',
            'tanggal_lpj' => 'required|date|before_or_equal:today',
            'uraian_kegiatan' => 'required|string|max:1000',
            'total_digunakan' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',

            // Detail LPJ validation
            'details' => 'required|array|min:1',
            'details.*.detail_pencairan_id' => 'required|exists:detail_pencairans,id',
            'details.*.uraian' => 'required|string|max:500',
            'details.*.volume_realisasi' => 'required|numeric|min:0',
            'details.*.satuan' => 'required|string|max:50',
            'details.*.harga_satuan' => 'required|numeric|min:1000',
            'details.*.subtotal_realisasi' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string|max:500',

            // Attachments
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
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
            'pencairan_dana_id.required' => 'Pencairan dana wajib dipilih',
            'pencairan_dana_id.exists' => 'Pencairan dana tidak valid',
            'tanggal_lpj.required' => 'Tanggal LPJ wajib diisi',
            'tanggal_lpj.date' => 'Format tanggal LPJ tidak valid',
            'tanggal_lpj.before_or_equal' => 'Tanggal LPJ tidak boleh lebih dari hari ini',
            'uraian_kegiatan.required' => 'Uraian kegiatan wajib diisi',
            'uraian_kegiatan.max' => 'Uraian kegiatan maksimal 1000 karakter',
            'total_digunakan.required' => 'Total digunakan wajib diisi',
            'total_digunakan.numeric' => 'Total digunakan harus berupa angka',
            'total_digunakan.min' => 'Total digunakan tidak boleh negatif',

            'details.required' => 'Detail LPJ wajib diisi',
            'details.min' => 'Minimal harus ada 1 detail LPJ',
            'details.*.detail_pencairan_id.required' => 'ID detail pencairan wajib diisi',
            'details.*.detail_pencairan_id.exists' => 'Detail pencairan tidak valid',
            'details.*.uraian.required' => 'Uraian detail wajib diisi',
            'details.*.uraian.max' => 'Uraian detail maksimal 500 karakter',
            'details.*.volume_realisasi.required' => 'Volume realisasi wajib diisi',
            'details.*.volume_realisasi.numeric' => 'Volume realisasi harus berupa angka',
            'details.*.volume_realisasi.min' => 'Volume realisasi tidak boleh negatif',
            'details.*.satuan.required' => 'Satuan wajib diisi',
            'details.*.satuan.max' => 'Satuan maksimal 50 karakter',
            'details.*.harga_satuan.required' => 'Harga satuan wajib diisi',
            'details.*.harga_satuan.numeric' => 'Harga satuan harus berupa angka',
            'details.*.harga_satuan.min' => 'Harga satuan minimal Rp 1.000',
            'details.*.subtotal_realisasi.required' => 'Subtotal realisasi wajib diisi',
            'details.*.subtotal_realisasi.numeric' => 'Subtotal realisasi harus berupa angka',
            'details.*.subtotal_realisasi.min' => 'Subtotal realisasi tidak boleh negatif',
            'details.*.keterangan.string' => 'Keterangan harus berupa teks',
            'details.*.keterangan.max' => 'Keterangan maksimal 500 karakter',

            'attachments.max' => 'Maksimal 10 file lampiran',
            'attachments.*.file' => 'File harus berupa file yang valid',
            'attachments.*.mimes' => 'Format file tidak diizinkan (PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG)',
            'attachments.*.max' => 'Ukuran file maksimal 5MB',

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
            $this->validatePencairanStatus($validator);
            $this->validateLpjAvailability($validator);
            $this->validateDetailsIntegrity($validator);
            $this->validateRealisasiAmount($validator);
            $this->validateTotalCalculation($validator);
        });
    }

    /**
     * Validate pencairan status
     */
    protected function validatePencairanStatus($validator)
    {
        $pencairanId = $this->input('pencairan_dana_id');
        $pencairan = \App\Models\PencairanDana::find($pencairanId);

        if (!$pencairan) {
            return;
        }

        $validStatuses = ['processed', 'completed'];
        if (!in_array($pencairan->status, $validStatuses)) {
            $validator->errors()->add('pencairan_dana_id', 'LPJ hanya dapat dibuat untuk pencairan yang telah diproses');
        }

        // Check if LPJ already exists
        if ($pencairan->laporanPertanggungJawaban) {
            $validator->errors()->add('pencairan_dana_id', 'LPJ sudah ada untuk pencairan ini');
        }

        // Check if pengajuan is pembayaran type (LPJ not required for pembayaran)
        $pengajuan = $pencairan->pengajuanDana;
        if ($pengajuan->jenis_pengajuan === 'pembayaran') {
            $validator->errors()->add('pencairan_dana_id', 'LPJ tidak diperlukan untuk jenis pengajuan pembayaran');
        }
    }

    /**
     * Validate LPJ availability
     */
    protected function validateLpjAvailability($validator)
    {
        $pencairanId = $this->input('pencairan_dana_id');
        $pencairan = \App\Models\PencairanDana::find($pencairanId);

        // Check if it's been at least some time since pencairan
        if ($pencairan && $pencairan->processed_at) {
            $minDaysSincePencairan = 1;
            $daysSince = $pencairan->processed_at->diffInDays(now());

            if ($daysSince < $minDaysSincePencairan) {
                $validator->errors()->add('pencairan_dana_id', "LPJ dapat dibuat minimal {$minDaysSincePencairan} hari setelah pencairan diproses");
            }
        }
    }

    /**
     * Validate that details match pencairan details
     */
    protected function validateDetailsIntegrity($validator)
    {
        $pencairanId = $this->input('pencairan_dana_id');
        $details = $this->input('details', []);

        if (empty($details)) {
            return;
        }

        $pencairan = \App\Models\PencairanDana::find($pencairanId);
        if (!$pencairan) {
            return;
        }

        $detailPencairanIds = array_column($details, 'detail_pencairan_id');
        $pencairanDetailIds = $pencairan->detailPencairans->pluck('id')->toArray();

        // Check all detail pencairan IDs belong to this pencairan
        foreach ($detailPencairanIds as $detailId) {
            if (!in_array($detailId, $pencairanDetailIds)) {
                $validator->errors()->add('details', 'Detail pencairan tidak valid');
                break;
            }
        }

        // Check if all pencairan details are included
        $missingDetails = array_diff($pencairanDetailIds, $detailPencairanIds);
        if (!empty($missingDetails)) {
            $validator->errors()->add('details', 'Semua detail pencairan harus dilaporkan');
        }
    }

    /**
     * Validate realisasi amount doesn't exceed pencairan
     */
    protected function validateRealisasiAmount($validator)
    {
        $totalDigunakan = $this->input('total_digunakan');
        $pencairanId = $this->input('pencairan_dana_id');

        if (!$totalDigunakan || !$pencairanId) {
            return;
        }

        $pencairan = \App\Models\PencairanDana::find($pencairanId);
        if ($pencairan && $totalDigunakan > $pencairan->total_pencairan) {
            $validator->errors()->add('total_digunakan', 'Total digunakan tidak boleh melebihi total pencairan (Rp ' . number_format($pencairan->total_pencairan, 0, ',', '.') . ')');
        }
    }

    /**
     * Validate total calculation matches details
     */
    protected function validateTotalCalculation($validator)
    {
        $totalDigunakan = $this->input('total_digunakan');
        $details = $this->input('details', []);

        if (!$totalDigunakan || empty($details)) {
            return;
        }

        $totalDetails = array_sum(array_column($details, 'subtotal_realisasi'));

        // Allow small difference due to rounding
        if (abs($totalDigunakan - $totalDetails) > 100) {
            $validator->errors()->add('total_digunakan', 'Total digunakan tidak sesuai dengan jumlah detail realisasi');
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
            'pencairan_dana_id' => 'Pencairan Dana',
            'tanggal_lpj' => 'Tanggal LPJ',
            'uraian_kegiatan' => 'Uraian Kegiatan',
            'total_digunakan' => 'Total Digunakan',
            'details' => 'Detail LPJ',
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

        // Add created_by
        $validated['created_by'] = Auth::id();

        // Set default status
        $validated['status'] = 'draft';

        // Calculate sisa dana
        $pencairan = \App\Models\PencairanDana::find($validated['pencairan_dana_id']);
        if ($pencairan) {
            $validated['sisa_dana'] = $pencairan->total_pencairan - $validated['total_digunakan'];
        }

        // Format dates
        if (isset($validated['tanggal_lpj'])) {
            $validated['tanggal_lpj'] = date('Y-m-d', strtotime($validated['tanggal_lpj']));
        }

        return $validated;
    }
}