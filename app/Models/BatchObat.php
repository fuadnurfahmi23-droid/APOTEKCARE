<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchObat extends Model
{
    /** Batch dianggap "segera kedaluwarsa" jika sisa waktunya <= angka ini (hari). */
    public const AMBANG_SEGERA_HARI = 90;

    protected $fillable = [
        'obat_id', 'nomor_batch', 'pemasok', 'tanggal_masuk',
        'tanggal_kedaluwarsa', 'jumlah_awal', 'sisa_stok',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_masuk' => 'date',
            'tanggal_kedaluwarsa' => 'date',
        ];
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    /** Batch yang boleh dijual: masih ada stok dan belum kedaluwarsa. */
    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('sisa_stok', '>', 0)
            ->whereDate('tanggal_kedaluwarsa', '>=', today());
    }

    /** Urutan FEFO: kedaluwarsa paling dekat lebih dulu. */
    public function scopeFefo(Builder $query): Builder
    {
        return $query->orderBy('tanggal_kedaluwarsa')->orderBy('id');
    }

    /** Status kedaluwarsa: aman / segera / kedaluwarsa. */
    public function getStatusKedaluwarsaAttribute(): string
    {
        if ($this->tanggal_kedaluwarsa->lt(today())) {
            return 'kedaluwarsa';
        }

        return today()->diffInDays($this->tanggal_kedaluwarsa) <= self::AMBANG_SEGERA_HARI
            ? 'segera'
            : 'aman';
    }
}
