<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    protected $tenantId;
    protected $start;
    protected $end;

    public function __construct($tenantId, $start, $end)
    {
        $this->tenantId = $tenantId;
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $transaksi = Transaksi::with('transaksi_barang.barang')
            ->where('tenant_id', $this->tenantId)
            ->whereBetween('created_at', [$this->start, $this->end])
            ->latest()
            ->get();

        return $transaksi->map(function ($item) {

            $barang = $item->transaksi_barang
                ->pluck('barang.nama')
                ->implode(', ');

            return [
                'tanggal'         => $item->created_at->format('d-m-Y'),
                'kode_transaksi'  => $item->transaksi_id,
                'barang'          => $barang,
                'total_harga'     => 'Rp ' . number_format($item->total, 0, ',', '.'),
                'pembayaran'      => ucfirst($item->metode_bayar ?? '-'),
                'status'          => ucfirst($item->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'TANGGAL',
            'KODE TRANSAKSI',
            'BARANG',
            'TOTAL HARGA',
            'PEMBAYARAN',
            'STATUS',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}
