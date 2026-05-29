<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\transaksi;
use App\Models\transaksi_barang;
use App\Models\barang;
use App\Models\kasbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            abort(403, 'Tenant tidak ditemukan.');
        }

        $tenantId = $tenant->tenant_id;
        $periode = $request->get('periode', 'harian');

        // ── Rentang Waktu ──────────────────────────────────────────────
        [$startCurrent, $endCurrent, $startPrev, $endPrev] = $this->getRentang($periode);

        // ── Total Penjualan ────────────────────────────────────────────
        $totalPenjualan = Transaksi::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startCurrent, $endCurrent])
            ->where('status', 'selesai')
            ->sum('total');

        $totalPenjualanPrev = Transaksi::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startPrev, $endPrev])
            ->where('status', 'selesai')
            ->sum('total');

        $penjualanPersentase = $totalPenjualanPrev > 0
            ? round((($totalPenjualan - $totalPenjualanPrev) / $totalPenjualanPrev) * 100)
            : null;

        // ── Total Kasbon ───────────────────────────────────────────────
        $totalKasbon = Kasbon::where('tenant_id', $tenantId)
            ->where('tipe_kasbon', 'pelanggan')
            ->where('status', '!=', 'lunas')
            ->sum('sisa');

        // ── Stok Habis ─────────────────────────────────────────────────
        $stokHabis = Barang::where('tenant_id', $tenantId)
            ->where('stok', '<', 1)
            ->count();

        // ── Tren Pendapatan (7 hari terakhir) ─────────────────────────
        $trenData = $this->getTrenPendapatan($tenantId, $periode);

        // ── Barang Paling Laris ────────────────────────────────────────
        $barangLaris = transaksi_barang::selectRaw('barang_id, SUM(qty) as total_order, SUM(subtotal) as total_pendapatan')
            ->whereHas('transaksi', fn($q) => $q
                ->where('tenant_id', $tenantId)
                ->whereBetween('created_at', [$startCurrent, $endCurrent])
                ->where('status', 'selesai')
            )
            ->with('barang:barang_id,nama,harga_jual')
            ->groupBy('barang_id')
            ->orderByDesc('total_order')
            ->limit(4)
            ->get();

        // ── Transaksi Terbaru ──────────────────────────────────────────
        // ── Query Transaksi ───────────────────────────────────────
        $transaksi = Transaksi::query()
            ->with('transaksi_barang.barang:barang_id,nama')
            ->where('tenant_id', $tenantId);

        // FILTER
        if ($request->filled('filter')) {
            $transaksi->where('status', $request->filter);
        }

        // SORT
        switch ($request->sort) {

            case 'terlama':
                $transaksi->orderBy('created_at', 'asc');
                break;

            case 'terbesar':
                $transaksi->orderBy('total', 'desc');
                break;

            case 'terkecil':
                $transaksi->orderBy('total', 'asc');
                break;

            default:
                $transaksi->latest();
                break;
        }

        // PAGINATION
        $transaksi = $transaksi
            ->paginate(10)
            ->withQueryString();

        // ── Kasbon Pelanggan ───────────────────────────────────────────
        $kasbonPelanggan = Kasbon::where('tenant_id', $tenantId)
            ->where('tipe_kasbon', 'pelanggan')
            ->orderBy('status')          // belum_lunas muncul duluan
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ── Kasbon Supplier ────────────────────────────────────────────
        $kasbonSupplier = Kasbon::where('tenant_id', $tenantId)
            ->where('tipe_kasbon', 'supplier')
            ->orderBy('status')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('tenant.dashboard', compact(
            'tenant', 'periode',
            'totalPenjualan', 'penjualanPersentase',
            'totalKasbon', 'stokHabis',
            'trenData', 'barangLaris',
            'transaksi', 'kasbonPelanggan', 'kasbonSupplier',
        ));
    }

    // ── Helper: Rentang waktu berdasarkan periode ──────────────────────
    private function getRentang(string $periode): array
    {
        $now = Carbon::now();

        return match ($periode) {
            'tahunan' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfYear(),
            ],
            'bulanan' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ],
            default => [ // harian
                $now->copy()->startOfDay(),
                $now->copy()->endOfDay(),
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
            ],
        };
    }

    // ── Helper: Data tren pendapatan ───────────────────────────────────
    private function getTrenPendapatan(int $tenantId, string $periode): array
    {
        $now = Carbon::now();

        if ($periode === 'tahunan') {
            // 12 bulan terakhir
            $labels = [];
            $current = [];
            $previous = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                $labels[] = $month->translatedFormat('M');
                $current[] = (float) Transaksi::where('tenant_id', $tenantId)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('status', 'selesai')
                    ->sum('total');
                $prevMonth = $month->copy()->subYear();
                $previous[] = (float) Transaksi::where('tenant_id', $tenantId)
                    ->whereYear('created_at', $prevMonth->year)
                    ->whereMonth('created_at', $prevMonth->month)
                    ->where('status', 'selesai')
                    ->sum('total');
            }
        } elseif ($periode === 'bulanan') {
            // 4 minggu terakhir
            $labels = [];
            $current = [];
            $previous = [];
            for ($i = 3; $i >= 0; $i--) {
                $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                $weekEnd   = $weekStart->copy()->endOfWeek();
                $labels[]  = 'Minggu ' . ($now->copy()->diffInWeeks($weekStart) === 0 ? 'Ini' : ($i + 1));
                $current[]  = (float) Transaksi::where('tenant_id', $tenantId)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->where('status', 'selesai')
                    ->sum('total');
                $prevWeekStart = $weekStart->copy()->subYear();
                $previous[] = (float) Transaksi::where('tenant_id', $tenantId)
                    ->whereBetween('created_at', [$prevWeekStart, $prevWeekStart->copy()->endOfWeek()])
                    ->where('status', 'selesai')
                    ->sum('total');
            }
        } else {
            // 7 hari terakhir
            $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $labels = [];
            $current = [];
            $previous = [];
            for ($i = 6; $i >= 0; $i--) {
                $day = $now->copy()->subDays($i)->startOfDay();
                $labels[] = $days[$day->dayOfWeek === 0 ? 6 : $day->dayOfWeek - 1];
                $current[] = (float) Transaksi::where('tenant_id', $tenantId)
                    ->whereDate('created_at', $day->toDateString())
                    ->where('status', 'selesai')
                    ->sum('total');
                $prevDay = $day->copy()->subWeek();
                $previous[] = (float) Transaksi::where('tenant_id', $tenantId)
                    ->whereDate('created_at', $prevDay->toDateString())
                    ->where('status', 'selesai')
                    ->sum('total');
            }
        }

        return compact('labels', 'current', 'previous');
    }

    // ── Lunasi Kasbon ──────────────────────────────────────────────────
    public function lunasiKasbon(Request $request, int $kasbonId)
    {
        $tenant = Auth::user()->tenant;
        $kasbon = Kasbon::where('tenant_id', $tenant->tenant_id)->findOrFail($kasbonId);
        $kasbon->update(['sisa' => 0, 'status' => 'lunas']);

        return back()->with('alert', 'Kasbon ' . $kasbon->nama . ' berhasil dilunasi!');
    }
}

