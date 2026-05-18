<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $table = 'tenant';
    protected $primaryKey = 'tenant_id';

    protected $fillable = [
        'user_id',
        'nama_tenant',
        'kategori',
        'blok',
        'foto',
        'deskripsi',
        'lama_kontrak',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'tenant_id', 'tenant_id');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'tenant_id', 'tenant_id');
    }

    public function kasbon(): HasMany
    {
        return $this->hasMany(Kasbon::class, 'tenant_id', 'tenant_id');
    }

    public function denah(): HasMany
    {
        return $this->hasMany(Denah::class, 'tenant_id', 'tenant_id');
    }
}
