<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanLampiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pencairan_dana_id',
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
     * Get the user who created the lampiran.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the file URL attribute.
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->path_file);
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
