<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $fillable = [
        'kode', 'nama', 'kategori', 'satuan', 'harga_beli', 'harga_jual', 'batas_minimum',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(BatchObat::class);
    }

    /**
     * Total stok = jumlah sisa stok batch yang belum kedaluwarsa.
     */
    public function getTotalStokAttribute(): int
    {
        return (int) $this->batches()->tersedia()->sum('sisa_stok');
    }

    /**
     * Status stok: habis / menipis / aman.
     */
    public function getStatusStokAttribute(): string
    {
        $stok = $this->total_stok;

        return match (true) {
            $stok <= 0 => 'habis',
            $stok <= $this->batas_minimum => 'menipis',
            default => 'aman',
        };
    }

    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        return $keyword
            ? $query->where(fn ($q) => $q->where('nama', 'like', "%{$keyword}%")
                ->orWhere('kode', 'like', "%{$keyword}%"))
            : $query;
    }
}
