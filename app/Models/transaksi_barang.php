<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class transaksi_barang extends Model
{
    protected $table = 'transaksi_barang';
    protected $primaryKey = 'transaksi_barang_id';

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'qty',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'harga'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}
