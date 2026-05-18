{{-- resources/views/tenant/beranda.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda – {{ $tenant->nama_tenant }}</title>

    {{-- ── Google Fonts: Be Vietnam Pro + Manrope ── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Design tokens ─────────────────────────────────────── */
        :root {
            --primary:      #007E43;
            --primary-soft: #EAF7F1;
            --danger:       #BA1A1A;
            --danger-soft:  #FFDAD6;
            --orange:       #B66E00;
            --orange-soft:  #FBDDB0;
        }

        /* ── Base ──────────────────────────────────────────────── */
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #F8FAF9;
            color: #1a1a1a;
        }
        .font-manrope { font-family: 'Manrope', sans-serif; }

        /* ── Scrollbar ─────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }

        /* ── Stat cards ────────────────────────────────────────── */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef0ef;
            padding: 1.25rem 1.5rem;
        }
        .stat-card.danger-card {
            background-color: var(--danger-soft);
            border-color: #f5b8b8;
        }

        /* ── Status badges ─────────────────────────────────────── */
        .badge-success { background-color: var(--primary-soft); color: var(--primary); }
        .badge-orange  { background-color: var(--orange-soft);  color: var(--orange);  }
        .badge-danger  { background-color: var(--danger-soft);  color: var(--danger);  }
        .badge-gray    { background-color: #f3f4f6; color: #6b7280; }

        /* ── Bar chart ─────────────────────────────────────────── */
        .bar-wrap  { display: flex; align-items: flex-end; gap: 8px; height: 120px; }
        .bar-col   { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 4px; height: 100%; }
        .bar       { width: 100%; border-radius: 6px 6px 0 0; transition: opacity .15s; }
        .bar:hover { opacity: .75; }
        .bar-label { font-size: 10px; color: #9ca3af; white-space: nowrap; }

        /* ── Periode toggle ────────────────────────────────────── */
        .periode-btn {
            padding: 6px 16px;
            border-radius: 999px;
            cursor: pointer;
            border: none;
            transition: all .15s;
            color: #6b7280;
            background: transparent;
        }
        .periode-btn.active {
            background-color: var(--primary);
            color: #ffffff;
        }

        /* ── Transaction table ─────────────────────────────────── */
        .tx-table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; }
        .tx-table td { font-size: 13px; }
        .tx-table tr:hover td { background-color: #f9fafb; }

        /* ── Section wrapper card ──────────────────────────────── */
        .section-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef0ef;
        }

        /* ── Kasbon card ───────────────────────────────────────── */
        .kasbon-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #eef0ef;
            padding: 1rem 1.25rem;
        }

        /* ── Transaction code badges ───────────────────────────── */
        .kode-ps { color: var(--primary); font-weight: 600; font-size: 12px; }
        .kode-sp { color: var(--orange);  font-weight: 600; font-size: 12px;
                   background-color: var(--orange-soft); padding: 2px 6px; border-radius: 4px; }

        /* ══════════════════════════════════════════════════════════
           BUTTON DESIGN SYSTEM — sesuai gambar Button.png
           Urutan dari atas ke bawah: Default → Hover → Active

           btn-outline (putih):
             Default → bg putih, border abu
             Hover   → bg putih, border hijau, teks hijau
             Active  → bg hijau, teks putih  (klik = active)

           btn-primary (hijau):
             Default → bg hijau, teks putih
             Hover   → bg hijau lebih gelap (opacity 88%)
             Active  → TIDAK ada perubahan (sesuai spec: hanya hover)
           ══════════════════════════════════════════════════════════ */

        /* Tombol outline/putih */
        .btn-outline {
            display: inline-flex; align-items: center; gap: 6px;
            border: 1.5px solid #d1d5db;
            background: #fff;
            color: #374151;
            padding: 7px 16px;
            border-radius: 8px; font-size: 13px; font-weight: 500;
            cursor: pointer;
            transition: border-color .15s, background .15s, color .15s;
            text-decoration: none;
        }
        /* Hover: border dan teks jadi hijau */
        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        /* Active (klik): bg jadi hijau, teks putih */
        .btn-outline:active {
            background-color: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Tombol primary/hijau */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background-color: var(--primary);
            color: #ffffff;
            padding: 10px 22px; border-radius: 999px;
            font-size: 14px; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none;
            transition: background-color .15s;
        }
        /* Hover: sedikit lebih gelap */
        .btn-primary:hover { background-color: #006435; }
        /* Active: TIDAK ada state khusus (sesuai spec) */

        /* Tombol Lunasi (varian pill outline) */
        .btn-lunasi {
            border: 1.5px solid #d1d5db; background: #fff;
            color: #374151; padding: 5px 16px;
            border-radius: 999px; font-size: 12px; font-weight: 500;
            cursor: pointer; transition: border-color .15s, background .15s, color .15s;
        }
        .btn-lunasi:hover { border-color: var(--primary); color: var(--primary); }
        .btn-lunasi:active { background-color: var(--primary); color: #fff; border-color: var(--primary); }

        /* ── Hamburger button (mobile only) ────────────────────── */
        .hamburger-btn {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border-radius: 10px; border: 1.5px solid #e5e7eb;
            background: #ffffff; cursor: pointer;
            transition: border-color .15s, background .15s;
        }
        .hamburger-btn:hover { border-color: var(--primary); background: var(--primary-soft); }
        .hamburger-btn:active { background: var(--primary); border-color: var(--primary); }
        .hamburger-btn:active svg { color: #fff; }
    </style>
</head>

<body>
    {{-- ── Sidebar component ─── --}}
    <x-tenant.sidebar />

    {{-- ══════════════════════════════════════════════════════════════
         MAIN CONTENT
         • Mobile (<lg)  : ml-0, padding lebih kecil (p-4)
         • Desktop (lg+) : ml-56 (sesuai lebar sidebar), padding p-8
         ══════════════════════════════════════════════════════════════ --}}
    <main class="lg:ml-56 min-h-screen p-4 lg:p-8">

        {{-- ── Mobile top bar: hamburger + nama toko ────────────── --}}
        {{-- Hanya tampil di mobile (<lg), desktop tidak perlu karena sidebar selalu visible --}}
        <div class="flex items-center gap-3 mb-5 lg:hidden">
            <button onclick="openSidebar()" class="hamburger-btn" aria-label="Buka menu">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-manrope font-bold text-gray-800 text-base">{{ $tenant->nama_tenant }}</span>
        </div>

        {{-- ── Flash success message ─────────────────────────────── --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium"
                 style="background-color:var(--primary-soft);color:var(--primary);">
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Page header ───────────────────────────────────────── --}}
        {{-- Flex row di desktop, column di mobile --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase mb-1" style="color:var(--primary);">
                    SELAMAT DATANG KEMBALI
                </p>
                {{-- Judul lebih kecil di mobile (text-2xl), besar di desktop (text-4xl) --}}
                <h1 class="font-manrope text-2xl lg:text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $tenant->nama_tenant }}
                </h1>
                <p class="text-sm font-semibold mt-0.5" style="color:var(--primary);">
                    {{ $tenant->blok }}
                </p>
                <p class="text-sm text-gray-500 mt-2 max-w-md">
                    Kelola transaksi hari ini dengan cepat dan pantau stok bahan makanan Anda secara real-time.
                </p>
            </div>

            {{-- Avatar —desktop saja yang di kanan atas --}}
            @if ($tenant->foto)
                <img src="{{ asset($tenant->foto) }}"
                     alt="{{ $tenant->nama_tenant }}"
                     class="w-12 h-12 rounded-full object-cover border-2 border-white shadow left">
            @endif
        </div>

        {{-- ── CTA: Buka Kasir ───────────────────────────────────── --}}
        <a href="{{ route('tenant.kasir') }}" class="btn-primary mb-8 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Buka Kasir
        </a>

        {{-- ══════════════════════════════════════════════════════════
             PERFORMA TOKO
             ══════════════════════════════════════════════════════════ --}}
        <div class="mb-2">
            <h2 class="font-manrope text-xl font-bold text-gray-900">Performa Toko</h2>
            <p class="text-xs text-gray-400 mt-0.5">Laporan performa toko Anda</p>
        </div>

        {{-- ── Periode toggle ────────────────────────────────────── --}}
        <form method="GET" action="{{ route('tenant.dashboard') }}" id="periodeForm" class="mb-5">
            <div class="inline-flex items-center gap-1.5 bg-[#F4F4EF] rounded-full p-1.5 text-xs">
                @foreach (['tahunan' => 'Tahunan', 'bulanan' => 'Bulanan', 'harian' => 'Harian'] as $val => $label)
                    <button type="button" onclick="setPeriode('{{ $val }}')"
                            class="periode-btn {{ $periode === $val ? 'active' : '' }}"
                            id="btn-{{ $val }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <input type="hidden" name="periode" id="periodeInput" value="{{ $periode }}">
        </form>

        {{-- ── Stat cards grid ────────────────────────────────────
             Mobile: 1 kolom
             sm:    2 kolom
             lg:    3 kolom
        ────────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

            {{-- Total Penjualan --}}
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background-color:var(--primary-soft);">
                        <svg class="w-5 h-5" style="color:var(--primary);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    {{-- Badge persentase perubahan --}}
                    @if (!is_null($penjualanPersentase))
                        <span class="text-xs font-semibold px-2 py-1 rounded-full"
                              style="{{ $penjualanPersentase >= 0
                                ? 'background-color:var(--primary-soft);color:var(--primary);'
                                : 'background-color:var(--danger-soft);color:var(--danger);' }}">
                            {{ $penjualanPersentase >= 0 ? '+' : '' }}{{ $penjualanPersentase }}%
                            dari {{ $periode === 'harian' ? 'Kemarin' : ($periode === 'bulanan' ? 'Bulan Lalu' : 'Tahun Lalu') }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">TOTAL PENJUALAN</p>
                <p class="font-manrope text-2xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                </p>
            </div>

            {{-- Total Kasbon --}}
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="background-color:var(--orange-soft);">
                        <svg class="w-5 h-5" style="color:var(--orange);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                    </div>
                    <a href="{{ route('tenant.kasbon') }}"
                       class="text-xs font-medium flex items-center gap-1"
                       style="color:var(--orange);">
                        Selengkapnya
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">TOTAL KASBON</p>
                <p class="font-manrope text-2xl font-bold text-gray-900 mt-1">
                    Rp {{ number_format($totalKasbon, 0, ',', '.') }}
                </p>
            </div>

            {{-- Peringatan Stok --}}
            {{-- sm:col-span-2 lg:col-span-1 agar tidak ada card menggantung di layar sm --}}
            <div class="stat-card sm:col-span-2 lg:col-span-1 {{ $stokHabis > 0 ? 'danger-card' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                         style="{{ $stokHabis > 0 ? 'background-color:var(--danger);' : 'background-color:var(--primary-soft);' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    @if ($stokHabis > 0)
                        <a href="{{ route('tenant.stok') }}"
                           class="text-xs font-semibold flex items-center gap-1"
                           style="color:var(--danger);">
                            Tambah Stok
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <p class="text-xs font-medium uppercase tracking-wide" style="color:var(--danger);">PERINGATAN STOK</p>
                <p class="font-manrope text-2xl font-bold mt-1"
                   style="{{ $stokHabis > 0 ? 'color:var(--danger);' : 'color:#1a1a1a;' }}">
                    {{ $stokHabis > 0 ? $stokHabis . ' Barang Habis' : 'Stok Aman' }}
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TREN PENDAPATAN + BARANG LARIS
             Mobile: 1 kolom (chart full, laris full)
             lg+:    2/3 chart, 1/3 laris
             ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

            {{-- Tren Pendapatan Penjualan --}}
            <div class="lg:col-span-2 section-card p-5 lg:p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-semibold text-gray-800 text-sm">Tren Pendapatan Penjualan</h3>
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:var(--primary);"></span>
                            Saat ini
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full inline-block bg-gray-300"></span>
                            Sebelumnya
                        </span>
                    </div>
                </div>

                {{-- Bar chart: bar tinggi proporsional terhadap nilai maksimum --}}
                <div class="bar-wrap">
                    @php
                        $maxVal = max(array_merge($trenData['current'], $trenData['previous'], [1]));
                    @endphp
                    @foreach ($trenData['labels'] as $i => $label)
                        @php
                            $curH  = $maxVal > 0 ? round(($trenData['current'][$i]  / $maxVal) * 100) : 0;
                            $prevH = $maxVal > 0 ? round(($trenData['previous'][$i] / $maxVal) * 100) : 0;
                            $isBiggest = $trenData['current'][$i] === max($trenData['current']); // Highlight bar tertinggi
                        @endphp
                        <div class="bar-col">
                            <div style="flex:1; display:flex; align-items:flex-end; gap:3px; width:100%;">
                                {{-- Bar sebelumnya (abu muda) --}}
                                <div class="bar" style="height:{{ max($prevH,4) }}%; background:#D1E8DC; flex:1;"></div>
                                {{-- Bar saat ini (hijau, lebih gelap jika tertinggi) --}}
                                <div class="bar" style="height:{{ max($curH,4) }}%; background:{{ $isBiggest ? 'var(--primary)' : '#A8D5BE' }}; flex:1;"></div>
                            </div>
                            <span class="bar-label">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Barang Paling Laris --}}
            <div class="section-card p-5 lg:p-6">
                <h3 class="font-semibold text-gray-800 text-sm mb-4">Barang Paling Laris</h3>
                <div class="space-y-4">
                    @forelse ($barangLaris as $item)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->barang->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $item->total_order }} order</p>
                            </div>
                            <span class="text-sm font-bold" style="color:var(--primary);">
                                Rp {{ number_format($item->barang->harga_jual ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">Belum ada data</p>
                    @endforelse
                </div>
                @if ($barangLaris->count())
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('tenant.stok') }}"
                           class="btn-outline w-full justify-center text-xs font-semibold">
                            LIHAT SEMUA BARANG
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TABEL TRANSAKSI
             ══════════════════════════════════════════════════════════ --}}
        <div class="section-card mb-6">

            {{-- Toolbar: filter, sort, export --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 lg:px-6 py-4 border-b border-gray-100">
                {{-- Kiri: Filter + Urutkan --}}
                <div class="flex items-center gap-2">
                    <button class="btn-outline text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filter
                    </button>
                    <button class="btn-outline text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        Urutkan
                    </button>
                </div>
                {{-- Kanan: Export --}}
                <div class="flex items-center gap-2">
                    <button class="btn-outline text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </button>
                    <button class="btn-outline text-xs">
                        <svg class="w-3.5 h-3.5" style="color:#16a34a;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel
                    </button>
                </div>
            </div>

            {{-- Table: scroll horizontal di mobile --}}
            <div class="overflow-x-auto">
                <table class="tx-table w-full min-w-[640px]">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-4 lg:px-6 py-3 text-left">TANGGAL</th>
                            <th class="px-4 py-3 text-left">KODE TRANSAKSI</th>
                            <th class="px-4 py-3 text-left">BARANG</th>
                            <th class="px-4 py-3 text-right">TOTAL HARGA</th>
                            <th class="px-4 py-3 text-left">PEMBAYARAN</th>
                            <th class="px-4 py-3 text-left">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($transaksi as $tx)
                            @php
                                $namaBarang = collect($tx->transaksi_barang ?? []) ->pluck('barang.nama') ->filter() ->implode(', ');
                                $namaBarangShort = strlen($namaBarang) > 30 ? substr($namaBarang, 0, 30) . '...' : $namaBarang;
                                $kodePrefix      = strtolower($tx->metode_bayar ?? '') === 'kasbon' ? 'SP' : 'PS';
                                $kode            = '#' . $kodePrefix . '-' . str_pad($tx->transaksi_id, 5, '0', STR_PAD_LEFT);
                                $isKasbon        = $kodePrefix === 'SP';
                                $colorClass      = match(strtolower($tx->status)) {
                                    'selesai'    => 'badge-success',
                                    'diproses'   => 'badge-orange',
                                    'dibatalkan' => 'badge-danger',
                                    default      => 'badge-gray'
                                };
                                $dotColor = match(strtolower($tx->status)) {
                                    'selesai'    => 'var(--primary)',
                                    'diproses'   => 'var(--orange)',
                                    'dibatalkan' => 'var(--danger)',
                                    default      => '#9ca3af'
                                };
                            @endphp
                            <tr>
                                <td class="px-4 lg:px-6 py-3">
                                    <p class="text-gray-800 font-medium whitespace-nowrap">{{ $tx->created_at->translatedFormat('d M Y') }}</p>
                                    <p class="text-gray-400 text-xs">{{ $tx->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($isKasbon)
                                        <span class="kode-sp">{{ $kode }}</span>
                                    @else
                                        <span class="kode-ps">{{ $kode }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-[180px]">{{ $namaBarangShort ?: '-' }}</td>
                                <td class="px-4 py-3 text-right font-semibold whitespace-nowrap"
                                    style="{{ strtolower($tx->status) === 'dibatalkan' ? 'color:#9ca3af; text-decoration:line-through;' : 'color:#111;' }}">
                                    {{ strtolower($tx->status) !== 'dibatalkan' ? '+' : '' }}
                                    Rp {{ number_format($tx->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 capitalize">{{ ucfirst($tx->metode_bayar ?? '-') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $colorClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:{{ $dotColor }};"></span>
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 lg:px-6 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-400">
                    Menampilkan {{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} transaksi
                </p>
                <div class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if ($transaksi->onFirstPage())
                        <span class="px-2.5 py-1.5 rounded-lg text-xs text-gray-300 cursor-not-allowed">‹</span>
                    @else
                        <a href="{{ $transaksi->previousPageUrl() }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-100 transition-colors">‹</a>
                    @endif

                    @foreach ($transaksi->getUrlRange(1, min($transaksi->lastPage(), 3)) as $page => $url)
                        <a href="{{ $url }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs font-medium transition-colors
                                  {{ $page == $transaksi->currentPage() ? 'text-white' : 'text-gray-600 hover:bg-gray-100' }}"
                           style="{{ $page == $transaksi->currentPage() ? 'background:var(--primary);' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($transaksi->lastPage() > 3)
                        <span class="px-2 text-xs text-gray-400">...</span>
                        <a href="{{ $transaksi->url($transaksi->lastPage()) }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-100 transition-colors">
                            {{ $transaksi->lastPage() }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if ($transaksi->hasMorePages())
                        <a href="{{ $transaksi->nextPageUrl() }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-100 transition-colors">›</a>
                    @else
                        <span class="px-2.5 py-1.5 rounded-lg text-xs text-gray-300 cursor-not-allowed">›</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             KASBON SECTION
             Mobile: 1 kolom — lg+: 2 kolom
             ══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Kasbon Pelanggan --}}
            <div>
                <h3 class="font-manrope font-bold text-gray-900 mb-3">Kasbon Pelanggan</h3>
                <div class="space-y-2">
                    @forelse ($kasbonPelanggan as $kb)
                        {{-- Flex col di mobile kecil, row di sm+ --}}
                        <div class="kasbon-card flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="font-semibold text-sm text-gray-800">{{ $kb->nama }}</p>
                                <p class="text-base font-bold mt-0.5" style="color:var(--danger);">
                                    Rp {{ number_format($kb->sisa, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400 font-medium uppercase mt-0.5">BELUM LUNAS</p>
                            </div>
                            <div class="sm:text-right">
                                <p class="text-xs text-gray-400 mb-2">
                                    {{ $kb->created_at->format('H:i') }} WIB – {{ $kb->created_at->translatedFormat('d M Y') }}
                                </p>
                                <form method="POST" action="{{ route('tenant.kasbon.lunasi', $kb->kasbon_id) }}"
                                      onsubmit="return confirm('Lunasi kasbon {{ $kb->nama }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-lunasi">Lunasi</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="kasbon-card text-center py-8 text-sm text-gray-400">
                            Tidak ada kasbon pelanggan aktif.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Kasbon Supplier --}}
            <div>
                <h3 class="font-manrope font-bold text-gray-900 mb-3">Kasbon Supplier</h3>
                <div class="space-y-2">
                    @forelse ($kasbonSupplier as $kb)
                        <div class="kasbon-card flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="font-semibold text-sm text-gray-800">{{ $kb->nama }}</p>
                                <p class="text-base font-bold mt-0.5" style="color:var(--danger);">
                                    Rp {{ number_format($kb->sisa, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400 font-medium uppercase mt-0.5">BELUM LUNAS</p>
                            </div>
                            <div class="sm:text-right">
                                <p class="text-xs text-gray-400 mb-2">
                                    {{ $kb->created_at->format('H:i') }} WIB – {{ $kb->created_at->translatedFormat('d M Y') }}
                                </p>
                                <form method="POST" action="{{ route('tenant.kasbon.lunasi', $kb->kasbon_id) }}"
                                      onsubmit="return confirm('Lunasi kasbon {{ $kb->nama }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-lunasi">Lunasi</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="kasbon-card text-center py-8 text-sm text-gray-400">
                            Tidak ada kasbon supplier aktif.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Spacer bawah: hanya pb kecil agar konten tidak terasa menggantung --}}
        <div class="pb-8"></div>

    </main>

    <script>
        /* Ganti periode → submit form otomatis */
        function setPeriode(val) {
            document.getElementById('periodeInput').value = val;
            document.querySelectorAll('.periode-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + val).classList.add('active');
            document.getElementById('periodeForm').submit();
        }
    </script>
</body>
</html>
