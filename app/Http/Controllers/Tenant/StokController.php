<?php
// ── app/Http/Controllers/Tenant/StokController.php ──────────────────────────
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Kasbon;
use App\Models\Supplier;
use App\Models\transaksi_barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    /* ── Halaman Manajemen Stok ─────────────────────────────────────── */
    public function index(Request $request)
    {
        $tenant    = Auth::user()->tenant;
        $filter    = $request->get('filter', 'semua');
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        /* ── Query barang dengan filter status stok ── */
        $query = Barang::where('tenant_id', $tenant->tenant_id);

        $query = match($filter) {
            'tersedia'    => $query->where('stok', '>', 5),
            'hampirhabis' => $query->whereBetween('stok', [1, 5]),
            'habis'       => $query->where('stok', '<=', 0),
            default       => $query, // semua
        };

        /* ── Hitung total penjualan per barang ── */
        $barang = $query->withCount([
            'transaksi_barang as total_terjual' => fn($q) => $q->selectRaw('SUM(qty)'),
        ])->orderBy('nama')->paginate(10)->withQueryString();

        /* Daftar barang untuk dropdown di form Perbarui Stok */
        $semuaBarang = Barang::where('tenant_id', $tenant->tenant_id)
                             ->orderBy('nama')->get();

        return view('tenant.stok', compact(
            'tenant', 'barang', 'filter', 'suppliers', 'semuaBarang'
        ));
    }

    /* ── Tambah Barang Baru ─────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'unit'       => 'nullable|string|max:50',
        ]);

        $tenant = Auth::user()->tenant;

        Barang::create([
            'tenant_id'  => $tenant->tenant_id,
            'nama'       => $request->nama,
            'harga_jual' => $request->harga_jual,
            'stok'       => $request->stok,
            // unit bisa ditambah ke migrasi jika belum ada
        ]);

        return redirect()->route('tenant.stok')
                         ->with('alert', 'Barang berhasil ditambahkan!');
    }

    /* ── Perbarui Stok (Restock dari Supplier) ─────────────────────── */
    public function restock(Request $request)
    {
        $request->validate([
            'barang_id'   => 'required|integer|exists:barang,barang_id',
            'supplier_id' => 'nullable|integer|exists:supplier,supplier_id',
            'qty'         => 'required|integer|min:1',
            'harga_beli'  => 'required|numeric|min:0',
            'metode_bayar'=> 'required|in:qris,tunai',
            'bayar'       => 'required|numeric|min:0',
            'nama_supplier_kasbon'  => 'nullable|string|max:255',
            'kontak_supplier_kasbon'=> 'nullable|string|max:100',
        ]);

        $tenant    = Auth::user()->tenant;
        $totalBeli = $request->qty * $request->harga_beli;
        $bayar     = (float) $request->bayar;
        $kurang    = max(0, $totalBeli - $bayar);

        DB::transaction(function () use ($request, $tenant, $totalBeli, $kurang) {
            /* ── Catat barang masuk ── */
            BarangMasuk::create([
                'tenant_id'   => $tenant->tenant_id,
                'barang_id'   => $request->barang_id,
                'supplier_id' => $request->supplier_id,
                'harga_beli'  => $request->harga_beli,
                'qty'         => $request->qty,
                'total_harga' => $totalBeli,
            ]);

            /* ── Tambah stok barang ── */
            Barang::where('barang_id', $request->barang_id)
                  ->increment('stok', $request->qty);

            /* ── Buat kasbon supplier jika bayar kurang ── */
            if ($kurang > 0) {
                $namaSupplier = $request->nama_supplier_kasbon ?? 'Supplier';
                Kasbon::create([
                    'tenant_id'   => $tenant->tenant_id,
                    'supplier_id' => $request->supplier_id,
                    'tipe_kasbon' => 'supplier',
                    'nama'        => $namaSupplier,
                    'kontak'      => $request->kontak_supplier_kasbon,
                    'total'       => $totalBeli,
                    'sisa'        => $kurang,
                    'status'      => 'belum_lunas',
                    'tenggat'     => now()->addDays(30),
                ]);
            }
        });

        return redirect()->route('tenant.stok')
                         ->with('alert', 'Stok berhasil diperbarui!');
    }

    /* ── Hapus Barang ───────────────────────────────────────────────── */
    public function destroy(int $id)
    {
        $tenant = Auth::user()->tenant;
        $barang = Barang::where('tenant_id', $tenant->tenant_id)->findOrFail($id);
        $barang->delete();

        return redirect()->route('tenant.stok')
                         ->with('alert', 'Barang berhasil dihapus!');
    }
}
