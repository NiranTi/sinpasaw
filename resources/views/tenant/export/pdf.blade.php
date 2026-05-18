<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #007e43;
            color: white;
            padding: 10px;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background: #f5f5f5;
        }
    </style>
</head>
<body>

    <h2>Laporan Transaksi</h2>

    <table>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>KODE TRANSAKSI</th>
                <th>BARANG</th>
                <th>TOTAL HARGA</th>
                <th>PEMBAYARAN</th>
                <th>STATUS</th>
            </tr>
        </thead>

        <tbody>
            @foreach($transaksi as $item)

                <tr>
                    <td>
                        {{ $item->created_at->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $item->transaksi_id }}
                    </td>

                    <td>
                        {{ $item->transaksi_barang->pluck('barang.nama')->implode(', ') }}
                    </td>

                    <td>
                        Rp {{ number_format($item->total, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ ucfirst($item->metode_bayar ?? '-') }}
                    </td>

                    <td>
                        {{ ucfirst($item->status) }}
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>

</body>
</html>
