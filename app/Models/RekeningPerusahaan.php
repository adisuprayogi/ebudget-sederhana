<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekeningPerusahaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bank_id',
        'nomor_rekening',
        'atas_nama',
        'cabang',
        'mata_uang',
        'saldo_awal',
        'is_default',
        'is_active',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the bank for this rekening.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Scope to only include active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get default account.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get formatted account number.
     */
    public function getNomorRekeningFormattedAttribute()
    {
        return wordwrap($this->nomor_rekening, 4, ' ', true);
    }

    /**
     * Get full account info.
     */
    public function getFullInfoAttribute()
    {
        return "{$this->bank->nama_bank} - {$this->nomor_rekening_formatted} - {$this->atas_nama}";
    }

    /**
     * Set as default account (unset others).
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_default) {
                static::where('bank_id', $model->bank_id)
                    ->where('id', '!=', $model->id ?? 0)
                    ->update(['is_default' => false]);
            }
        });
    }
}
