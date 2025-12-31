<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HonorariumLampiran extends Model
{
    protected $fillable = [
        'pencairan_dana_id',
        'honorarium_detail_id',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file',
        'created_by',
    ];

    protected $casts = [
        'ukuran_file' => 'integer',
    ];

    /**
     * Get the pencairan dana that owns the lampiran.
     */
    public function pencairanDana()
    {
        return $this->belongsTo(PencairanDana::class);
    }

    /**
     * Get the honorarium detail that owns the lampiran.
     */
    public function honorariumDetail()
    {
        return $this->belongsTo(HonorariumDetail::class);
    }

    /**
     * Get the user who created the lampiran.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
