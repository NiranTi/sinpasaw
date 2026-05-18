{{-- resources/views/components/tenant/sidebar.blade.php --}}

{{-- ══════════════════════════════════════════════════════════════════
     OVERLAY — latar gelap di belakang sidebar saat mobile
     • Hanya muncul di mobile (<lg), klik overlay menutup sidebar
     ══════════════════════════════════════════════════════════════════ --}}
<div id="sidebarOverlay"
     class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden"
     onclick="closeSidebar()">
</div>

{{-- ══════════════════════════════════════════════════════════════════
     SIDEBAR PANEL
     • Desktop (lg+) : selalu tampil, fixed kiri, lebar 14rem (w-56)
     • Mobile (<lg)  : tersembunyi geser ke kiri (-translate-x-full),
       muncul saat JS menambahkan translate-x-0
     ══════════════════════════════════════════════════════════════════ --}}
<aside id="sidebarPanel"
       style="background:#F4F4EF; font-family:'Be Vietnam Pro', sans-serif;"
       class="fixed top-0 left-0 h-screen w-56 flex flex-col z-30
              -translate-x-full lg:translate-x-0
              transition-transform duration-300 ease-in-out">

    {{-- Portal badge --}}
    <div class="p-4 bg-white mx-6 mt-4 rounded-xl">
        <p class="text-xs font-manrope tracking-widest uppercase text-gray-400 mb-0.5">PORTAL TENANT</p>
        <p class="font-manrope text-xs text-[#1A1C19]">Pasar Modern Sinpasa Summarecon Bandung</p>
    </div>

    {{-- ── Nav links ──────────────────────────────────────────────────
         State dari gambar Dashboard.png (urutan atas → bawah):
           1. Default  → bg transparan, teks abu-abu
           2. Hover    → bg hijau muda (#EAF7F1), teks hijau (#007E43)
           3. Active   → bg hijau gelap (#007E43), teks putih
         Hover hanya berlaku pada item NON-aktif
    ────────────────────────────────────────────────────────────────── --}}
    <nav class="flex-1 px-3 py-4 space-y-1">
        @php
            $links = [
                ['route' => 'tenant.dashboard', 'label' => 'Beranda', 'icon' => 'home'],
                ['route' => 'tenant.kasir',   'label' => 'Kasir',   'icon' => 'cashier'],
                ['route' => 'tenant.stok',    'label' => 'Stok Barang', 'icon' => 'box'],
                ['route' => 'tenant.pengaturan', 'label' => 'Pengaturan', 'icon' => 'settings'],
            ];
        @endphp

        @foreach ($links as $link)
            @php $active = request()->routeIs($link['route']); @endphp

            <a href="{{ route($link['route']) }}"
               onclick="closeSidebar()"
               class="flex items-center gap-3 px-3 py-2.5 rounded-4xl text-[0.7rem]
                      transition-all duration-150
                      {{ $active ? 'text-white' : 'text-gray-500' }}"
               style="{{ $active ? 'background-color:#007E43;' : '' }}"
               {{-- Hover: hanya item non-aktif yang berubah warna --}}
               @if(!$active)
                   onmouseenter="this.style.backgroundColor='#E8E8E3'; this.style.color='#007E43';"
                   onmouseleave="this.style.backgroundColor=''; this.style.color='';"
               @endif>

                {{-- Icon svg berdasarkan tipe --}}
                @if ($link['icon'] === 'home')
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                @elseif ($link['icon'] === 'cashier')
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                @elseif ($link['icon'] === 'box')
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                @endif

                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
</aside>

{{-- ══════════════════════════════════════════════════════════════════
     JS: fungsi buka/tutup sidebar untuk mobile
     openSidebar()  → dipanggil dari tombol hamburger di beranda.blade.php
     closeSidebar() → dipanggil dari overlay atau setelah klik nav item
     ══════════════════════════════════════════════════════════════════ --}}
<script>
    function openSidebar() {
        document.getElementById('sidebarPanel').classList.remove('-translate-x-full');
        document.getElementById('sidebarPanel').classList.add('translate-x-0');
        document.getElementById('sidebarOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Cegah scroll body di balik sidebar
    }
    function closeSidebar() {
        document.getElementById('sidebarPanel').classList.add('-translate-x-full');
        document.getElementById('sidebarPanel').classList.remove('translate-x-0');
        document.getElementById('sidebarOverlay').classList.add('hidden');
        document.body.style.overflow = ''; // Kembalikan scroll
    }
</script>
