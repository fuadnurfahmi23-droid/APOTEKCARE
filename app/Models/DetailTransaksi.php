<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    protected $fillable = [
        'transaksi_id', 'obat_id', 'batch_obat_id', 'jumlah', 'harga_satuan', 'subtotal',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(BatchObat::class, 'batch_obat_id');
    }
}
