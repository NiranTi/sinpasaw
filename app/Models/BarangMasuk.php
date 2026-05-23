<?php
// ── app/Models/BarangMasuk.php ───────────────────────────────────────────────
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $primaryKey = 'barang_masuk_id';

    protected $fillable = [
        'supplier_id', 'barang_id', 'tenant_id',
        'harga_beli', 'qty', 'total_harga',
    ];

    protected $casts = [
        'harga_beli'  => 'decimal:2',
        'total_harga' => 'decimal:2',
        'created_at'  => 'datetime',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }
}
