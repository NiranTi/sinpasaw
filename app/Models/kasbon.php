<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kasbon extends Model
{
    protected $table = 'kasbon';
    protected $primaryKey = 'kasbon_id';

    protected $fillable = [
        'tenant_id',
        'transaksi_id',
        'supplier_id',
        'tipe_kasbon',
        'nama',
        'kontak',
        'total',
        'sisa',
        'tenggat',
        'status',
    ];

    protected $casts = [
        'total'      => 'decimal:2',
        'sisa'       => 'decimal:2',
        'tenggat'    => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relasi ─────────────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeBelumLunas($query)
    {
        return $query->where('status', '!=', 'lunas');
    }

    public function scopePelanggan($query)
    {
        return $query->where('tipe_kasbon', 'pelanggan');
    }

    public function scopeSupplier($query)
    {
        return $query->where('tipe_kasbon', 'supplier');
    }

    // ── Accessors ──────────────────────────────────────────────────────

    public function getIsLunasAttribute(): bool
    {
        return $this->status === 'lunas';
    }
}
