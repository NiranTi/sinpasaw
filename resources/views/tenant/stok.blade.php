{{-- resources/views/tenant/stok.blade.php --}}
@extends('layouts.tenant')

@section('title', 'Stok Barang – ' . $tenant->nama_tenant)

@section('styles')
/* ── Filter tabs (Semua / Tersedia / Hampir habis / Habis) ── */
.filter-tab {
    padding:5px 14px; border-radius:999px; font-size:12px; font-weight:500;
    cursor:pointer; border:none; color:#40493D; background:transparent; transition:all .15s;
    white-space:nowrap;
}
.filter-tab.active { background:var(--primary); color:#fff; }

/* ── Status badge di tabel (dot + text, no bg) ── */
.stok-badge { font-size:12px; font-weight:600; display:inline-flex; align-items:center; gap:5px; }

/* ── Tabel ── */
.stok-table th {
    font-size:10px; font-weight:700; text-transform:uppercase;
    letter-spacing:.06em; color:#9ca3af;
}
.stok-table tr:hover td { background:#f9fafb; }

/* ── Payment toggle di form restock ── */
.restock-pay-btn {
    flex:1; padding:12px 8px; border-radius:12px; border:1.5px solid #e5e7eb;
    background:#fff; cursor:pointer; transition:all .15s; text-align:center;
    font-size:11px; font-weight:600; color:#6b7280;
}
.restock-pay-btn.active { border-color:var(--primary); background:var(--primary); color:#fff; }
.restock-pay-btn:not(.active):hover { border-color:var(--primary); color:var(--primary); }

/* ── Mobile form area (muncul ketika tab diklik) ── */
.mobile-form-area {
    overflow:hidden; transition:max-height .3s ease, opacity .3s ease;
    max-height:0; opacity:0;
}
.mobile-form-area.open { max-height:1200px; opacity:1; }
@endsection

@section('content')

{{-- ── Page header ──────────────────────────────────────── --}}
<div class="mb-6">
    <p class="page-label">STOK BARANG</p>
    <h1 class="page-title">Manajemen Stok</h1>
    <p class="page-subtitle">Pantau stok barang toko Anda dengan mudah dan cepat.</p>
</div>

{{-- ══════════════════════════════════════════════════════════
     Layout utama
     Mobile  : full-width, tabel selalu tampil, form di-toggle
     Desktop : left 44% forms | right flex-1 tabel
═════════════════════════════════════════════════════════════ --}}
<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ════════════════════════════════════════════════
         KIRI: Forms (desktop selalu tampil)
    ════════════════════════════════════════════════ --}}
    <div class="hidden lg:flex flex-col w-full lg:w-[44%] flex-shrink-0 gap-5">

        {{-- Form Tambah Barang (desktop) --}}
        <div class="form-card">
            <h3 class="font-manrope font-bold text-gray-800 mb-4">Tambahkan Barang Baru</h3>
            <form method="POST" action="{{ route('tenant.stok.store') }}">
                @csrf
                @include('tenant.stok._form-tambah')
                <button type="submit" class="btn-primary mt-2 w-full p-2">
                    Tambahkan
                </button>
            </form>
        </div>

        {{-- Form Perbarui Stok / Restock (desktop) --}}
        <div class="form-card">
            <h3 class="font-manrope font-bold text-gray-800 mb-4">Perbarui Stok</h3>
            <form method="POST" action="{{ route('tenant.stok.restock') }}" id="dRestockForm">
                @csrf
                @include('tenant.stok._form-restock', ['prefix' => 'd'])
                <button type="submit" class="btn-primary mt-2 w-full p-2">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════
         KANAN / FULL: Tabel Stok
         (+ tab toggle + mobile forms di atas tabel)
    ════════════════════════════════════════════════ --}}
    <div class="flex-1 min-w-0 w-full">

        {{-- "Stok Saat Ini" heading — warna primary (hijau) sesuai desain mobile --}}
        <h2 class="font-manrope font-bold text-xl mb-0.5" style="color:var(--primary);">Stok Saat Ini</h2>
        <p class="text-sm text-gray-500 mb-4">Atur stok Anda disini.</p>

        {{-- ── Tab toggle: hanya muncul di mobile ── --}}
        {{-- Sama style dengan periode toggle beranda (tahunan/bulanan/harian) --}}
        <div class="flex lg:hidden mb-3">
            <div class="tab-wrap">
                <button id="tab-tambah" class="tab-btn" onclick="switchStokTab('tambah')">
                    Tambah Barang
                </button>
                <button id="tab-edit" class="tab-btn" onclick="switchStokTab('edit')">
                    Edit Barang
                </button>
            </div>
        </div>

        {{-- ── Mobile form area (tersembunyi default, expand saat tab diklik) ── --}}
        <div class="lg:hidden">
            {{-- Form Tambah Barang (mobile) --}}
            <div id="mFormTambahWrap" class="mobile-form-area">
                <div class="form-card mb-3">
                    <h3 class="font-manrope font-bold text-gray-800 mb-4">Tambahkan Barang Baru</h3>
                    <form method="POST" action="{{ route('tenant.stok.store') }}">
                        @csrf
                        @include('tenant.stok._form-tambah')
                        <button type="submit" class="btn-primary mt-2 py-2" style="width:100%;justify-content:center;font-size:14px;">
                            Tambahkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Form Perbarui Stok / Edit (mobile) --}}
            <div id="mFormEditWrap" class="mobile-form-area">
                <div class="form-card mb-3">
                    <h3 class="font-manrope font-bold text-gray-800 mb-4">Perbarui Stok</h3>
                    <form method="POST" action="{{ route('tenant.stok.restock') }}" id="mRestockForm">
                        @csrf
                        @include('tenant.stok._form-restock', ['prefix' => 'm'])
                        <button type="submit" class="btn-primary mt-2 py-2" style="width:100%;justify-content:center;font-size:14px;">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Filter bar ── --}}
        <div class="section-card px-2 pb-2">
            <div class="flex flex-wrap items-center justify-between gap-3 p-4 pb-0">

                {{-- Filter tabs (pill style, same as beranda periode toggle) --}}
                <div class="tab-wrap overflow-x-auto">
                    @foreach (['semua' => 'Semua', 'tersedia' => 'Tersedia', 'hampirhabis' => 'Hampir habis', 'habis' => 'Habis'] as $val => $label)
                        <a href="{{ route('tenant.stok', ['filter' => $val]) }}"
                           class="filter-tab {{ $filter === $val ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- ── Tabel stok ── --}}
            <div class="overflow-x-auto mt-3 bg-white rounded-2xl">
                <table class="stok-table w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-5 py-3 text-left">BARANG</th>
                            <th class="px-4 py-3 text-left">STOK</th>
                            <th class="px-4 py-3 text-left">HARGA PER</th>
                            <th class="px-4 py-3 text-left">PENJUALAN</th>
                            <th class="px-4 py-3 text-left">STATUS</th>
                            <th class="px-4 py-3 w-8"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($barang as $b)
                            @php
                                $status = match(true) {
                                    $b->stok <= 0  => ['label' => 'Habis',        'color' => 'var(--danger)',  'dot' => 'var(--danger)'],
                                    $b->stok <= 5  => ['label' => 'Hampir habis', 'color' => 'var(--orange)', 'dot' => 'var(--orange)'],
                                    default        => ['label' => 'Tersedia',     'color' => 'var(--primary)','dot' => 'var(--primary)'],
                                };
                            @endphp
                            <tr>
                                <td class="px-5 py-3 font-medium text-gray-800 text-sm">{{ $b->nama }}</td>
                                <td class="px-4 py-3 text-gray-600 text-sm whitespace-nowrap">{{ $b->stok }}</td>
                                <td class="px-4 py-3 text-gray-600 text-sm whitespace-nowrap">
                                    Rp {{ number_format($b->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-sm">
                                    {{ number_format($b->total_terjual ?? 0, 0, ',', '.') }}x
                                </td>
                                <td class="px-4 py-3">
                                    <span class="stok-badge" style="color:{{ $status['color'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 inline-block"
                                              style="background:{{ $status['dot'] }};"></span>
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <form method="POST"
                                          action="{{ route('tenant.stok.destroy', $b->barang_id) }}"
                                          onsubmit="return confirm('Hapus barang {{ addslashes($b->nama) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-400 transition-colors rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                                    Tidak ada barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            {{-- Pagination ── --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-t border-gray-100">
                <p class="text-xs text-gray-400">
                    Menampilkan {{ $barang->firstItem() ?? 0 }}–{{ $barang->lastItem() ?? 0 }}
                    dari {{ $barang->total() }} barang
                </p>
                <div class="flex items-center gap-1">
                    @if ($barang->onFirstPage())
                        <span class="px-2.5 py-1.5 rounded-lg text-xs text-gray-300">‹</span>
                    @else
                        <a href="{{ $barang->previousPageUrl() }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-100">‹</a>
                    @endif

                    @foreach ($barang->getUrlRange(1, $barang->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs font-medium transition-colors
                                  {{ $page == $barang->currentPage() ? 'text-white' : 'text-gray-600 hover:bg-gray-100' }}"
                           style="{{ $page == $barang->currentPage() ? 'background:var(--primary);' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($barang->hasMorePages())
                        <a href="{{ $barang->nextPageUrl() }}"
                           class="px-2.5 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-100">›</a>
                    @else
                        <span class="px-2.5 py-1.5 rounded-lg text-xs text-gray-300">›</span>
                    @endif
                </div>
            </div>
            </div>
        </div>{{-- /section-card --}}
    </div>{{-- /right column --}}
</div>

<div class="pb-6"></div>
@endsection

@section('scripts')
<script>
/* ── Tab toggle stok (MOBILE ONLY) ──────────────────────── */
let activeStokTab = null;

function switchStokTab(tab) {
    const tambahWrap = document.getElementById('mFormTambahWrap');
    const editWrap   = document.getElementById('mFormEditWrap');

    /* Jika tab yang sama diklik lagi → tutup (toggle off) */
    if (activeStokTab === tab) {
        tambahWrap.classList.remove('open');
        editWrap.classList.remove('open');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        activeStokTab = null;
        return;
    }

    activeStokTab = tab;

    /* Buka form yang sesuai, tutup yang lain */
    tambahWrap.classList.toggle('open', tab === 'tambah');
    editWrap.classList.toggle('open',   tab === 'edit');

    /* Update active state tombol tab */
    document.getElementById('tab-tambah').classList.toggle('active', tab === 'tambah');
    document.getElementById('tab-edit').classList.toggle('active',   tab === 'edit');

    /* Scroll ke form area agar langsung terlihat */
    tambahWrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ── Hitung ringkasan restock (desktop + mobile) ─────────── */
function calcRestock(prefix) {
    const qty   = parseFloat(document.getElementById(prefix + 'RsQty')?.value)   || 0;
    const harga = parseFloat(document.getElementById(prefix + 'RsHarga')?.value) || 0;
    const bayar = parseFloat(document.getElementById(prefix + 'RsBayar')?.value) || 0;
    const total     = qty * harga;
    const kembalian = Math.max(0, bayar - total);
    const kurang    = Math.max(0, total - bayar);
    const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');

    const s = id => document.getElementById(prefix + id);
    if(s('RsSubtotal')) s('RsSubtotal').textContent = rp(total);
    if(s('RsTotal'))    s('RsTotal').textContent    = rp(total);
    if(s('RsKembali'))  s('RsKembali').textContent  = rp(kembalian);
    if(s('RsKurang'))   {
        s('RsKurang').textContent  = rp(kurang);
        s('RsKurang').style.color  = kurang > 0 ? 'var(--danger)' : '#6b7280';
    }
}

/* ── Set metode bayar supplier (desktop + mobile) ────────── */
function setRestockMetode(prefix, m) {
    document.getElementById(prefix + 'RsMetode').value = m;
    document.getElementById(prefix + 'RsBtnQRIS').classList.toggle('active',  m === 'qris');
    document.getElementById(prefix + 'RsBtnTUNAI').classList.toggle('active', m === 'tunai');
}
</script>
@endsection
