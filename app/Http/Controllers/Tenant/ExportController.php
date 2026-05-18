<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportExcel()
    {
        $tenant = Auth::user()->tenant;

        $tenantId = $tenant->tenant_id;

        // contoh periode
        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        return Excel::download(
            new TransaksiExport($tenantId, $start, $end),
            'transaksi.xlsx'
        );
    }

    public function exportPdf()
    {
        $tenant = Auth::user()->tenant;

        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        $transaksi = Transaksi::with('transaksi_barang.barang')
            ->where('tenant_id', $tenant->tenant_id)
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('tenant.export.pdf', compact('transaksi'));

        return $pdf->download('laporan-transaksi.pdf');
    }
}
