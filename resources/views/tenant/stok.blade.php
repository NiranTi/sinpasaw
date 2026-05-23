{{-- resources/views/tenant/stok.blade.php --}}
@extends('layouts.tenant')

@section('title', 'Stok Barang – ' . $tenant->nama_tenant)

@section('styles')
/* ── Stok-specific styles ──────────────────────────────── */

/* Filter tabs — sama style dengan periode toggle beranda */
.filter-tabs {
    display:inline-flex; align-items:center; background:#F4F4EF;
    border-radius:999px; padding:4px; gap:3px;
}
.filter-tab {
    padding:5px 14px; border-radius:999px; font-size:12px; font-weight:500;
    cursor:pointer; border:none; color:#6b7280; background:transparent;
    transition:all .15s; white-space:nowrap;
}
.filter-tab.active { background:var(--primary); color:#fff; }

/* Stock status badge */
.stok-badge {
    font-size:12px; font-weight:600; padding:3px 10px; border-radius:999px;
    white-space:nowrap;
}

/* Table */
.stok-table th {
    font-size:10px; font-weight:700; text-transform:uppercase;
    letter-spacing:.06em; color:#9ca3af;
}
.stok-table td { font-size:13px; }
.stok-table tr:hover td { background:#f9fafb; }

/* Payment method toggle (restock) */
.restock-pay-btn {
    flex:1; padding:12px 8px; border-radius:12px; border:1.5px solid #e5e7eb;
    background:#fff; cursor:pointer; transition:all .15s; text-align:center;
    font-size:12px; font-weight:600; color:#6b7280;
}
.restock-pay-btn.active {
    border-color:var(--primary); background:var(--primary); color:#fff;
}
.restock-pay-btn:not(.active):hover { border-color:var(--primary); color:var(--primary); }
@endsection

@section('content')
{{-- ── Page header ──────────────────────────────────────── --}}
<div class="mb-6">
    <p class="page-label">STOK BARANG</p>
    <h1 class="page-title">Manajemen Stok</h1>
    <p class="page-subtitle">Pantau stok barang toko Anda dengan mudah dan cepat.</p>
</div>

{{-- ════════════════════════════════════════════════════════
     Layout: Form kiri + Tabel kanan
     Mobile: 1 kolom stacked | Desktop: ~45% + ~55%
════════════════════════════════════════════════════════ --}}
<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ═══════════════════════════════════
         KIRI: Form Tambah + Form Restock
    ═══════════════════════════════════ --}}
    <div class="w-full lg:w-[44%] space-y-5 flex-shrink-0">

        {{-- ── Form Tambahkan Barang Baru ────────────────── --}}
        <div class="form-card">
            <h3 class="font-manrope font-bold text-gray-800 mb-4">Tambahkan Barang Baru</h3>
            <form method="POST" action="{{ route('tenant.stok.store') }}">
                @csrf
                {{-- Nama Barang --}}
                <div class="form-group">
                    <label class="form-label">NAMA BARANG</label>
                    <input type="text" name="nama"
                           class="form-input @error('nama') ring-2 ring-red-300 @enderror"
                           value="{{ old('nama') }}" placeholder="Nama barang..." required>
                    @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Harga Jual --}}
                <div class="form-group">
                    <label class="form-label">HARGA JUAL</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="harga_jual"
                               class="form-input has-prefix"
                               value="{{ old('harga_jual') }}" placeholder="0" min="0" required>
                    </div>
                </div>

                {{-- Stok + Unit --}}
                <div class="grid grid-cols-2 gap-3 form-group">
                    <div>
                        <label class="form-label">STOK</label>
                        <input type="number" name="stok"
                               class="form-input"
                               value="{{ old('stok', 0) }}" min="0" required>
                    </div>
                    <div>
                        <label class="form-label">UNIT</label>
                        <input type="text" name="unit"
                               class="form-input"
                               value="{{ old('unit', 'KG') }}" placeholder="KG">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-primary w-full mt-1" style="width:100%;">
                    Tambahkan
                </button>
            </form>
        </div>

        {{-- ── Form Perbarui Stok (Restock dari Supplier) ── --}}
        <div class="form-card">
            <h3 class="font-manrope font-bold text-gray-800 mb-4">Perbarui Stok</h3>
            <form method="POST" action="{{ route('tenant.stok.restock') }}" id="restockForm">
                @csrf

                {{-- Nama Barang (select dari barang yang ada) --}}
                <div class="form-group">
                    <label class="form-label">NAMA BARANG</label>
                    <select name="barang_id" class="form-input" required onchange="updateRestockCalc()">
                        <option value="">Pilih barang...</option>
                        @foreach ($semuaBarang as $b)
                            <option value="{{ $b->barang_id }}" data-stok="{{ $b->stok }}">
                                {{ $b->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Supplier --}}
                <div class="form-group">
                    <label class="form-label">SUPPLIER</label>
                    <select name="supplier_id" class="form-input">
                        <option value="">Pilih supplier...</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->supplier_id }}">{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah + Unit --}}
                <div class="grid grid-cols-2 gap-3 form-group">
                    <div>
                        <label class="form-label">JUMLAH</label>
                        <input type="number" name="qty" id="restockQty"
                               class="form-input" placeholder="0" min="1"
                               oninput="updateRestockCalc()" required>
                    </div>
                    <div>
                        <label class="form-label">UNIT</label>
                        <input type="text" name="unit" class="form-input" placeholder="KG" value="KG">
                    </div>
                </div>

                {{-- Harga Per Unit --}}
                <div class="form-group">
                    <label class="form-label">HARGA PER UNIT</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="harga_beli" id="restockHarga"
                               class="form-input has-prefix"
                               placeholder="0" min="0"
                               oninput="updateRestockCalc()" required>
                    </div>
                </div>

                {{-- Metode bayar ke supplier --}}
                <div class="flex gap-2 mb-4">
                    <button type="button" id="restockBtnQRIS"
                            class="restock-pay-btn" onclick="setRestockMetode('qris')">
                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"/>
                        </svg>
                        QRIS
                    </button>
                    <button type="button" id="restockBtnTUNAI"
                            class="restock-pay-btn active" onclick="setRestockMetode('tunai')">
                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75"/>
                        </svg>
                        TUNAI
                    </button>
                </div>
                <input type="hidden" name="metode_bayar" id="restockMetode" value="tunai">

                {{-- Ringkasan pembayaran ke supplier --}}
                <div class="space-y-1.5 py-3 border-t border-b border-gray-100 text-sm mb-4">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span id="rsSubtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Pajak</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 text-base">
                        <span>Total</span>
                        <span id="rsTotal" style="color:var(--primary);">Rp 0</span>
                    </div>
                    {{-- Bayar (input) --}}
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Bayar</span>
                        <input type="number" name="bayar" id="rsBayarInput"
                               class="text-right bg-transparent border-none outline-none text-sm font-semibold text-gray-800 w-28"
                               placeholder="0" min="0" oninput="updateRestockCalc()" required>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Kembali</span>
                        <span id="rsKembali">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-semibold">
                        <span>Kurang</span>
                        <span id="rsKurang" style="color:var(--danger);">Rp 0</span>
                    </div>
                </div>

                {{-- Simpan --}}
                <button type="submit" class="btn-primary w-full" style="width:100%;">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         KANAN: Tabel Stok Saat Ini
    ═══════════════════════════════════ --}}
    <div class="flex-1 min-w-0 section-card">
        <div class="p-5 pb-0">
            <h3 class="font-manrope font-bold text-gray-900">Stok Saat Ini</h3>
            <p class="text-xs text-gray-400 mt-0.5 mb-4">Atur stok Anda disini.</p>

            {{-- Filter bar: Sort + Filter tabs --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                {{-- Urutkan --}}
                <button class="btn-outline-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    Urutkan
                </button>

                {{-- Filter tabs --}}
                <div class="filter-tabs">
                    @foreach (['semua' => 'Semua', 'tersedia' => 'Tersedia', 'hampirhabis' => 'Hampir habis', 'habis' => 'Habis'] as $val => $label)
                        <a href="{{ route('tenant.stok', ['filter' => $val]) }}"
                           class="filter-tab {{ $filter === $val ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="stok-table w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left">BARANG</th>
                        <th class="px-4 py-3 text-left">STOK</th>
                        <th class="px-4 py-3 text-left">HARGA PER</th>
                        <th class="px-4 py-3 text-left">PENJUALAN</th>
                        <th class="px-4 py-3 text-left">STATUS</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($barang as $b)
                        @php
                            /* Status berdasarkan level stok */
                            $status = match(true) {
                                $b->stok <= 0  => ['label' => 'Habis',        'class' => 'badge-danger',   'dot' => 'var(--danger)'],
                                $b->stok <= 5  => ['label' => 'Hampir habis', 'class' => 'badge-orange',  'dot' => 'var(--orange)'],
                                default        => ['label' => 'Tersedia',     'class' => 'badge-success', 'dot' => 'var(--primary)'],
                            };
                        @endphp
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $b->nama }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $b->stok }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                Rp {{ number_format($b->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ number_format($b->total_terjual ?? 0, 0, ',', '.') }}x
                            </td>
                            <td class="px-4 py-3">
                                <span class="stok-badge {{ $status['class'] }}"
                                      style="display:inline-flex;align-items:center;gap:5px;">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0"
                                          style="background:{{ $status['dot'] }};"></span>
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                {{-- Hapus barang --}}
                                <form method="POST"
                                      action="{{ route('tenant.stok.destroy', $b->barang_id) }}"
                                      onsubmit="return confirm('Hapus barang {{ $b->nama }}?')">
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
        </div>

        {{-- Pagination --}}
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
</div>
@endsection

@section('scripts')
<script>
/* ── Format rupiah ─────────────────────────────────────── */
function rp(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

/* ── Hitung ringkasan restock ──────────────────────────── */
function updateRestockCalc() {
    const qty   = parseFloat(document.getElementById('restockQty').value) || 0;
    const harga = parseFloat(document.getElementById('restockHarga').value) || 0;
    const bayar = parseFloat(document.getElementById('rsBayarInput').value) || 0;

    const subtotal  = qty * harga;
    const kembalian = Math.max(0, bayar - subtotal);
    const kurang    = Math.max(0, subtotal - bayar);

    document.getElementById('rsSubtotal').textContent = rp(subtotal);
    document.getElementById('rsTotal').textContent    = rp(subtotal);
    document.getElementById('rsKembali').textContent  = rp(kembalian);
    document.getElementById('rsKurang').textContent   = rp(kurang);
    document.getElementById('rsKurang').style.color   = kurang > 0 ? 'var(--danger)' : '#6b7280';
}

/* ── Set metode pembayaran restock ─────────────────────── */
function setRestockMetode(m) {
    document.getElementById('restockMetode').value = m;
    document.getElementById('restockBtnQRIS').classList.toggle('active',  m === 'qris');
    document.getElementById('restockBtnTUNAI').classList.toggle('active', m === 'tunai');
}
</script>
@endsection
