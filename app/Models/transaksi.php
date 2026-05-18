<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    protected $fillable = [
        'tenant_id',
        'total',
        'jumlah_bayar',
        'kembalian',
        'metode_bayar',
        'status',
    ];

    protected $casts = [
        'total'       => 'decimal:2',
        'jumlah_bayar'=> 'decimal:2',
        'kembalian'   => 'decimal:2',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }
    public function transaksi_barang()
    {
        return $this->hasMany(transaksi_barang::class, 'transaksi_id', 'transaksi_id');
    }
    public function kasbon(): HasOne
    {
        return $this->hasOne(Kasbon::class, 'transaksi_id', 'transaksi_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'transaksi_id', 'transaksi_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ── Accessors ──────────────────────────────────────────────────────

    public function getNamaBarangAttribute(): string
    {
        return $this->transaksi_barang
            ->pluck('barang.nama')
            ->filter()
            ->implode(', ');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'selesai'    => 'success',
            'diproses'   => 'orange',
            'dibatalkan' => 'danger',
            default      => 'gray',
        };
    }

    public function getKodeTransaksiAttribute(): string
    {
        $prefix = str_starts_with($this->metode_bayar ?? '', 'kasbon') ? 'SP' : 'PS';
        return "#$prefix-" . str_pad($this->transaksi_id, 5, '0', STR_PAD_LEFT);
    }
}
