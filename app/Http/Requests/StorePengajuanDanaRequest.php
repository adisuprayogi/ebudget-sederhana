<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\PenerimaManfaatService;

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
        return [
            'judul_pengajuan' => 'required|string|max:255',
            'jenis_pengajuan' => 'required|in:kegiatan,pengadaan,pembayaran,honorarium,sewa,konsumsi,lainnya',
            'program_kerja_id' => 'required|exists:program_kerjas,id',
            'divisi_id' => 'required|exists:divisis,id',
            'tanggal_pengajuan' => 'required|date',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'total_pengajuan' => 'required|numeric|min:1000',
            'deskripsi' => 'nullable|string|max:1000',

            // Penerima manfaat validation
            'penerima_manfaat_type' => 'required|string',
            'penerima_manfaat_id' => 'nullable|required_if:penerima_manfaat_type,pegawai,vendor,pic_kegiatan|integer',
            'penerima_manfaat_name' => 'nullable|required_if:penerima_manfaat_type,pengaju,internal,external,non_pegawai|string|max:255',
            'penerima_manfaat_detail' => 'nullable|string',

            // Detail pengajuan validation
            'details' => 'required|array|min:1',
            'details.*.uraian' => 'required|string|max:500',
            'details.*.volume' => 'required|numeric|min:0.01',
            'details.*.satuan' => 'required|string|max:50',
            'details.*.harga_satuan' => 'required|numeric|min:1000',
            'details.*.subtotal' => 'required|numeric|min:1000',

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
            'judul_pengajuan.required' => 'Judul pengajuan wajib diisi',
            'jenis_pengajuan.required' => 'Jenis pengajuan wajib dipilih',
            'jenis_pengajuan.in' => 'Jenis pengajuan tidak valid',
            'program_kerja_id.required' => 'Program kerja wajib dipilih',
            'program_kerja_id.exists' => 'Program kerja tidak valid',
            'divisi_id.required' => 'Divisi wajib dipilih',
            'divisi_id.exists' => 'Divisi tidak valid',
            'tanggal_pengajuan.required' => 'Tanggal pengajuan wajib diisi',
            'tanggal_pengajuan.date' => 'Format tanggal tidak valid',
            'periode_mulai.required' => 'Periode mulai wajib diisi',
            'periode_mulai.date' => 'Format periode mulai tidak valid',
            'periode_selesai.required' => 'Periode selesai wajib diisi',
            'periode_selesai.date' => 'Format periode selesai tidak valid',
            'periode_selesai.after_or_equal' => 'Periode selesai harus setelah atau sama dengan periode mulai',
            'total_pengajuan.required' => 'Total pengajuan wajib diisi',
            'total_pengajuan.numeric' => 'Total pengajuan harus berupa angka',
            'total_pengajuan.min' => 'Total pengajuan minimal Rp 1.000',

            'penerima_manfaat_type.required' => 'Tipe penerima manfaat wajib dipilih',
            'penerima_manfaat_id.required_if' => 'Penerima manfaat wajib dipilih',
            'penerima_manfaat_name.required_if' => 'Nama penerima manfaat wajib diisi',

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
            $this->validatePenerimaManfaat($validator);
            $this->validateDetailsSum($validator);
            $this->validatePaguAvailability($validator);
        });
    }

    /**
     * Validate penerima manfaat selection
     */
    protected function validatePenerimaManfaat($validator)
    {
        $jenisPengajuan = $this->input('jenis_pengajuan');
        $penerimaType = $this->input('penerima_manfaat_type');
        $penerimaId = $this->input('penerima_manfaat_id');
        $penerimaName = $this->input('penerima_manfaat_name');

        if (!PenerimaManfaatService::validatePenerimaManfaat($jenisPengajuan, $penerimaType, $penerimaId)) {
            $validator->errors()->add('penerima_manfaat_type', 'Pilihan penerima manfaat tidak valid untuk jenis pengajuan ini');
        }

        // Special validation for pembayaran type
        if ($jenisPengajuan === 'pembayaran') {
            if (!in_array($penerimaType, ['pegawai', 'internal', 'external', 'non_pegawai'])) {
                $validator->errors()->add('penerima_manfaat_type', 'Untuk pembayaran, penerima harus pegawai atau user internal/external');
            }

            if ($penerimaType === 'external' && empty($penerimaName)) {
                $validator->errors()->add('penerima_manfaat_name', 'Nama penerima wajib diisi untuk pembayaran external');
            }
        }
    }

    /**
     * Validate that total_pengajuan matches sum of details
     */
    protected function validateDetailsSum($validator)
    {
        $totalPengajuan = $this->input('total_pengajuan');
        $details = $this->input('details', []);

        if (!empty($details)) {
            $totalDetails = array_sum(array_column($details, 'subtotal'));

            if (abs($totalPengajuan - $totalDetails) > 100) { // Allow small difference due to rounding
                $validator->errors()->add('total_pengajuan', 'Total pengajuan tidak sesuai dengan jumlah detail pengajuan');
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

        // Get pagu for this divisi and program
        $pagu = \App\Models\PenetapanPagu::where('divisi_id', $divisiId)
            ->whereHas('programKerjas', function ($query) use ($programKerjaId) {
                $query->where('program_kerjas.id', $programKerjaId);
            })
            ->first();

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
            'penerima_manfaat_type' => 'Tipe Penerima Manfaat',
            'penerima_manfaat_id' => 'Penerima Manfaat',
            'penerima_manfaat_name' => 'Nama Penerima Manfaat',
            'penerima_manfaat_detail' => 'Detail Penerima Manfaat',
            'details' => 'Detail Pengajuan',
            'catatan' => 'Catatan',
        ];
    }
}