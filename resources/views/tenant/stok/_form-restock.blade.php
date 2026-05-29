{{-- resources/views/tenant/stok/_form-restock.blade.php
     Partial: dipakai di desktop ($prefix='d') dan mobile ($prefix='m')
     $prefix digunakan untuk membedakan JS element ID antara dua form
──────────────────────────────────────────────────────────── --}}
@php $prefix ??= 'd'; @endphp

{{-- Nama Barang (select dari daftar barang yang ada) --}}
<div class="form-group">
    <label class="form-label">NAMA BARANG</label>
    <select name="barang_id" class="form-select" required>
        <option value="">Pilih barang...</option>
        @foreach ($semuaBarang as $b)
            <option value="{{ $b->barang_id }}">{{ $b->nama }} (stok: {{ $b->stok }})</option>
        @endforeach
    </select>
</div>

{{-- Supplier --}}
<div class="form-group">
    <label class="form-label">SUPPLIER</label>
    <input type="text" name="supplier_id" placeholder="Pilih supplier..."
    class="form-input" required>
</div>

{{-- Jumlah + Unit (2 kolom) --}}
<div class="grid grid-cols-2 gap-3 form-group">
    <div>
        <label class="form-label">JUMLAH</label>
        <input type="number" name="qty" id="{{ $prefix }}RsQty"
               class="form-input" placeholder="0" min="1"
               oninput="calcRestock('{{ $prefix }}')" required>
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
        <input type="number" name="harga_beli" id="{{ $prefix }}RsHarga"
               class="form-input has-prefix" placeholder="0" min="0"
               oninput="calcRestock('{{ $prefix }}')" required>
    </div>
</div>

{{-- Metode bayar ke supplier: QRIS / TUNAI --}}
<div class="flex gap-2 mb-4">
    <button type="button" id="{{ $prefix }}RsBtnQRIS"
            class="restock-pay-btn" onclick="setRestockMetode('{{ $prefix }}', 'qris')">
        <svg class="w-5 h-5 mx-auto mb-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"/>
        </svg>
        QRIS
    </button>
    <button type="button" id="{{ $prefix }}RsBtnTUNAI"
            class="restock-pay-btn active" onclick="setRestockMetode('{{ $prefix }}', 'tunai')">
        <svg class="w-5 h-5 mx-auto mb-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75"/>
        </svg>
        TUNAI
    </button>
</div>
<input type="hidden" name="metode_bayar" id="{{ $prefix }}RsMetode" value="tunai">

{{-- Ringkasan pembayaran supplier --}}
<div class="space-y-1.5 py-3 border-t border-b border-gray-100 text-sm mb-4">
    <div class="flex justify-between text-gray-500">
        <span>Subtotal</span><span id="{{ $prefix }}RsSubtotal">Rp 0</span>
    </div>
    <div class="flex justify-between font-bold text-gray-900 text-base">
        <span>Total</span>
        <span id="{{ $prefix }}RsTotal" style="color:var(--primary);">Rp 0</span>
    </div>
    {{-- Bayar: input langsung di baris summary --}}
    <div class="flex justify-between items-center text-gray-500">
        <span>Bayar</span>
        <input type="number" name="bayar" id="{{ $prefix }}RsBayar"
               class="text-right text-sm font-semibold text-gray-800 w-32 border-none outline-none bg-transparent"
               placeholder="0" min="0" oninput="calcRestock('{{ $prefix }}')" required>
    </div>
    <div class="flex justify-between text-gray-500">
        <span>Kembali</span><span id="{{ $prefix }}RsKembali">Rp 0</span>
    </div>
    <div class="flex justify-between font-semibold">
        <span>Kurang</span>
        <span id="{{ $prefix }}RsKurang" style="color:var(--danger);">Rp 0</span>
    </div>
</div>
