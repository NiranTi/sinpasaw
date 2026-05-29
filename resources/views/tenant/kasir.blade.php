{{-- resources/views/tenant/kasir.blade.php --}}
@extends('layouts.tenant')

@section('title', 'Kasir – ' . $tenant->nama_tenant)

@section('styles')
/* ── Product card
   Mobile : horizontal flex (image kiri, teks tengah, button kanan)
   Desktop: vertical card dalam grid 2-kolom
────────────────────────────────────────────────────────── */
.product-card {
    background:#fff; border-radius:16px; overflow:hidden;
    display:flex; flex-direction:row; align-items:center;
    gap:12px; padding:12px; transition:box-shadow .15s;
}
.product-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }

/* Image placeholder — kotak dengan initial nama */
.product-img-wrap {
    width:80px; height:80px; flex-shrink:0; border-radius:12px;
    background:linear-gradient(135deg,#A8D5BE,#EAF7F1);
    display:flex; align-items:center; justify-content:center;
    font-size:1.5rem; color:#007E43; font-weight:700; overflow:hidden;
}

/* Body teks: flex-1 agar melebar mengisi sisa ruang */
.product-card-body { flex:1; min-width:0; }

/* Desktop: vertical card layout */
@media (min-width:1024px) {
    .product-card { flex-direction:column; padding:0; gap:0; align-items:stretch; }
    .product-img-wrap { width:100%; height:auto; aspect-ratio:1; border-radius:16px 16px 0 0; font-size:2rem; }
    .product-card-body { padding:1rem; }
}

/* ── + button & qty control ── */
.btn-add-circle {
    width:36px; height:36px; border-radius:50%; background:var(--primary);
    color:#fff; border:none; display:flex; align-items:center; justify-content:center;
    cursor:pointer; flex-shrink:0; transition:background .15s;
}
.btn-add-circle:hover { background:#006435; }
.qty-control {
    display:none; align-items:center; gap:6px;
    background:var(--primary-soft); border-radius:999px; padding:4px 10px;
}
.qty-btn {
    width:24px; height:24px; border-radius:50%; border:none;
    background:var(--primary); color:#fff; font-size:16px; font-weight:600;
    cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1;
}
.qty-btn:hover { background:#006435; }
.qty-value { font-size:14px; font-weight:700; color:var(--primary); min-width:20px; text-align:center; }

/* ── Desktop cart panel ── */
.cart-panel {
    background:#fff; border-radius:16px; padding:1.25rem;
    position:sticky; top:2rem; max-height:calc(100vh - 6rem); overflow-y:auto;
}
.cart-item {
    display:flex; align-items:center; gap:8px;
    padding:8px 0; border-bottom:1px solid #f3f4f6;
}
.cart-item:last-child { border-bottom:none; }

/* ── Payment method toggle ── */
.pay-btn {
    flex:1; padding:12px 8px; border-radius:12px; border:1.5px solid #e5e7eb;
    background:#fff; cursor:pointer; transition:all .15s;
    text-align:center; font-size:11px; font-weight:600; color:#6b7280;
}
.pay-btn.active { border-color:var(--primary); background:var(--primary); color:#fff; }
.pay-btn:not(.active):hover { border-color:var(--primary); color:var(--primary); }

/* ── Search ── */
.search-wrap {
    display:flex; align-items:center; gap:3px;
    background:#fff; border:1.5px solid #e5e7eb;
    border-radius:999px; padding:4px 4px 4px 16px; width:100%;
}
.search-input { flex:1; border:none; outline:none; background:transparent; font-size:14px; color:#374151; }
.search-input::placeholder { color:#b0b8bf; }
@endsection

@section('content')
@if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        {{ $errors->first() }}
    </div>
@endif

{{-- Page header --}}
<div class="mb-6">
    <p class="page-label">KASIR</p>
    <h1 class="page-title">Kasir Digital</h1>
    <p class="page-subtitle">Pusat pencatatan penjualan secara real-time memudahkan pengelolaan transaksi harian tenant.</p>
</div>

{{-- Search bar --}}
<div class="mb-5">
    <div class="search-wrap max-w-lg">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input id="searchInput" type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari produk ..." class="search-input" autocomplete="off">
        <button type="submit" class="btn-primary" style="font-size:13px;padding:9px 20px;">Cari</button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     Layout: produk (kiri/full) + cart panel (kanan, desktop)
═════════════════════════════════════════════════════════ --}}
<div class="flex flex-col lg:flex-row gap-6 items-start pb-24 lg:pb-0">

    {{-- ── Product list
         Mobile : space-y-3 (1 kolom horizontal)
         Desktop: grid 2-kolom
    ── --}}
    <div class="w-full lg:flex-1 space-y-3 lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-4">
        @forelse ($barang as $b)
            <div class="product-card"
                 data-id="{{ $b->barang_id }}"
                 data-search="{{ strtolower($b->nama) }}"
                 data-nama="{{ addslashes($b->nama) }}"
                 data-harga="{{ $b->harga_jual }}">

                {{-- Image / placeholder ── --}}
                <div class="product-img-wrap">
                    {{ strtoupper(substr($b->nama, 0, 1)) }}
                </div>

                {{-- Teks & tombol ── --}}
                <div class="product-card-body">
                    <p class="font-manrope font-bold text-sm lg:text-base text-gray-900 leading-tight">
                        {{ $b->nama }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">Stok: {{ $b->stok }}</p>

                    <div class="flex items-center justify-between mt-2">
                        <p class="font-bold text-sm" style="color:var(--primary);">
                            Rp {{ number_format($b->harga_jual, 0, ',', '.') }}
                        </p>

                        {{-- Tombol + (default) --}}
                        <button class="btn-add-circle btn-add"
                                onclick="addToCart({{ $b->barang_id }}, '{{ addslashes($b->nama) }}', {{ $b->harga_jual }}, {{ $b->stok }})"
                                aria-label="Tambah">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                        {{-- Qty control (muncul ketika item ada di cart) --}}
                        <div class="qty-control">
                            <button class="qty-btn" onclick="updateQty({{ $b->barang_id }}, -1)">−</button>
                            <span class="qty-value" id="qty-{{ $b->barang_id }}">1</span>
                            <button class="qty-btn" onclick="updateQty({{ $b->barang_id }}, 1)">+</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 text-gray-400 text-sm">
                Tidak ada produk ditemukan.
            </div>
        @endforelse
    </div>

    {{-- ── Desktop cart panel (hidden di mobile) ── --}}
    <div class="hidden lg:block w-72 xl:w-80 flex-shrink-0">
        <div class="cart-panel">
            <p class="text-xs text-gray-400 text-right mb-3 font-mono">{{ $kodeTransaksi }}</p>

            {{-- Item list --}}
            <div id="dCartItems">
                <p class="text-sm text-gray-400 text-center py-4">Keranjang kosong</p>
            </div>

            {{-- Payment method --}}
            <div class="flex gap-2 my-3">
                <button id="dBtnQRIS"  class="pay-btn"        onclick="setMetode('qris')">
                    <svg class="w-5 h-5 mx-auto mb-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                    </svg>
                    QRIS
                </button>
                <button id="dBtnTUNAI" class="pay-btn active"  onclick="setMetode('tunai')">
                    <svg class="w-5 h-5 mx-auto mb-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375"/>
                    </svg>
                    TUNAI
                </button>
            </div>

            {{-- Nominal diterima --}}
            <div class="form-group">
                <label class="form-label">NOMINAL DITERIMA</label>
                <div class="input-prefix-wrap">
                    <span class="input-prefix">Rp</span>
                    <input type="number" id="dNominal" class="form-input has-prefix"
                           placeholder="0" oninput="syncNominal(this,'m')" min="0">
                </div>
            </div>

            {{-- Summary --}}
            <div class="space-y-1.5 py-3 border-t border-gray-100 text-sm">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span id="dSubtotal">Rp 0</span></div>
                <div class="flex justify-between font-bold text-base pt-1">
                    <span>Total</span><span id="dTotal" style="color:var(--primary);">Rp 0</span>
                </div>
                <div class="flex justify-between text-gray-500 pt-1 border-t border-gray-100">
                    <span>Bayar</span><span id="dBayar">Rp 0</span>
                </div>
                <div class="flex justify-between text-gray-500"><span>Kembali</span><span id="dKembali">Rp 0</span></div>
                <div class="flex justify-between font-semibold">
                    <span>Kurang</span><span id="dKurang" style="color:var(--danger);">Rp 0</span>
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 mt-2 cursor-pointer select-none">
                <input type="checkbox" id="printStruk" class="w-4 h-4 accent-green-700"> Print Struk
            </label>

            <button onclick="handlePayNow()"
                    class="btn-primary mt-4"
                    style="width:100%;font-size:14px;padding:13px 20px;display:flex;justify-content:center;">
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>

{{-- Hidden form submit --}}
<form id="payForm" method="POST" action="{{ route('tenant.kasir.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="items"           id="fItems">
    <input type="hidden" name="metode_bayar"     id="fMetode"  value="tunai">
    <input type="hidden" name="nominal"          id="fNominal">
    <input type="hidden" name="nama_pelanggan"   id="fNama">
    <input type="hidden" name="kontak_pelanggan" id="fKontak">
</form>

{{-- Mobile: floating cart button (muncul saat cart tidak kosong) --}}
<div id="floatingCartBtn" class="fixed bottom-4 left-4 right-4 z-40 lg:hidden hidden">
    <button onclick="showMobileCart()"
            class="btn-primary shadow-xl"
            style="width:100%;font-size:13px;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;">
        <span id="mCartCount" class="font-semibold">0 item</span>
        <span id="mCartTotal" class="font-semibold">Rp 0</span>
    </button>
</div>
@endsection

@section('modals')
{{-- ══════════════════════════════════════════════════════
     MOBILE CART — slide-up dari bawah
     ══════════════════════════════════════════════════════ --}}
<div id="mobileCartOverlay" class="fixed inset-0 z-50 lg:hidden hidden">
    <div class="absolute inset-0 bg-black/40" onclick="hideMobileCart()"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl p-5 max-h-[88vh] overflow-y-auto z-10">

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-manrope font-bold text-gray-900">Keranjang
                <span class="text-xs text-gray-400 font-normal ml-1">{{ $kodeTransaksi }}</span>
            </h3>
            <button onclick="hideMobileCart()" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="mCartItems">
            <p class="text-sm text-gray-400 text-center py-4">Keranjang kosong</p>
        </div>

        {{-- Payment method --}}
        <div class="flex gap-2 my-3">
            <button id="mBtnQRIS"  class="pay-btn"        onclick="setMetode('qris')">QRIS</button>
            <button id="mBtnTUNAI" class="pay-btn active"  onclick="setMetode('tunai')">TUNAI</button>
        </div>

        {{-- Nominal --}}
        <div class="form-group">
            <label class="form-label">NOMINAL DITERIMA</label>
            <div class="input-prefix-wrap">
                <span class="input-prefix">Rp</span>
                <input type="number" id="mNominal" class="form-input has-prefix"
                       placeholder="0" oninput="syncNominal(this,'d')" min="0">
            </div>
        </div>

        {{-- Summary --}}
        <div class="space-y-1.5 py-3 border-t border-gray-100 text-sm">
            <div class="flex justify-between text-gray-500"><span>Subtotal</span><span id="mSubtotal">Rp 0</span></div>
            <div class="flex justify-between font-bold text-base pt-1">
                <span>Total</span><span id="mTotal" style="color:var(--primary);">Rp 0</span>
            </div>
            <div class="flex justify-between text-gray-500 pt-1 border-t border-gray-100">
                <span>Bayar</span><span id="mBayar">Rp 0</span>
            </div>
            <div class="flex justify-between text-gray-500"><span>Kembali</span><span id="mKembali">Rp 0</span></div>
            <div class="flex justify-between font-semibold">
                <span>Kurang</span><span id="mKurang" style="color:var(--danger);">Rp 0</span>
            </div>
        </div>

        <button onclick="handlePayNow()"
                class="btn-primary mt-3"
                style="width:100%;font-size:14px;padding:13px 20px;display:flex;justify-content:center;">
            Bayar Sekarang
        </button>
    </div>
</div>

{{-- Kasbon popup modal --}}
<div id="kasbonOverlay"
     class="fixed inset-0 z-50 flex items-center justify-center hidden"
     style="background:rgba(0,0,0,0.25);">
    <div class="relative bg-white rounded-2xl shadow-xl w-80 p-6">
        <button onclick="closeKasbonModal()"
                class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h3 class="font-manrope font-bold text-gray-900 text-base text-center mb-2">
            Nominal diterima kurang dari total belanja
        </h3>
        <p class="text-sm text-gray-500 text-center mb-5 leading-relaxed">
            Dengan melanjutkan transaksi, sistem akan mencatat sebagai kasbon.
            Silakan isi nama dan kontak pelanggan, lalu pilih <strong>Lanjutkan</strong>
            untuk menyimpan kasbon atau <strong>Kembali</strong> untuk membatalkan.
        </p>
        <div class="form-group">
            <label class="form-label">NAMA PELANGGAN</label>
            <input type="text" id="kasbonNama" class="form-input" placeholder="Nama pelanggan...">
        </div>
        <div class="form-group" style="margin-bottom:1.5rem;">
            <label class="form-label">NO. HP PELANGGAN</label>
            <input type="tel" id="kasbonKontak" class="form-input" placeholder="08xxxxxxxxxx">
        </div>
        <div class="flex gap-3">
            <button onclick="closeKasbonModal()"
                    class="btn-outline flex-1" style="justify-content:center;">Kembali</button>
            <button onclick="submitKasbon()"
                    class="btn-primary flex-1" style="justify-content:center;font-size:14px;">Lanjutkan</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('searchInput').addEventListener('input', function () {

    const keyword = this.value.toLowerCase().trim();

    document.querySelectorAll('.product-card').forEach(card => {

        const nama = card.dataset.search;

        if (nama.includes(keyword)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }

    });

});
/* ── Cart state ──────────────────────────────────────────── */
const cart = {};
let   metode = 'tunai';
const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');

/* Tambah item ke cart */
function addToCart(id, nama, harga, maxStok) {

    if (cart[id]) {

        if (cart[id].qty >= maxStok) {
            showAlert(`Stok ${nama.toLowerCase} tidak mencukupi`, 'error');
            return;
        }

        cart[id].qty++;

    } else {

        cart[id] = {
            id,
            nama,
            harga,
            qty: 1,
            maxStok
        };
    }

    renderAll();
}
/* Ubah qty */
function updateQty(id, delta) {
    if (!cart[id]) return;

    const newQty = cart[id].qty + delta;

    if (newQty > cart[id].maxStok) {
        showAlert(`Stok ${cart[id].nama.toLowerCase()} tidak mencukupi`, 'error');
        return;
    }

    cart[id].qty = newQty;

    if (cart[id].qty <= 0) {
        delete cart[id];
    }

    renderAll();
}
/* Hapus dari cart */
function removeFromCart(id) { delete cart[id]; renderAll(); }

/* Render semua: cart panels + product cards + summary + floating button */
function renderAll() {
    const items    = Object.values(cart);
    const subtotal = items.reduce((s, i) => s + i.qty * i.harga, 0);

    renderCartPanel('dCartItems', items);
    renderCartPanel('mCartItems', items);
    renderProductCards();
    updateSummary(subtotal);
    updateFloatingBtn(items, subtotal);
}

/* Render item list ke container tertentu */
function renderCartPanel(containerId, items) {
    const el = document.getElementById(containerId);
    if (!el) return;
    if (!items.length) {
        el.innerHTML = '<p class="text-sm text-gray-400 text-center py-4">Keranjang kosong</p>';
        return;
    }
    el.innerHTML = items.map(i => `
        <div class="cart-item">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">${i.nama}</p>
                <p class="text-xs text-gray-400">Jumlah: ${i.qty}</p>
            </div>
            <span class="text-sm font-bold flex-shrink-0" style="color:var(--primary);">${rp(i.qty*i.harga)}</span>
            <button onclick="removeFromCart(${i.id})"
                    class="ml-2 w-6 h-6 flex items-center justify-center text-gray-300 hover:text-red-400 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `).join('');
}

/* Update tampilan tombol +/qty di product cards */
function renderProductCards() {
    document.querySelectorAll('[data-id]').forEach(card => {
        const id  = parseInt(card.dataset.id);
        const inC = cart[id];
        const ba  = card.querySelector('.btn-add');
        const qc  = card.querySelector('.qty-control');
        const qv  = card.querySelector('.qty-value');
        if (inC) { if(ba) ba.style.display='none'; if(qc) qc.style.display='flex'; if(qv) qv.textContent=inC.qty; }
        else     { if(ba) ba.style.display='flex'; if(qc) qc.style.display='none'; }
    });
}

/* Update summary di KEDUA panel (desktop dan mobile) */
function updateSummary(subtotal) {
    const nominal   = getNominal();
    const kembalian = Math.max(0, nominal - subtotal);
    const kurang    = Math.max(0, subtotal - nominal);

    const pairs = [
        ['dSubtotal','mSubtotal', rp(subtotal)],
        ['dTotal',   'mTotal',    rp(subtotal)],
        ['dBayar',   'mBayar',    rp(nominal)],
        ['dKembali', 'mKembali',  rp(kembalian)],
        ['dKurang',  'mKurang',   rp(kurang)],
    ];
    pairs.forEach(([did, mid, val]) => {
        [did, mid].forEach(id => { const el = document.getElementById(id); if(el) el.textContent = val; });
    });
    ['dKurang','mKurang'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.color = kurang > 0 ? 'var(--danger)' : '#6b7280';
    });
}

/* Recalc saat input nominal berubah */
function updateCalc() {
    const subtotal = Object.values(cart).reduce((s,i) => s + i.qty * i.harga, 0);
    updateSummary(subtotal);
}

/* Sync nilai nominal antara desktop input dan mobile input */
function syncNominal(src, otherSuffix) {
    const other = document.getElementById(otherSuffix === 'd' ? 'dNominal' : 'mNominal');
    if (other) other.value = src.value;
    updateCalc();
}

/* Ambil nilai nominal dari input yang aktif (tergantung viewport) */
function getNominal() {
    const isMobile = window.innerWidth < 1024;
    const el = document.getElementById(isMobile ? 'mNominal' : 'dNominal');
    return parseFloat(el?.value) || 0;
}

/* Update floating cart button (mobile) */
function updateFloatingBtn(items, subtotal) {
    const btn = document.getElementById('floatingCartBtn');
    if (!btn) return;
    if (items.length > 0) {
        btn.classList.remove('hidden');
        const c = document.getElementById('mCartCount');
        const t = document.getElementById('mCartTotal');
        if(c) c.textContent = items.length + ' item';
        if(t) t.textContent = rp(subtotal);
    } else {
        btn.classList.add('hidden');
        hideMobileCart();
    }
}

/* Set metode pembayaran (QRIS / TUNAI) */
function setMetode(m) {
    metode = m;
    ['d','m'].forEach(s => {
        document.getElementById(`${s}BtnQRIS`)?.classList.toggle('active',  m === 'qris');
        document.getElementById(`${s}BtnTUNAI`)?.classList.toggle('active', m === 'tunai');
    });
}

/* Tampilkan / sembunyikan mobile cart sheet */
function showMobileCart() { document.getElementById('mobileCartOverlay').classList.remove('hidden'); }
function hideMobileCart() { document.getElementById('mobileCartOverlay').classList.add('hidden'); }

/* Klik "Bayar Sekarang" */
function handlePayNow() {
    const items = Object.values(cart);
    if (!items.length) { showAlert('Keranjang masih kosong!', 'error'); return; }
    const subtotal = items.reduce((s,i) => s + i.qty * i.harga, 0);
    const nominal  = getNominal();
    if (subtotal > nominal) {
        /* Nominal kurang → tampilkan kasbon popup */
        document.getElementById('kasbonOverlay').classList.remove('hidden');
    } else {
        submitForm();
    }
}

/* Submit form ke server */
function submitForm(namaPelanggan = '', kontakPelanggan = '') {
    const items = Object.values(cart).map(i => ({ id:i.id, nama:i.nama, harga:i.harga, qty:i.qty }));
    document.getElementById('fItems').value   = JSON.stringify(items);
    document.getElementById('fMetode').value  = metode;
    document.getElementById('fNominal').value = getNominal();
    document.getElementById('fNama').value    = namaPelanggan;
    document.getElementById('fKontak').value  = kontakPelanggan;
    document.getElementById('payForm').submit();
}

/* Lanjutkan kasbon setelah isi nama + kontak */
function submitKasbon() {
    const nama   = document.getElementById('kasbonNama').value.trim();
    const kontak = document.getElementById('kasbonKontak').value.trim();
    if (!nama) { document.getElementById('kasbonNama').focus(); return; }
    closeKasbonModal();
    submitForm(nama, kontak);
}
function closeKasbonModal() { document.getElementById('kasbonOverlay').classList.add('hidden'); }
document.getElementById('kasbonOverlay')
    .addEventListener('click', e => { if (e.target === e.currentTarget) closeKasbonModal(); });
</script>
@endsection
