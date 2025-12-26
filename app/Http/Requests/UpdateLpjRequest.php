<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLpjRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $lpj = $this->route('laporanPertanggungJawaban');
        $user = Auth::user();

        // User can update their own draft LPJ
        if ($lpj->status === 'draft' && $lpj->created_by === $user->id) {
            return $user->hasPermission('lpj.edit');
        }

        // Staff keuangan can update any LPJ
        return $user->hasPermission('lpj.edit_all');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $lpj = $this->route('laporanPertanggungJawaban');
        $isDraft = $lpj->status === 'draft';

        return [
            'tanggal_lpj' => 'sometimes|required|date|before_or_equal:today',
            'uraian_kegiatan' => 'sometimes|required|string|max:1000',
            'total_digunakan' => 'sometimes|required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',

            // Detail LPJ validation (only for draft status)
            'details' => $isDraft ? 'sometimes|required|array|min:1' : 'prohibited',
            'details.*.id' => $isDraft ? 'nullable|integer|exists:detail_lpjs,id' : 'prohibited',
            'details.*.detail_pencairan_id' => $isDraft ? 'required|exists:detail_pencairans,id' : 'prohibited',
            'details.*.uraian' => $isDraft ? 'required|string|max:500' : 'prohibited',
            'details.*.volume_realisasi' => $isDraft ? 'required|numeric|min:0' : 'prohibited',
            'details.*.satuan' => $isDraft ? 'required|string|max:50' : 'prohibited',
            'details.*.harga_satuan' => $isDraft ? 'required|numeric|min:1000' : 'prohibited',
            'details.*.subtotal_realisasi' => $isDraft ? 'required|numeric|min:0' : 'prohibited',
            'details.*.keterangan' => $isDraft ? 'nullable|string|max:500' : 'prohibited',

            // Attachments (only for draft status)
            'attachments' => $isDraft ? 'nullable|array|max:10' : 'prohibited',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',

            // Removed attachments
            'removed_attachments' => 'nullable|array',
            'removed_attachments.*' => 'integer|exists:lpj_attachments,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $messages = [
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
            'details.*.id.exists' => 'Detail LPJ tidak valid',
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

        // Add prohibition messages
        $lpj = $this->route('laporanPertanggungJawaban');
        if ($lpj && $lpj->status !== 'draft') {
            $messages['details.prohibited'] = 'Detail LPJ tidak dapat diubah karena LPJ sudah disubmit';
            $messages['attachments.prohibited'] = 'Lampiran tidak dapat diubah karena LPJ sudah disubmit';
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
            $this->validateEditable($validator);
            $this->validateRealisasiAmount($validator);
            $this->validateTotalCalculation($validator);
            $this->validateDetailsIntegrity($validator);
        });
    }

    /**
     * Validate that LPJ can be edited
     */
    protected function validateEditable($validator)
    {
        $lpj = $this->route('laporanPertanggungJawaban');

        if (!in_array($lpj->status, ['draft'])) {
            $validator->errors()->add('status', 'LPJ tidak dapat diedit karena status: ' . $lpj->status);
        }
    }

    /**
     * Validate realisasi amount doesn't exceed pencairan
     */
    protected function validateRealisasiAmount($validator)
    {
        $totalDigunakan = $this->input('total_digunakan');
        if (!$totalDigunakan) {
            return;
        }

        $lpj = $this->route('laporanPertanggungJawaban');
        $pencairan = $lpj->pencairanDana;

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
     * Validate that details belong to this LPJ's pencairan
     */
    protected function validateDetailsIntegrity($validator)
    {
        $details = $this->input('details', []);
        if (empty($details)) {
            return;
        }

        $lpj = $this->route('laporanPertanggungJawaban');
        $pencairan = $lpj->pencairanDana;

        $detailPencairanIds = array_column($details, 'detail_pencairan_id');
        $pencairanDetailIds = $pencairan->detailPencairans->pluck('id')->toArray();

        // Check all detail pencairan IDs belong to this pencairan
        foreach ($detailPencairanIds as $detailId) {
            if (!in_array($detailId, $pencairanDetailIds)) {
                $validator->errors()->add('details', 'Detail pencairan tidak valid');
                break;
            }
        }

        // Check if all pencairan details are included (only for new details without ID)
        $newDetails = array_filter($details, function ($detail) {
            return !isset($detail['id']);
        });

        if (!empty($newDetails)) {
            $newDetailIds = array_column($newDetails, 'detail_pencairan_id');
            $existingLpjDetailIds = $lpj->detailLpjs->pluck('detail_pencairan_id')->toArray();

            $missingDetails = array_diff($newDetailIds, $existingLpjDetailIds);
            if (!empty($missingDetails) && count($newDetailIds) < count($pencairanDetailIds)) {
                $validator->errors()->add('details', 'Semua detail pencairan harus dilaporkan');
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

        // Format dates
        if (isset($validated['tanggal_lpj'])) {
            $validated['tanggal_lpj'] = date('Y-m-d', strtotime($validated['tanggal_lpj']));
        }

        // Recalculate sisa dana if total_digunakan changed
        if (isset($validated['total_digunakan'])) {
            $lpj = $this->route('laporanPertanggungJawaban');
            $pencairan = $lpj->pencairanDana;
            if ($pencairan) {
                $validated['sisa_dana'] = $pencairan->total_pencairan - $validated['total_digunakan'];
            }
        }

        return $validated;
    }
}