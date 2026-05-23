<?php
// ── app/Http/Controllers/Tenant/KasirController.php ─────────────────────────
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiBarang;
use App\Models\Kasbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /* ── Halaman Kasir ─────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $tenant = Auth::user()->tenant;

        /* Ambil semua barang yang tersedia (stok > 0) */
        $query = Barang::where('tenant_id', $tenant->tenant_id)->where('stok', '>', 0);

        /* Filter pencarian nama barang */
        if ($search = $request->get('q')) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $barang = $query->orderBy('nama')->get();

        /* Generate kode transaksi berikutnya */
        $lastId = Transaksi::max('transaksi_id') ?? 0;
        $kodeTransaksi = 'PS-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        return view('tenant.kasir', compact('tenant', 'barang', 'kodeTransaksi'));
    }

    /* ── Proses Bayar ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'items'            => 'required|string', // JSON
            'metode_bayar'     => 'required|in:qris,tunai',
            'nominal'          => 'required|numeric|min:0',
            'nama_pelanggan'   => 'nullable|string|max:255',
            'kontak_pelanggan' => 'nullable|string|max:100',
            'print_struk'      => 'nullable|boolean',
        ]);

        $tenant = Auth::user()->tenant;
        $items  = json_decode($request->items, true);

        if (empty($items)) {
            return back()->withErrors(['items' => 'Keranjang tidak boleh kosong.']);
        }

        DB::transaction(function () use ($request, $tenant, $items) {
            /* ── Hitung total ── */
            $subtotal = collect($items)->sum(fn($i) => $i['qty'] * $i['harga']);
            $pajak    = 0; // bisa dikonfigurasi
            $total    = $subtotal + $pajak;
            $nominal  = (float) $request->nominal;
            $kembalian = max(0, $nominal - $total);
            $kurang    = max(0, $total - $nominal);

            /* ── Tentukan metode & status ── */
            $metodeBayar = $kurang > 0 ? 'kasbon' : $request->metode_bayar;
            $status      = $kurang > 0 ? 'diproses' : 'selesai';

            /* ── Buat transaksi ── */
            $transaksi = Transaksi::create([
                'tenant_id'    => $tenant->tenant_id,
                'total'        => $total,
                'jumlah_bayar' => $nominal,
                'kembalian'    => $kembalian,
                'metode_bayar' => $metodeBayar,
                'status'       => $status,
            ]);

            /* ── Buat transaksi_barang & kurangi stok ── */
            foreach ($items as $item) {
                TransaksiBarang::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'barang_id'    => $item['id'],
                    'qty'          => $item['qty'],
                    'harga'        => $item['harga'],
                    'subtotal'     => $item['qty'] * $item['harga'],
                ]);
                /* Kurangi stok, tidak boleh < 0 */
                Barang::where('barang_id', $item['id'])
                      ->where('stok', '>=', $item['qty'])
                      ->decrement('stok', $item['qty']);
            }

            /* ── Buat kasbon pelanggan jika kurang ── */
            if ($kurang > 0 && $request->filled('nama_pelanggan')) {
                Kasbon::create([
                    'tenant_id'    => $tenant->tenant_id,
                    'transaksi_id' => $transaksi->transaksi_id,
                    'tipe_kasbon'  => 'pelanggan',
                    'nama'         => $request->nama_pelanggan,
                    'kontak'       => $request->kontak_pelanggan,
                    'total'        => $total,
                    'sisa'         => $kurang,
                    'status'       => 'belum_lunas',
                    'tenggat'      => now()->addDays(30),
                ]);
            }
        });

        return redirect()->route('tenant.kasir')
                         ->with('alert', 'Pembayaran anda berhasil!');
    }
}
