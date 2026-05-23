{{-- resources/views/components/tenant/alert-modal.blade.php
     ──────────────────────────────────────────────────────────
     Modal sukses — muncul otomatis jika session('alert') ada.
     Bisa juga dipanggil via JS: showAlert('Pesan sukses!')
     ────────────────────────────────────────────────────────── --}}

{{-- ── Overlay + modal shell — selalu ada di DOM, tersembunyi default ── --}}
<div id="alertOverlay"
     class="fixed inset-0 z-50 flex items-center justify-center hidden"
     style="background:rgba(0,0,0,0.25);">

    {{-- Modal card — sesuai desain: sudut bulat, bg putih, shadow --}}
    <div class="relative bg-white rounded-2xl shadow-xl w-80 p-8 text-center"
         style="font-family:'Be Vietnam Pro',sans-serif;">

        {{-- Tombol tutup (×) --}}
        <button onclick="closeAlert()"
                class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors"
                aria-label="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Pesan utama --}}
        <p id="alertMessage" class="text-base font-semibold text-gray-900 mb-6 leading-snug">
            {{-- diisi via JS atau session --}}
        </p>

        {{-- Ikon centang hijau besar — sesuai desain --}}
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto"
             style="background-color:#007E43;">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    </div>
</div>

{{-- ── JS helper ── --}}
<script>
    /* Tampilkan alert dengan pesan tertentu */
    function showAlert(message) {
        document.getElementById('alertMessage').textContent = message;
        document.getElementById('alertOverlay').classList.remove('hidden');
    }
    /* Tutup alert */
    function closeAlert() {
        document.getElementById('alertOverlay').classList.add('hidden');
    }
    /* Tutup jika klik overlay (luar modal) */
    document.getElementById('alertOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeAlert();
    });
    /* Auto-show jika session alert ada — dipanggil setelah DOM load */
    @if(session('alert'))
        document.addEventListener('DOMContentLoaded', function() {
            showAlert(@json(session('alert')));
        });
    @endif
</script>
