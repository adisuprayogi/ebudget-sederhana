<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePengajuanDanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasPermission('pengajuan_dana.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $jenisPengajuan = $this->input('jenis_pengajuan');

        // Base rules applicable to all types
        $rules = [
            'judul_pengajuan' => 'required|string|max:255',
            'jenis_pengajuan' => 'required|in:kegiatan,pengadaan,pembayaran,honorarium,sewa,konsumi,reimbursement,lainnya',
            'program_kerja_id' => 'required|exists:program_kerjas,id',
            'divisi_id' => 'required|exists:divisis,id',
            'tanggal_pengajuan' => 'nullable|date',
            'total_pengajuan' => 'required|numeric|min:1000',
            'deskripsi' => 'required|string|max:1000',
            'nama_bank' => 'nullable|string|max:100',
            'rekening_tujuan' => 'nullable|string|max:200',
            // Global attachments (for all jenis pengajuan)
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
        ];

        // Rules for regular pengajuan (non-honorarium)
        if ($jenisPengajuan !== 'honorarium') {
            $rules['sub_program_id'] = 'required|exists:sub_programs,id';
            $rules['periode_mulai'] = 'nullable|date';
            $rules['periode_selesai'] = 'nullable|date|after_or_equal:periode_mulai';
            $rules['jenis_penerima'] = 'required|in:karyawan,vendor,lainnya';
            $rules['penerima_manfaat_id'] = 'nullable|required_if:jenis_penerima,karyawan|integer|exists:users,id';
            $rules['penerima_manfaat_name'] = 'nullable|required_if:jenis_penerima,vendor,lainnya|string|max:255';
            $rules['penerima_manfaat_detail'] = 'nullable|string';
            $rules['details'] = 'required|array|min:1';
            $rules['details.*.uraian'] = 'required|string|max:500';
            $rules['details.*.volume'] = 'required|numeric|min:0.01';
            $rules['details.*.satuan'] = 'required|string|max:50';
            $rules['details.*.harga_satuan'] = 'required|numeric|min:0';
            $rules['details.*.subtotal'] = 'nullable|numeric|min:0';
            $rules['details.*.detail_anggaran_id'] = 'nullable|exists:detail_anggarans,id';
            $rules['details.*.sub_program_id'] = 'nullable|exists:sub_programs,id';
        }

        // Rules for honorarium
        if ($jenisPengajuan === 'honorarium') {
            $rules['sub_program_id'] = 'required|exists:sub_programs,id';
            $rules['detail_anggaran_id'] = 'required|exists:detail_anggarans,id';
            $rules['honorarium_details'] = 'required|array|min:1';
            $rules['honorarium_details.*.penerima_manfaat_type'] = 'required|in:karyawan,non_karyawan';
            $rules['honorarium_details.*.penerima_manfaat_id'] = 'nullable|integer|exists:users,id';
            $rules['honorarium_details.*.penerima_manfaat_name'] = 'nullable|string|max:255';
            $rules['honorarium_details.*.jumlah_honor'] = 'required|numeric|min:0';
            $rules['honorarium_details.*.nomor_rekening'] = 'required|string|max:50';
            $rules['honorarium_details.*.deskripsi'] = 'nullable|string|max:500';
            $rules['honorarium_details.*.lampiran'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048';
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
            'judul_pengajuan.required' => 'Judul pengajuan wajib diisi',
            'jenis_pengajuan.required' => 'Jenis pengajuan wajib dipilih',
            'jenis_pengajuan.in' => 'Jenis pengajuan tidak valid',
            'program_kerja_id.required' => 'Program kerja wajib dipilih',
            'program_kerja_id.exists' => 'Program kerja tidak valid',
            'sub_program_id.required' => 'Sub program wajib dipilih',
            'sub_program_id.exists' => 'Sub program tidak valid',
            'divisi_id.required' => 'Divisi wajib dipilih',
            'divisi_id.exists' => 'Divisi tidak valid',
            'tanggal_pengajuan.date' => 'Format tanggal tidak valid',
            'periode_mulai.required' => 'Periode mulai wajib diisi',
            'periode_mulai.date' => 'Format periode mulai tidak valid',
            'periode_selesai.required' => 'Periode selesai wajib diisi',
            'periode_selesai.date' => 'Format periode selesai tidak valid',
            'periode_selesai.after_or_equal' => 'Periode selesai harus setelah atau sama dengan periode mulai',
            'total_pengajuan.required' => 'Total pengajuan wajib diisi',
            'total_pengajuan.numeric' => 'Total pengajuan harus berupa angka',
            'total_pengajuan.min' => 'Total pengajuan minimal Rp 1.000',

            'jenis_penerima.required' => 'Jenis penerima wajib dipilih',
            'jenis_penerima.in' => 'Jenis penerima tidak valid',
            'penerima_manfaat_id.required_if' => 'Nama karyawan wajib dipilih',
            'penerima_manfaat_id.exists' => 'Karyawan tidak valid',
            'penerima_manfaat_name.required_if' => 'Nama penerima wajib diisi',

            'details.required' => 'Detail pengajuan wajib diisi',
            'details.min' => 'Minimal harus ada 1 detail pengajuan',
            'details.*.uraian.required' => 'Uraian detail wajib diisi',
            'details.*.volume.required' => 'Volume wajib diisi',
            'details.*.volume.numeric' => 'Volume harus berupa angka',
            'details.*.satuan.required' => 'Satuan wajib diisi',
            'details.*.harga_satuan.required' => 'Harga satuan wajib diisi',
            'details.*.harga_satuan.numeric' => 'Harga satuan harus berupa angka',
            'details.*.harga_satuan.min' => 'Harga satuan minimal Rp 1.000',
            'details.*.subtotal.required' => 'Subtotal wajib diisi',
            'details.*.subtotal.numeric' => 'Subtotal harus berupa angka',
            'details.*.subtotal.min' => 'Subtotal minimal Rp 1.000',

            'attachments.required' => 'Lampiran dokumen wajib diupload (minimal 1 file)',
            'attachments.min' => 'Lampiran dokumen wajib diupload (minimal 1 file)',
            'attachments.max' => 'Maksimal 5 file lampiran',
            'attachments.*.file' => 'File harus berupa file yang valid',
            'attachments.*.mimes' => 'Format file tidak diizinkan',
            'attachments.*.max' => 'Ukuran file maksimal 2MB',
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
            $jenisPengajuan = $this->input('jenis_pengajuan');

            if ($jenisPengajuan === 'honorarium') {
                $this->validateHonorariumDetails($validator);
                $this->validatePaguAvailability($validator);
            } else {
                $this->validatePenerimaManfaat($validator);
                $this->validateDetailsSum($validator);
                $this->validatePaguAvailability($validator);
            }
        });
    }

    /**
     * Validate honorarium details
     */
    protected function validateHonorariumDetails($validator)
    {
        $honorariumDetails = $this->input('honorarium_details', []);

        foreach ($honorariumDetails as $index => $detail) {
            $penerimaType = $detail['penerima_manfaat_type'] ?? null;
            $penerimaId = $detail['penerima_manfaat_id'] ?? null;
            $penerimaName = $detail['penerima_manfaat_name'] ?? null;

            if ($penerimaType === 'karyawan' && empty($penerimaId)) {
                $validator->errors()->add("honorarium_details.{$index}.penerima_manfaat_id", "Karyawan wajib dipilih");
            }

            if ($penerimaType === 'non_karyawan' && empty($penerimaName)) {
                $validator->errors()->add("honorarium_details.{$index}.penerima_manfaat_name", "Nama penerima wajib diisi");
            }
        }
    }

    /**
     * Validate penerima manfaat selection
     */
    protected function validatePenerimaManfaat($validator)
    {
        $jenisPenerima = $this->input('jenis_penerima');
        $penerimaId = $this->input('penerima_manfaat_id');
        $penerimaName = $this->input('penerima_manfaat_name');

        // Validate based on jenis_penerima
        if ($jenisPenerima === 'karyawan' && empty($penerimaId)) {
            $validator->errors()->add('penerima_manfaat_id', 'Nama karyawan wajib dipilih');
        }

        if (in_array($jenisPenerima, ['vendor', 'lainnya']) && empty($penerimaName)) {
            $validator->errors()->add('penerima_manfaat_name', 'Nama penerima wajib diisi');
        }
    }

    /**
     * Validate that total_pengajuan matches sum of details
     */
    protected function validateDetailsSum($validator)
    {
        $totalPengajuan = (float) $this->input('total_pengajuan', 0);
        $details = $this->input('details', []);

        if (!empty($details)) {
            // Calculate total from volume * harga_satuan (since subtotal may not be sent from form)
            $totalDetails = 0;
            foreach ($details as $detail) {
                $volume = (float) ($detail['volume'] ?? 0);
                $harga = (float) ($detail['harga_satuan'] ?? 0);
                $totalDetails += ($volume * $harga);
            }

            if (abs($totalPengajuan - $totalDetails) > 100) { // Allow small difference due to rounding
                $validator->errors()->add('total_pengajuan', "Total pengajuan (Rp " . number_format($totalPengajuan, 0, ',', '.') . ") tidak sesuai dengan jumlah detail (Rp " . number_format($totalDetails, 0, ',', '.') . ")");
            }
        }
    }

    /**
     * Validate pagu availability
     */
    protected function validatePaguAvailability($validator)
    {
        $divisiId = $this->input('divisi_id');
        $programKerjaId = $this->input('program_kerja_id');
        $totalPengajuan = $this->input('total_pengajuan');

        // Get pagu for this divisi
        $pagu = \App\Models\PenetapanPagu::where('divisi_id', $divisiId)->first();

        if ($pagu) {
            // Calculate total approved pengajuan for this program
            $totalApproved = \App\Models\PengajuanDana::where('divisi_id', $divisiId)
                ->where('program_kerja_id', $programKerjaId)
                ->whereIn('status', ['disetujui', 'dicairkan', 'selesai'])
                ->sum('total_pengajuan');

            $remainingPagu = $pagu->jumlah_pagu - $totalApproved;

            if ($totalPengajuan > $remainingPagu) {
                $validator->errors()->add('total_pengajuan', 'Total pengajuan melebihi sisa pagu yang tersedia (Rp ' . number_format($remainingPagu, 0, ',', '.') . ')');
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
            'judul_pengajuan' => 'Judul Pengajuan',
            'jenis_pengajuan' => 'Jenis Pengajuan',
            'program_kerja_id' => 'Program Kerja',
            'divisi_id' => 'Divisi',
            'tanggal_pengajuan' => 'Tanggal Pengajuan',
            'periode_mulai' => 'Periode Mulai',
            'periode_selesai' => 'Periode Selesai',
            'total_pengajuan' => 'Total Pengajuan',
            'deskripsi' => 'Deskripsi',
            'nama_bank' => 'Nama Bank',
            'rekening_tujuan' => 'Rekening Tujuan',
            'penerima_manfaat_type' => 'Tipe Penerima Manfaat',
            'penerima_manfaat_id' => 'Penerima Manfaat',
            'penerima_manfaat_name' => 'Nama Penerima Manfaat',
            'penerima_manfaat_detail' => 'Detail Penerima Manfaat',
            'details' => 'Detail Pengajuan',
            'catatan' => 'Catatan',
        ];
    }
}
