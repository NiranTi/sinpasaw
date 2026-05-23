<?php
// ── app/Models/Supplier.php ──────────────────────────────────────────────────
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    protected $fillable = ['nama_supplier', 'kontak'];

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'supplier_id', 'supplier_id');
    }

    public function kasbon(): HasMany
    {
        return $this->hasMany(Kasbon::class, 'supplier_id', 'supplier_id');
    }
}
