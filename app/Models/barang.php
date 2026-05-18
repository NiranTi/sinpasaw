<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'tenant_id',
        'nama',
        'harga_jual',
        'stok',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'stok'       => 'integer',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function transaksi_barang(): HasMany
    {
        return $this->hasMany(transaksi_barang::class, 'barang_id', 'barang_id');
    }

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id', 'barang_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeHabis($query)
    {
        return $query->where('stok', '<=', 0);
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
