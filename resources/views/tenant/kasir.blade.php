{{-- resources/views/tenant/kasir.blade.php --}}
@extends('layouts.tenant')

@section('title', 'Kasir – ' . $tenant->nama_tenant)

@section('styles')
/* ── Kasir-specific styles ──────────────────────────────── */

/* Product card */
.product-card {
    background:#fff; border-radius:16px; border:1px solid #eef0ef;
    overflow:hidden; transition:box-shadow .15s;
}
.product-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }

/* Product image placeholder */
.product-img {
    width:100%; aspect-ratio:1; object-fit:cover;
    background:linear-gradient(135deg,#A8D5BE,#EAF7F1);
    display:flex; align-items:center; justify-content:center;
    font-size:2rem; color:#007E43; font-weight:700;
}

/* Add button circle */
.btn-add-circle {
    width:36px; height:36px; border-radius:50%;
    background:var(--primary); color:#fff; border:none;
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; transition:background .15s; flex-shrink:0;
}
.btn-add-circle:hover { background:#006435; }

/* Qty control in product card */
.qty-control {
    display:none; align-items:center; gap:6px;
    background:var(--primary-soft); border-radius:999px; padding:4px 10px;
}
.qty-btn {
    width:24px; height:24px; border-radius:50%; border:none;
    background:var(--primary); color:#fff; font-size:16px; font-weight:600;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    transition:background .15s; line-height:1;
}
.qty-btn:hover { background:#006435; }
.qty-value { font-size:14px; font-weight:700; color:var(--primary); min-width:20px; text-align:center; }

/* Cart panel */
.cart-panel {
    background:#fff; border-radius:16px; border:1px solid #eef0ef;
    display:flex; flex-direction:column; height:fit-content;
    position:sticky; top:2rem;
}
.cart-item {
    display:flex; align-items:center; justify-content:space-between; gap:8px;
    padding:10px 0; border-bottom:1px solid #f3f4f6;
}
.cart-item:last-child { border-bottom:none; }

/* Payment method toggle */
.pay-method-btn {
    flex:1; padding:14px 8px; border-radius:12px; border:1.5px solid #e5e7eb;
    background:#fff; cursor:pointer; transition:all .15s; text-align:center;
}
.pay-method-btn.active {
    border-color:var(--primary); background:var(--primary); color:#fff;
}
.pay-method-btn:not(.active):hover { border-color:var(--primary); }

/* Search bar */
.search-wrap {
    display:flex; align-items:center; gap:3px;
    background:#fff; border:1.5px solid #e5e7eb; border-radius:999px;
    padding:3px 3px 3px 16px; max-width:480px; width:100%;
}
.search-input {
    flex:1; border:none; outline:none; background:transparent;
    font-family:'Be Vietnam Pro',sans-serif; font-size:14px; color:#374151;
}
.search-input::placeholder { color:#b0b8bf; }
@endsection

@section('content')
{{-- ── Page header ──────────────────────────────────────── --}}
<div class="mb-6">
    <p class="page-label">KASIR</p>
    <h1 class="page-title">Kasir Digital</h1>
    <p class="page-subtitle">Pusat pencatatan penjualan secara real-time memudahkan pengelolaan transaksi harian tenant.</p>
</div>

{{-- ── Layout: Produk (kiri) + Cart (kanan) ────────────── --}}
<div class="flex flex-col lg:flex-row gap-6 items-start">

    {{-- ════════════════════════════════════════════════════
         KIRI: Search + Product Grid
         Mobile: full width | Desktop: flex-1
    ════════════════════════════════════════════════════ --}}
    <div class="flex-1 min-w-0">

        {{-- Search bar --}}
        <div class="flex justify-center mb-6">
            <form method="GET" action="{{ route('tenant.kasir') }}" class="search-wrap">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari produk ..."
                       class="search-input" autocomplete="off">
                <button type="submit" class="btn-primary" style="padding:10px 22px;font-size:14px;">Cari</button>
            </form>
        </div>

        {{-- Product grid — 2 kolom --}}
        <div class="grid grid-cols-2 gap-4" id="productGrid">
            @forelse ($barang as $b)
                <div class="product-card" data-id="{{ $b->barang_id }}" data-nama="{{ $b->nama }}" data-harga="{{ $b->harga_jual }}">
                    {{-- Product image placeholder (warna dari initial nama) --}}
                    <div class="product-img rounded-t-2xl">
                        <span>{{ strtoupper(substr($b->nama, 0, 1)) }}</span>
                    </div>
                    <div class="p-4">
                        <p class="font-manrope font-bold text-base text-gray-900 leading-tight">{{ $b->nama }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Stok: {{ $b->stok }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="font-bold text-sm" style="color:var(--primary);">
                                Rp {{ number_format($b->harga_jual, 0, ',', '.') }}
                            </p>
                            {{-- + button (default) --}}
                            <button class="btn-add-circle btn-add"
                                    onclick="addToCart({{ $b->barang_id }}, '{{ addslashes($b->nama) }}', {{ $b->harga_jual }}, {{ $b->stok }})"
                                    aria-label="Tambah ke keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                            {{-- Qty control (muncul saat item sudah di keranjang) --}}
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
    </div>

    {{-- ════════════════════════════════════════════════════
         KANAN: Cart Panel
         Mobile: full width di bawah | Desktop: w-72 sticky
    ════════════════════════════════════════════════════ --}}
    <div class="cart-panel w-full lg:w-72 xl:w-80 p-5">
        {{-- Kode transaksi --}}
        <p class="text-xs text-gray-400 text-right mb-3 font-mono">{{ $kodeTransaksi }}</p>

        {{-- Cart items --}}
        <div id="cartItems" class="min-h-[60px] mb-4">
            <p id="cartEmpty" class="text-sm text-gray-400 text-center py-4">Keranjang kosong</p>
        </div>

        {{-- Payment method toggle: QRIS | TUNAI --}}
        <div class="flex gap-2 mb-4">
            <button id="btnQRIS" class="pay-method-btn" onclick="setMetode('qris')">
                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 18.75h.75v.75h-.75v-.75zM18.75 13.5h.75v.75h-.75v-.75zM18.75 18.75h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/>
                </svg>
                <p class="text-xs font-semibold">QRIS</p>
            </button>
            <button id="btnTUNAI" class="pay-method-btn active" onclick="setMetode('tunai')">
                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                </svg>
                <p class="text-xs font-semibold">TUNAI</p>
            </button>
        </div>

        {{-- Nominal diterima --}}
        <div class="form-group">
            <label class="form-label">NOMINAL DITERIMA</label>
            <div class="input-prefix-wrap">
                <span class="input-prefix">Rp</span>
                <input type="number" id="nominalInput"
                       class="form-input has-prefix" placeholder="0"
                       oninput="updateCalc()" min="0">
            </div>
        </div>

        {{-- Financial summary --}}
        <div class="space-y-1.5 py-3 border-t border-gray-100 text-sm">
            <div class="flex justify-between text-gray-500">
                <span>Subtotal</span>
                <span id="calcSubtotal">Rp 0</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Pajak</span>
                <span>Rp 0</span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 text-base pt-1">
                <span>Total</span>
                <span id="calcTotal" style="color:var(--primary);">Rp 0</span>
            </div>
            <div class="flex justify-between text-gray-500 pt-1 border-t border-gray-100">
                <span>Bayar</span>
                <span id="calcBayar">Rp 0</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Kembali</span>
                <span id="calcKembali">Rp 0</span>
            </div>
            <div class="flex justify-between font-semibold">
                <span>Kurang</span>
                <span id="calcKurang" style="color:var(--danger);">Rp 0</span>
            </div>
        </div>

        {{-- Print Struk checkbox --}}
        <label class="flex items-center gap-2 text-sm text-gray-600 mt-3 cursor-pointer select-none">
            <input type="checkbox" id="printStruk" class="w-4 h-4 accent-green-700"> Print Struk
        </label>

        {{-- Bayar Sekarang --}}
        <button onclick="handlePayNow()"
                class="btn-primary w-full mt-4" style="width:100%;">
            Bayar Sekarang
        </button>
    </div>
</div>

{{-- Hidden form untuk submit ke server --}}
<form id="payForm" method="POST" action="{{ route('tenant.kasir.store') }}" class="hidden">
    @csrf
    <input type="hidden" name="items"            id="formItems">
    <input type="hidden" name="metode_bayar"      id="formMetode" value="tunai">
    <input type="hidden" name="nominal"           id="formNominal">
    <input type="hidden" name="nama_pelanggan"    id="formNamaPelanggan">
    <input type="hidden" name="kontak_pelanggan"  id="formKontakPelanggan">
</form>
@endsection

{{-- ════════════════════════════════════════════════════════════
     KASBON POPUP MODAL — muncul saat nominal < total belanja
     Sesuai desain alert-kasbon.png
════════════════════════════════════════════════════════════ --}}
@section('modals')
<div id="kasbonOverlay"
     class="fixed inset-0 z-50 flex items-center justify-center hidden"
     style="background:rgba(0,0,0,0.25);">
    <div class="relative bg-white rounded-2xl shadow-xl w-80 p-6"
         style="font-family:'Be Vietnam Pro',sans-serif;">

        {{-- X button --}}
        <button onclick="closeKasbonModal()"
                class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Judul --}}
        <h3 class="font-manrope font-bold text-gray-900 text-base text-center mb-2">
            Nominal diterima kurang dari total belanja
        </h3>
        <p class="text-sm text-gray-500 text-center mb-5 leading-relaxed">
            Dengan melanjutkan transaksi, sistem akan mencatat sebagai kasbon.
            Silakan isi nama dan kontak pelanggan, lalu pilih <strong>Lanjutkan</strong> untuk menyimpan kasbon
            atau <strong>Kembali</strong> untuk membatalkan.
        </p>

        {{-- Form kasbon --}}
        <div class="form-group">
            <label class="form-label">NAMA PELANGGAN</label>
            <input type="text" id="kasbonNama" class="form-input" placeholder="Nama pelanggan...">
        </div>
        <div class="form-group" style="margin-bottom:1.5rem;">
            <label class="form-label">NO. HP PELANGGAN</label>
            <input type="tel" id="kasbonKontak" class="form-input" placeholder="08xxxxxxxxxx">
        </div>

        {{-- Tombol Kembali (outline) dan Lanjutkan (primary) --}}
        <div class="flex gap-3">
            <button onclick="closeKasbonModal()" class="btn-outline flex-1">Kembali</button>
            <button onclick="submitKasbon()"
                    class="btn-primary flex-1" style="padding:13px 8px;">Lanjutkan</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
/* ── State keranjang ─────────────────────────────────────── */
const cart  = {};   // { barang_id: { id, nama, harga, qty, maxStok } }
let   metode = 'tunai';

/* ── Format rupiah ───────────────────────────────────────── */
function rp(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

/* ── Tambah item ke cart ─────────────────────────────────── */
function addToCart(id, nama, harga, maxStok) {
    if (cart[id]) { cart[id].qty++; }
    else          { cart[id] = { id, nama, harga, qty: 1, maxStok }; }
    renderAll();
}

/* ── Ubah qty item ───────────────────────────────────────── */
function updateQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderAll();
}

/* ── Hapus item dari cart ────────────────────────────────── */
function removeFromCart(id) {
    delete cart[id];
    renderAll();
}

/* ── Render cart panel + product card state ──────────────── */
function renderAll() {
    renderCart();
    renderProductCards();
    updateCalc();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty     = document.getElementById('cartEmpty');
    const items     = Object.values(cart);

    if (items.length === 0) {
        container.innerHTML = '';
        empty.style.display = 'block';
        return;
    }
    empty.style.display = 'none';

    container.innerHTML = items.map(item => `
        <div class="cart-item">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-800 truncate">${item.nama}</p>
                <p class="text-xs text-gray-400">Jumlah: ${item.qty}</p>
            </div>
            <span class="text-sm font-bold flex-shrink-0" style="color:var(--primary);">
                ${rp(item.qty * item.harga)}
            </span>
            <button onclick="removeFromCart(${item.id})"
                    class="w-6 h-6 flex items-center justify-center text-gray-300 hover:text-red-400 flex-shrink-0 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `).join('');
}

/* ── Update tampilan tombol +/qty di product cards ───────── */
function renderProductCards() {
    document.querySelectorAll('[data-id]').forEach(card => {
        const id      = parseInt(card.dataset.id);
        const inCart  = cart[id];
        const btnAdd  = card.querySelector('.btn-add');
        const qtyCtrl = card.querySelector('.qty-control');
        const qtyVal  = card.querySelector('.qty-value');

        if (inCart) {
            btnAdd.style.display  = 'none';
            qtyCtrl.style.display = 'flex';
            qtyVal.textContent    = inCart.qty;
        } else {
            btnAdd.style.display  = 'flex';
            qtyCtrl.style.display = 'none';
        }
    });
}

/* ── Hitung dan tampilkan ringkasan keuangan ─────────────── */
function updateCalc() {
    const subtotal = Object.values(cart).reduce((s, i) => s + i.qty * i.harga, 0);
    const total    = subtotal; // pajak = 0
    const nominal  = parseFloat(document.getElementById('nominalInput').value) || 0;
    const kembalian = Math.max(0, nominal - total);
    const kurang    = Math.max(0, total - nominal);

    document.getElementById('calcSubtotal').textContent = rp(subtotal);
    document.getElementById('calcTotal').textContent    = rp(total);
    document.getElementById('calcBayar').textContent    = rp(nominal);
    document.getElementById('calcKembali').textContent  = rp(kembalian);
    document.getElementById('calcKurang').textContent   = rp(kurang);

    /* Warnai kurang merah jika > 0 */
    document.getElementById('calcKurang').style.color = kurang > 0 ? 'var(--danger)' : '#6b7280';
}

/* ── Set metode pembayaran ───────────────────────────────── */
function setMetode(m) {
    metode = m;
    document.getElementById('btnQRIS').classList.toggle('active',  m === 'qris');
    document.getElementById('btnTUNAI').classList.toggle('active', m === 'tunai');
}

/* ── Klik "Bayar Sekarang" ───────────────────────────────── */
function handlePayNow() {
    const items = Object.values(cart);
    if (items.length === 0) {
        showAlert('Keranjang masih kosong!');
        return;
    }
    const subtotal = items.reduce((s, i) => s + i.qty * i.harga, 0);
    const nominal  = parseFloat(document.getElementById('nominalInput').value) || 0;
    const kurang   = subtotal - nominal;

    if (kurang > 0) {
        /* Nominal kurang → tampilkan kasbon popup */
        document.getElementById('kasbonOverlay').classList.remove('hidden');
    } else {
        submitForm(); // langsung submit
    }
}

/* ── Submit form pembayaran ──────────────────────────────── */
function submitForm(namaPelanggan = '', kontakPelanggan = '') {
    const items = Object.values(cart).map(i => ({
        id: i.id, nama: i.nama, harga: i.harga, qty: i.qty
    }));
    document.getElementById('formItems').value           = JSON.stringify(items);
    document.getElementById('formMetode').value          = metode;
    document.getElementById('formNominal').value         = document.getElementById('nominalInput').value || 0;
    document.getElementById('formNamaPelanggan').value   = namaPelanggan;
    document.getElementById('formKontakPelanggan').value = kontakPelanggan;
    document.getElementById('payForm').submit();
}

/* ── Kasbon modal: lanjutkan ─────────────────────────────── */
function submitKasbon() {
    const nama   = document.getElementById('kasbonNama').value.trim();
    const kontak = document.getElementById('kasbonKontak').value.trim();
    if (!nama) {
        document.getElementById('kasbonNama').focus();
        return;
    }
    closeKasbonModal();
    submitForm(nama, kontak);
}
function closeKasbonModal() {
    document.getElementById('kasbonOverlay').classList.add('hidden');
}
/* Tutup kasbon modal klik di luar */
document.getElementById('kasbonOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeKasbonModal();
});
</script>
@endsection
