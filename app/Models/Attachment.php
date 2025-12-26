<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_dana_id',
        'filename',
        'path',
        'size',
        'mime_type',
    ];

    /**
     * Get the pengajuan dana that owns the attachment.
     */
    public function pengajuanDana()
    {
        return $this->belongsTo(PengajuanDana::class);
    }
}
