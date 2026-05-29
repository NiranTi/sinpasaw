@extends('layouts.tenant')

@section('title', 'Beranda – ' . $tenant->nama_tenant)

@section('styles')
    <style>
        /* ── Design tokens ────────────────────────────────────── */
        :root {
            --primary:      #007E43;
            --primary-soft: #EAF7F1;
            --danger:       #BA1A1A;
            --danger-soft:  #FFDAD6;
            --orange:       #B66E00;
            --orange-soft:  #FBDDB0;
        }

        /* ── Base ─────────────────────────────────────────────── */
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #FAFAF5;
            color: #1a1a1a;
        }
        .font-manrope { font-family: 'Manrope', sans-serif; }

        /* ── Scrollbar ────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }

        /* ── Stat cards ───────────────────────────────────────── */
        .stat-card {
            background: #F4F4EF;
            border-radius: 16px;
            padding: 1.5rem;
        }
        .stat-card.danger-card {
            background-color: var(--danger-soft);
            border-color: #f5b8b8;
        }

        /* ── Status badges ────────────────────────────────────── */
        .badge-success { color: var(--primary); }
        .badge-orange  { color: var(--orange);  }
        .badge-danger  { color: var(--danger);  }
        .badge-gray    { color: #6b7280; }

        /* ── Bar chart ────────────────────────────────────────── */
        .bar-wrap  { display: flex; align-items: flex-end; gap: 6px; height: 120px; }
        .bar-col   { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 4px; height: 100%; }
        .bar       { width: 100%; border-radius: 5px 5px 0 0; transition: opacity .15s; }
        .bar:hover { opacity: .75; }
        .bar-label { font-size: 10px; color: #9ca3af; white-space: nowrap; }

        /* ── Periode toggle ───────────────────────────────────── */
        /* Background sesuai desain: abu hangat #F4F4EF */
        .periode-wrap {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #F4F4EF;
            border-radius: 999px;
            padding: 4px;
        }
        .periode-btn {
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all .15s;
            color: #40493D;
            background: transparent;
        }
        .periode-btn.active {
            background-color: var(--primary);
            color: #ffffff;
        }

        /* ── Section card ─────────────────────────────────────── */
        .section-card {
            background: #F4F4EF;
            border-radius: 16px;
        }

        /* ── Kasbon card ──────────────────────────────────────── */
        .kasbon-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1rem 1.1rem;
        }

        /* ── Kode transaksi ───────────────────────────────────── */
        .kode-ps { color: var(--primary); font-weight: 600; font-size: 10px;
                background-color: var(--primary-soft); padding: 2px 6px; border-radius: 4px; }
        .kode-sp { color: var(--orange);  font-weight: 600; font-size: 10px;
                   background-color: var(--orange-soft); padding: 2px 6px; border-radius: 4px; }

        /* ══════════════════════════════════════════════════════
           BUTTON DESIGN SYSTEM
           btn-outline: default putih → hover border+teks hijau → active bg hijau
           btn-primary: default hijau → hover lebih gelap → active TIDAK berubah
           ══════════════════════════════════════════════════════ */
        .btn-outline {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fff; color: #374151;
            padding: 7px 14px; border-radius: 999px;
            cursor: pointer; text-decoration: none;
            transition: border-color .15s, background .15s, color .15s;
        }
        .btn-outline:hover  { background-color: #E8E8E3; color: var(--primary); }
        .btn-outline:active { background: var(--primary); color: #fff; border-color: var(--primary); }

        .btn-outline-barang {
            letter-spacing: 1.2px;
            transition: border-color .15s, background .15s, color .15s;
        }
        .btn-outline-barang:hover  { background-color: #E8E8E3; color: var(--primary); }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background-color: var(--primary); color: #ffffff;
            padding: 10px 22px; border-radius: 999px;
            font-size: 0.7rem; font-weight: 500; border: none;
            letter-spacing: 0.3px;
            cursor: pointer; text-decoration: none; transition: background-color .15s;
        }
        .btn-primary:hover { background-color: #009750; }

        /* Tombol Lunasi */
        .btn-lunasi {
            border: 1.5px solid #d1d5db; background: #fff; color: #374151;
            padding: 5px 18px; border-radius: 999px;
            font-size: 12px; font-weight: 500; cursor: pointer;
            transition: border-color .15s, background .15s, color .15s;
            white-space: nowrap;
        }
        .btn-lunasi:hover  { border-color: var(--primary); color: var(--primary); }
        .btn-lunasi:active { background: var(--primary); color: #fff; border-color: var(--primary); }

        /* ── Hamburger ────────────────────────────────────────── */
        .hamburger-btn {
            display: flex; align-items: center; justify-content: center;
            width: 2rem; height: 2rem; flex-shrink: 0;
            border-radius: 999px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }
        .hamburger-btn:hover  { border-color: var(--primary); background: var(--primary-soft); }
        .hamburger-btn:active { background: var(--primary); border-color: var(--primary); }
        .hamburger-btn:active svg { color: #fff !important; }

        /* ── Tabel mobile: sembunyikan kolom Kode & Barang ─────── */
        /* Di mobile, kolom kode transaksi dan barang disembunyikan  */
        @media (max-width: 767px) {
            .col-kode,
            .col-barang { display: none; }

            /* Export buttons di mobile: susun vertikal (PDF kiri, Excel kanan bawah) */
            .export-wrap { flex-direction: column; align-items: flex-end; gap: 6px; }
        }

        /* ── Tx table base ────────────────────────────────────── */
        .tx-table th {
            text-transform: uppercase; letter-spacing: .05em; color: #9ca3af;
        }
        .tx-table tr:hover td { background-color: #f9fafb; }
    </style>
@endsection

@section('content')
        {{-- ── Flash message ────────────────────────────────────── --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium"
                 style="background-color:var(--primary-soft);color:var(--primary);">
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Page header ──────────────────────────────────────── --}}
        {{-- Desktop: row (judul kiri, avatar kanan) | Mobile: cukup judul --}}
        <div class="flex items-start justify-between mb-5">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase mb-1" style="color:var(--primary);">
                    SELAMAT DATANG KEMBALI
                </p>
                {{-- text-2xl di mobile, text-4xl di desktop --}}
                <h1 class="font-manrope text-2xl lg:text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $tenant->nama_tenant }}
                </h1>
                <p class="text-sm font-semibold mt-0.5 text-[#925800]">
                    {{ $tenant->blok }}
                </p>
                <p class="text-xs text-gray-900 mt-2 leading-relaxed">
                    Kelola transaksi hari ini dengan cepat dan pantau stok bahan makanan Anda secara real-time.
                </p>
            </div>

            {{-- Avatar desktop (kanan atas, tersembunyi di mobile karena sudah ada di top bar) --}}
            @if ($tenant->foto)
                <img src="{{ asset($tenant->foto) }}"
                     alt="{{ $tenant->nama_tenant }}"
                     class="w-9 h-9 rounded-full object-cover hidden lg:block">
            @else
                {{-- Fallback inisial jika tidak ada foto --}}
                <div class="hidden lg:flex w-9 h-9 rounded-full items-center justify-center text-white text-xs font-bold"
                     style="background-color:var(--primary);">
                    {{ strtoupper(substr($tenant->nama_tenant, 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- ── CTA Buka Kasir ───────────────────────────────────── --}}
        <a href="{{ route('tenant.kasir') }}" class="btn-primary mb-7 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Buka Kasir
        </a>

        {{-- ══════════════════════════════════════════════════════
             PERFORMA TOKO
             ══════════════════════════════════════════════════════ --}}
        <div class="mb-2">
            <h2 class="text-lg lg:text-xl text-[#003B1F]">Performa Toko</h2>
            <p class="text-xs text-gray-900 mt-0.5">Laporan performa toko Anda</p>
        </div>

        {{-- ── Periode toggle ─────────────────────────────────── --}}
        <form method="GET" action="{{ route('tenant.dashboard') }}" id="periodeForm" class="mb-5 flex justify-center">
            <div class="periode-wrap">
                @foreach (['tahunan' => 'Tahunan', 'bulanan' => 'Bulanan', 'harian' => 'Harian'] as $val => $label)
                    <button type="button"
                            onclick="setPeriode('{{ $val }}')"
                            class="periode-btn {{ $periode === $val ? 'active' : '' }}"
                            id="btn-{{ $val }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <input type="hidden" name="periode" id="periodeInput" value="{{ $periode }}">
        </form>

        {{-- ══════════════════════════════════════════════════════
             STAT CARDS — layout mobile sesuai desain:
             ┌──────────────┬──────────────┐
             │ Total        │ Total        │
             │ Penjualan    │ Kasbon       │
             ├──────┬───────┴──────────────┤  ← mobile
             │ Stok │ Barang Paling Laris  │
             └──────┴──────────────────────┘

             Desktop: 3 kolom sejajar, barang laris terpisah di bawah chart
             ══════════════════════════════════════════════════════ --}}

        {{-- Baris 1: Penjualan + Kasbon — selalu 2 kolom di semua ukuran --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
            {{-- Total Penjualan --}}
            <div class="stat-card">
                {{-- Wrapper atas --}}
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-2">

                    {{-- Kiri --}}
                    <div>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center mb-3 bg-[#B0E4CC]">
                            <svg class="w-4 h-4" style="color:var(--primary);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide font-manrope">
                            TOTAL PENJUALAN
                        </p>

                        <p class="font-manrope text-lg lg:text-xl text-gray-900 mt-0.5 leading-tight">
                            Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Badge Persentase --}}
                    @if (!is_null($penjualanPersentase))
                        <span
                            class="
                                flex justify-end
                                lg:block
                                ml-auto
                                w-fit
                                text-[0.6rem] font-semibold
                                c py-0.5 rounded-full

                                {{-- Mobile: bawah --}}
                                mt-1

                                {{-- Desktop: kanan atas --}}
                                lg:mt-0
                                lg:self-start
                                lg:whitespace-nowrap
                            "
                            style="{{ $penjualanPersentase >= 0
                                ? 'color:var(--primary);'
                                : 'color:var(--danger);' }}"
                        >
                            {{ $penjualanPersentase >= 0 ? '+' : '' }}{{ $penjualanPersentase }}%
                            dari {{ $periode === 'harian' ? 'Kemarin' : ($periode === 'bulanan' ? 'Bulan Lalu' : 'Tahun Lalu') }}
                        </span>
                    @endif

                </div>
            </div>

            {{-- Total Kasbon --}}
            <div class="stat-card">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                         style="background-color:var(--orange-soft);">
                        <svg class="w-4 h-4" style="color:var(--orange);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                    </div>
                {{-- RESPONSIVE: tampil desktop --}}
                <a href="#section-kasbon"
                class="hidden lg:flex text-[0.6rem] font-medium items-center gap-1 shrink-0"
                style="color:var(--orange);">
                    Selengkapnya
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide font-manrope">TOTAL KASBON</p>
                <p class="font-manrope text-lg lg:text-xl text-gray-900 mt-0.5 leading-tight">
                    Rp {{ number_format($totalKasbon, 0, ',', '.') }}
                </p>
                {{-- Link Selengkapnya --}}
                {{-- RESPONSIVE: tampil mobile --}}
                <a href="#section-kasbon"
                class="flex justify-end pt-2 lg:hidden items-center gap-0.5 mt-1.5 text-[0.6rem] font-medium"
                style="color:var(--orange);">
                    Selengkapnya
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Stok Habis — di desktop kolom ke-3, di mobile baris ke-2 kiri --}}
            {{-- Di mobile: baris 2 terdiri dari stok (kiri) dan barang laris (kanan),
                 karena itu stok hanya 1 kolom di grid yang akan kita buat terpisah --}}
            {{-- Kolom ini hanya tampil di desktop (lg+) --}}
            <div class="stat-card hidden lg:block {{ $stokHabis > 0 ? 'danger-card' : '' }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                         style="{{ $stokHabis > 0 ? 'background-color:var(--danger);' : 'background-color:var(--primary-soft);' }}">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    @if ($stokHabis > 0)
                        <a href="{{ route('tenant.stok') }}" class="text-[0.6rem] font-semibold flex items-center gap-1" style="color:var(--danger);">
                            Tambah Stok
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <p class="text-xs font-medium uppercase tracking-wide" style="color:var(--danger); font-manrope">PERINGATAN STOK</p>
                <p class="font-manrope text-xl mt-0.5" style="{{ $stokHabis > 0 ? 'color:var(--danger);' : 'color:#1a1a1a;' }}">
                    {{ $stokHabis > 0 ? $stokHabis . ' Barang Habis' : 'Stok Aman' }}
                </p>
            </div>
        </div>

        {{-- ── Baris 2 mobile: Stok (kiri) + Barang Laris (kanan) ─
             Sesuai desain: di mobile keduanya berdampingan dalam satu baris
             Di desktop: tersembunyi (barang laris muncul di section chart)
        ─────────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 gap-3 mb-6 lg:hidden">

            {{-- Stok Habis (mobile only row 2 kiri) --}}
            <div class="stat-card {{ $stokHabis > 0 ? 'danger-card' : '' }}">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                         style="{{ $stokHabis > 0 ? 'background-color:var(--danger);' : 'background-color:var(--primary-soft);' }}">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-medium uppercase tracking-wide" style="color:var(--danger);">PERINGATAN STOK</p>
                <p class="font-manrope text-base mt-0.5 leading-tight" style="{{ $stokHabis > 0 ? 'color:var(--danger);' : 'color:#1a1a1a;' }}">
                    {{ $stokHabis > 0 ? $stokHabis . ' Barang Habis' : 'Stok Aman' }}
                </p>
                @if ($stokHabis > 0)
                    <a href="{{ route('tenant.stok') }}" class="flex pt-2 justify-end items-center gap-0.5 mt-1.5 text-[0.6rem] font-semibold" style="color:var(--danger);">
                        Tambah Stok
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Barang Paling Laris (mobile only row 2 kanan) --}}
            <div class="stat-card">
                <p class="text-sm font-manrope text-gray-700 mb-2">Barang Paling Laris</p>
                <div class="space-y-2">
                    @forelse ($barangLaris->take(2) as $item)
                        <div class="flex items-center justify-between gap-1">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-800 truncate">{{ $item->barang->nama ?? '-' }}</p>
                                <p class="text-[0.6rem] text-gray-400">{{ $item->total_order }} order</p>
                            </div>
                            <span class="text-xs font-semibold flex-shrink-0" style="color:var(--primary);">
                                Rp {{ number_format($item->barang->harga_jual ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">Belum ada data</p>
                    @endforelse
                </div>
                <div class="mt-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('tenant.stok') }}"
                       class="btn-outline-barang tracking-wider block text-center text-[0.5rem] text-[#007E43] py-2 rounded-2xl border border-gray-200">
                        LIHAT SEMUA BARANG
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             CHART + BARANG LARIS (desktop layout)
             Mobile: chart full width, barang laris tersembunyi (sudah ada di atas)
             Desktop: 2/3 chart, 1/3 barang laris
             ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

            {{-- Tren Pendapatan Penjualan --}}
            <div class="lg:col-span-2 section-card p-4 lg:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-manrope text-gray-800 text-md">Tren Pendapatan Penjualan</h3>
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full inline-block" style="background:var(--primary);"></span>
                            Saat ini
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full inline-block bg-gray-300"></span>
                            Sebelumnya
                        </span>
                    </div>
                </div>

                {{-- Bar chart proporsional --}}
                <div class="bar-wrap">
                    @php
                        $maxVal = max(array_merge($trenData['current'], $trenData['previous'], [1]));
                    @endphp
                    @foreach ($trenData['labels'] as $i => $label)
                        @php
                            $curH      = $maxVal > 0 ? round(($trenData['current'][$i]  / $maxVal) * 100) : 0;
                            $prevH     = $maxVal > 0 ? round(($trenData['previous'][$i] / $maxVal) * 100) : 0;
                            $isBiggest = $trenData['current'][$i] === max($trenData['current']);
                        @endphp
                        <div class="bar-col">
                            <div style="flex:1; display:flex; align-items:flex-end; gap:2px; width:100%;">
                                <div class="bar" style="height:{{ max($prevH,4) }}%; background:#D1E8DC; flex:1;"></div>
                                <div class="bar" style="height:{{ max($curH,4)  }}%; background:{{ $isBiggest ? 'var(--primary)' : '#A8D5BE' }}; flex:1;"></div>
                            </div>
                            <span class="bar-label">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Barang Paling Laris — HANYA desktop ── --}}
            <div class="section-card p-5 lg:p-6 hidden lg:block">
                <h3 class="font-manrope text-gray-800 text-md mb-4">Barang Paling Laris</h3>
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
                           class="btn-outline-barang block text-center text-[0.6rem] text-[#007E43] py-2 rounded-2xl border border-gray-200">
                            LIHAT SEMUA BARANG
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             TABEL TRANSAKSI
             ══════════════════════════════════════════════════════ --}}
        <div class="section-card mb-6">

            {{-- Toolbar ── --}}
            <div form method="GET" action="{{ route('tenant.dashboard') }}"
                class="flex items-center gap-3 flex-wrap w-full lg:w-auto lg:ml-auto px-4 py-4 border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    {{-- Filter & Urutkan --}}
                    <div class="flex items-center gap-3 flex-wrap">

                        {{-- FILTER --}}
                        <form method="GET" class="relative">
                            <input type="hidden" name="filter" value="{{ request('filter') }}">

                            <select
                                name="filter"
                                onchange="this.form.submit()"
                                class="appearance-none rounded-4xl bg-white pl-10 pr-9 py-2 text-xs font-medium text-zinc-700 focus:outline-1 focus:outline-[#007E43]"
                            >
                                <option value="" disabled {{ request('filter') ? '' : 'selected' }}>
                                    Filter
                                </option>
                                <option value="selesai" {{ request('filter') == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                <option value="diproses" {{ request('filter') == 'diproses' ? 'selected' : '' }}>
                                    Diproses
                                </option>
                                <option value="dibatalkan" {{ request('filter') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>

                            {{-- Icon --}}
                            <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                                <svg class="w-4 h-4 text-zinc-500"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                            </div>

                            {{-- Arrow --}}
                            <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="w-3 h-3 text-zinc-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </form>

                        {{-- SORT --}}
                        <form method="GET" class="relative">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">

                            <select
                                name="sort"
                                onchange="this.form.submit()"
                                class="appearance-none rounded-4xl bg-white pl-10 pr-9 py-2 text-xs font-medium text-zinc-700 focus:outline-1 focus:outline-[#007E43]"
                            >
                                <option value="" disabled {{ request('sort') ? '' : 'selected' }}>
                                    Urutkan
                                </option>

                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>
                                    Terbaru
                                </option>

                                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>
                                    Terlama
                                </option>

                                <option value="terbesar" {{ request('sort') == 'terbesar' ? 'selected' : '' }}>
                                    Total Terbesar
                                </option>

                                <option value="terkecil" {{ request('sort') == 'terkecil' ? 'selected' : '' }}>
                                    Total Terkecil
                                </option>
                            </select>

                            {{-- Icon --}}
                            <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                                <svg class="w-4 h-4 text-zinc-500"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </div>

                            {{-- Arrow --}}
                            <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                                <svg class="w-3 h-3 text-zinc-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </form>

                    </div>
                </div>
                {{-- Export buttons
                     Mobile:  Export PDF di atas sendiri, Excel di bawahnya (flex-col align-end)
                     Desktop: sejajar (flex-row) --}}
                <div class="flex flex-col items-end gap-1.5 ml-auto sm:flex-row sm:items-center sm:gap-2 export-wrap">
                    <a class="btn-outline text-xs" href="{{ route('tenant.export.pdf', request()->query()) }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Export PDF
                    </a>

                    <a class="btn-outline text-xs" href="{{ route('tenant.export.excel', request()->query()) }}">
                        <svg class="w-3.5 h-3.5" style="color:#16a34a;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel
                    </a>
                </div>
            </div>

            {{-- Tabel — scroll horizontal di mobile --}}
            {{-- Di mobile: kolom Kode Transaksi & Barang disembunyikan via .col-kode .col-barang --}}
            <div class="overflow-x-auto">
                <table class="tx-table w-full max-w-5xl bg-white gap-1 rounded-2xl mx-2">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-[0.6rem] px-4 pl-8 py-3 text-left">TANGGAL</th>
                            <th class="text-[0.6rem] px-2 py-3 text-left col-kode">KODE TRANSAKSI</th>
                            <th class="text-[0.6rem] px-4 py-3 text-left col-barang">BARANG</th>
                            <th class="text-[0.6rem] px-4 py-3 text-center">TOTAL HARGA</th>
                            <th class="text-[0.6rem] px-4 py-3 text-center">PEMBAYARAN</th>
                            <th class="text-[0.6rem] px-4 pr-8 py-3 text-left">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($transaksi as $tx)
                            @php
                                $namaBarang      = collect($tx->transaksi_barang ?? [])
                                                        ->pluck('barang.nama')->filter()->implode(', ');
                                $namaBarangShort = strlen($namaBarang) > 30
                                                    ? substr($namaBarang, 0, 30) . '...' : $namaBarang;
                                $kodePrefix      = strtolower($tx->metode_bayar ?? '') === 'kasbon' ? 'SP' : 'PS';
                                $kode            = '#' . $kodePrefix . '-' . str_pad($tx->transaksi_id, 5, '0', STR_PAD_LEFT);
                                $isKasbon        = $kodePrefix === 'SP';
                                $isBatal         = strtolower($tx->status) === 'dibatalkan';
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
                                /* Label status singkat untuk mobile */
                                $statusShort = match(strtolower($tx->status)) {
                                    'selesai'    => 'Selesai',
                                    'diproses'   => 'Proses',
                                    'dibatalkan' => 'Batal',
                                    default      => ucfirst($tx->status)
                                };
                            @endphp
                            <tr>
                                <td class="px-4 lg:px-4 py-3">
                                    <p class="text-gray-800 font-medium whitespace-nowrap text-[0.7rem]  pl-2">
                                        {{ $tx->created_at->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="text-gray-400 text-[0.6rem] pl-2">{{ $tx->created_at->format('H:i') }} WIB</p>
                                </td>

                                {{-- Kolom Kode — tersembunyi di mobile --}}
                                <td class="px-2 py-3 col-kode">
                                    @if ($isKasbon)
                                        <span class="kode-sp">{{ $kode }}</span>
                                    @else
                                        <span class="kode-ps">{{ $kode }}</span>
                                    @endif
                                </td>

                                {{-- Kolom Barang — tersembunyi di mobile --}}
                                <td class="px-4 py-3 text-gray-600 col-barang" style="max-width:180px;">
                                    <p class="text-xs">{{ $namaBarangShort ?: '-' }}</p>
                                </td>

                                {{-- Total Harga --}}
                                <td class="px-4 py-3 text-center whitespace-nowrap text-xs font-semibold align-middle"
                                    style="{{ $isBatal ? 'color:#9ca3af; text-decoration:line-through;' : 'color:#111;' }}">
                                    {{ !$isBatal ? '+' : '' }} Rp {{ number_format($tx->total, 0, ',', '.') }}
                                </td>

                                {{-- Pembayaran --}}
                                <td class="px-4 py-3 text-gray-600 capitalize text-xs align-middle text-center">
                                    {{ ucfirst($tx->metode_bayar ?? '-') }}
                                </td>

                                {{-- Status --}}
                                <td class="items-center">
                                    <span class="px-3 inline-flex items-center text-center gap-1 text-[0.6rem] font-semibold rounded-full {{ $colorClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0"
                                              style="background:{{ $dotColor }};"></span>
                                        {{-- Label pendek di mobile, lengkap di desktop --}}
                                        <span class="lg:hidden">{{ $statusShort }}</span>
                                        <span class="hidden lg:inline">{{ ucfirst($tx->status) }}</span>
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

            {{-- Pagination ── --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 lg:px-6 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-400">
                    Menampilkan {{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} transaksi
                </p>
                <div class="flex items-center gap-1">
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

                    @if ($transaksi->hasMorePages())
                        <a href="{{ $transaksi->nextPageUrl() }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-100 transition-colors">›</a>
                    @else
                        <span class="px-2.5 py-1.5 rounded-lg text-xs text-gray-300 cursor-not-allowed">›</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             KASBON SECTION
             Mobile: 1 kolom penuh
             Desktop: 2 kolom
             ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5" id="section-kasbon">

            {{-- Kasbon Pelanggan ── --}}
            <div>
                <h3 class="font-manrope font-bold text-gray-900 mb-3">Kasbon Pelanggan</h3>
                <div class="space-y-2">
                    @forelse ($kasbonPelanggan as $kb)
                        {{-- Semua item tampil flat
                             - Belum lunas: nama + nominal merah + tombol Lunasi di kanan
                             - Lunas: nama + nominal abu + label "LUNAS" (tanpa tombol) --}}
                        <div class="kasbon-card flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-gray-800">{{ $kb->nama }}</p>
                                <p class="text-base font-bold mt-0.5"
                                   style="{{ $kb->is_lunas ? 'color:#9ca3af;' : 'color:var(--danger);' }}">
                                    Rp {{ number_format($kb->sisa ?: $kb->total, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] font-semibold uppercase tracking-wide mt-0.5"
                                   style="{{ $kb->is_lunas ? 'color:#9ca3af;' : 'color:var(--danger);' }}">
                                    {{ $kb->is_lunas ? 'LUNAS' : 'BELUM LUNAS' }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-[10px] text-gray-400 mb-1.5 whitespace-nowrap">
                                    {{ $kb->created_at->format('H:i') }} WIB – {{ $kb->created_at->translatedFormat('d M Y') }}
                                </p>
                                {{-- Tombol Lunasi hanya muncul jika belum lunas --}}
                                @if (!$kb->is_lunas)
                                    <form method="POST"
                                          action="{{ route('tenant.kasbon.lunasi', $kb->kasbon_id) }}"
                                          onsubmit="return confirm('Lunasi kasbon {{ $kb->nama }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-lunasi">Lunasi</button>
                                    </form>
                                @else
                                    {{-- Kasbon lunas --}}
                                    <span class="text-sm font-bold text-gray-400">
                                        Rp {{ number_format($kb->total, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="kasbon-card text-center py-8 text-sm text-gray-400">
                            Tidak ada kasbon pelanggan aktif.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Kasbon Supplier ── --}}
            <div>
                <h3 class="font-manrope font-bold text-gray-900 mb-3">Kasbon Supplier</h3>
                <div class="space-y-2">
                    @forelse ($kasbonSupplier as $kb)
                        <div class="kasbon-card flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-gray-800">{{ $kb->nama }}</p>
                                <p class="text-base font-bold mt-0.5"
                                   style="{{ $kb->is_lunas ? 'color:#9ca3af;' : 'color:var(--danger);' }}">
                                    Rp {{ number_format($kb->sisa ?: $kb->total, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] font-semibold uppercase tracking-wide mt-0.5"
                                   style="{{ $kb->is_lunas ? 'color:#9ca3af;' : 'color:var(--danger);' }}">
                                    {{ $kb->is_lunas ? 'LUNAS' : 'BELUM LUNAS' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-[10px] text-gray-400 mb-1.5 whitespace-nowrap">
                                    {{ $kb->created_at->format('H:i') }} WIB – {{ $kb->created_at->translatedFormat('d M Y') }}
                                </p>
                                @if (!$kb->is_lunas)
                                    <form method="POST"
                                          action="{{ route('tenant.kasbon.lunasi', $kb->kasbon_id) }}"
                                          onsubmit="return confirm('Lunasi kasbon {{ $kb->nama }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-lunasi">Lunasi</button>
                                    </form>
                                @else
                                    <span class="text-sm font-bold text-gray-400">
                                        Rp {{ number_format($kb->total, 0, ',', '.') }}
                                    </span>
                                @endif
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

        <div class="pb-6"></div>

    <script>
        /* Ganti periode dan submit form otomatis */
        function setPeriode(val) {
            document.getElementById('periodeInput').value = val;
            document.querySelectorAll('.periode-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + val).classList.add('active');
            document.getElementById('periodeForm').submit();
        }
    </script>
@endsection
