{{-- resources/views/tenant/pengaturan.blade.php --}}
@extends('layouts.tenant')

@section('title', 'Pengaturan – ' . $tenant->nama_tenant)

@section('styles')
/* ── Pengaturan-specific styles ─────────────────────────── */

/* Avatar card (kiri) */
.avatar-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #eef0ef;
    padding: 2.5rem 1.5rem;
    text-align: center;
    position: sticky;
    top: 2rem;
}

/* Avatar wrapper dengan edit button overlay */
.avatar-wrap {
    position: relative;
    width: 96px;
    height: 96px;
    margin: 0 auto 1rem;
}
.avatar-img {
    width: 96px; height: 96px;
    border-radius: 50%; object-fit: cover;
    border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.avatar-placeholder {
    width: 96px; height: 96px; border-radius: 50%;
    background: var(--primary-soft);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 700; color: var(--primary);
    border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
/* Edit button (kamera) overlay di sudut kanan bawah avatar */
.avatar-edit-btn {
    position: absolute; bottom: 2px; right: 2px;
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--primary); color: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; border: 2px solid #fff;
    transition: background .15s;
}
.avatar-edit-btn:hover { background: #006435; }

/* Section card kanan */
.settings-section {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #eef0ef;
    padding: 1.75rem;
    margin-bottom: 1.25rem;
}
.settings-section:last-child { margin-bottom: 0; }

/* Select field — konsisten dengan form-input */
.form-select {
    width: 100%; padding: 12px 14px; border-radius: 12px;
    border: none; outline: none; background: #f0f2f1;
    font-family: 'Be Vietnam Pro', sans-serif; font-size: 14px; color: #1c1c1c;
    transition: background .15s, box-shadow .15s; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;
    padding-right: 36px;
}
.form-select:focus { background-color: #e8f5ef; box-shadow: 0 0 0 2px rgba(0,126,67,.2); }
@endsection

@section('content')
{{-- ── Page header ──────────────────────────────────────── --}}
<div class="mb-7">
    <p class="page-label">PENGATURAN</p>
    <h1 class="page-title">Pengaturan Akun</h1>
    <p class="page-subtitle">Atur informasi akun Anda dengan mudah dan aman.</p>
</div>

{{-- Validation errors global --}}
@if ($errors->any())
    <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium"
         style="background-color:var(--danger-soft);color:var(--danger);">
        {{ $errors->first() }}
    </div>
@endif

{{-- ════════════════════════════════════════════════════════
     Layout: Avatar card kiri + Form sections kanan
     Mobile: 1 kolom | Desktop: ~30% + ~70%
════════════════════════════════════════════════════════ --}}
<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ═══════════════════════════════════
         KIRI: Avatar + Info Toko
    ═══════════════════════════════════ --}}
    <div class="avatar-card w-full lg:w-56 xl:w-64 flex-shrink-0">

        {{-- Avatar dengan tombol edit (kamera) --}}
        <div class="avatar-wrap">
            @if ($tenant->foto)
                <img src="{{ asset($tenant->foto) }}"
                     alt="{{ $tenant->nama_tenant }}"
                     class="avatar-img" id="avatarPreview">
            @else
                <div class="avatar-placeholder" id="avatarPlaceholder">
                    {{ strtoupper(substr($tenant->nama_tenant, 0, 1)) }}
                </div>
                <img src="" alt="" class="avatar-img hidden" id="avatarPreview">
            @endif

            {{-- Tombol kamera — trigger hidden file input --}}
            <label class="avatar-edit-btn" for="fotoInput" title="Ganti foto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </label>
        </div>

        {{-- Nama toko --}}
        <p class="font-manrope font-bold text-gray-900 text-base">{{ $tenant->nama_tenant }}</p>

        {{-- Kategori (orange) --}}
        <p class="text-xs font-semibold uppercase tracking-wide mt-1 mb-3"
           style="color:var(--orange);">
            {{ strtoupper($tenant->kategori ?? 'Bahan Pangan') }}
        </p>

        {{-- Tanggal bergabung --}}
        <div class="flex items-center justify-center gap-1.5 text-xs text-gray-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Bergabung {{ $tenant->created_at->translatedFormat('F Y') }}
        </div>
    </div>

    {{-- ═══════════════════════════════════
         KANAN: Form sections
    ═══════════════════════════════════ --}}
    <div class="flex-1 min-w-0">

        {{-- ── Profil Toko ──────────────────────────────── --}}
        <div class="settings-section">
            <h3 class="font-manrope font-bold text-gray-900 mb-5">Profil Toko</h3>

            <form method="POST"
                  action="{{ route('tenant.pengaturan.profil') }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- File input tersembunyi untuk foto --}}
                <input type="file" id="fotoInput" name="foto"
                       accept="image/*" class="hidden"
                       onchange="previewAvatar(this)">

                {{-- Nama Toko (full width) --}}
                <div class="form-group">
                    <label class="form-label">NAMA TOKO</label>
                    <input type="text" name="nama_tenant"
                           class="form-input @error('nama_tenant') ring-2 ring-red-300 @enderror"
                           value="{{ old('nama_tenant', $tenant->nama_tenant) }}" required>
                    @error('nama_tenant')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Kategori + Blok (2 kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 form-group">
                    <div>
                        <label class="form-label">KATEGORI</label>
                        <select name="kategori"
                                class="form-select @error('kategori') ring-2 ring-red-300 @enderror">
                            @php
                                $kategoriOptions = [
                                    'Lapak Basah', 'Lapak Kering', 'Bahan Pangan Mentah',
                                    'Sayuran', 'Buah-buahan', 'Daging & Ikan',
                                    'Bumbu & Rempah', 'Sembako', 'Lainnya',
                                ];
                            @endphp
                            @foreach ($kategoriOptions as $opt)
                                <option value="{{ $opt }}"
                                    {{ old('kategori', $tenant->kategori) === $opt ? 'selected' : '' }}>
                                    {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">BLOK</label>
                        <input type="text" name="blok"
                               class="form-input @error('blok') ring-2 ring-red-300 @enderror"
                               value="{{ old('blok', $tenant->blok) }}" placeholder="L054">
                        @error('blok')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Nama Pemilik (full width) --}}
                <div class="form-group">
                    <label class="form-label">NAMA PEMILIK</label>
                    <input type="text" name="nama_pemilik"
                           class="form-input @error('nama_pemilik') ring-2 ring-red-300 @enderror"
                           value="{{ old('nama_pemilik', $user->name) }}" required>
                    @error('nama_pemilik')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email + No. HP (2 kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 form-group">
                    <div>
                        <label class="form-label">EMAIL</label>
                        <input type="email" name="email"
                               class="form-input @error('email') ring-2 ring-red-300 @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">NO. HP</label>
                        {{-- phone: tambahkan kolom ini ke tabel users via: php artisan make:migration add_phone_to_users_table --}}
                        <input type="tel" name="no_hp"
                               class="form-input"
                               value="{{ old('no_hp', $user->phone ?? '') }}"
                               placeholder="Masukkan no. HP disini...">
                    </div>
                </div>

                {{-- Simpan profil — pill button full width sesuai style login --}}
                <div class="flex justify-end mt-2">
                    <button type="submit" class="btn-primary" style="padding:13px 40px;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Ubah Kata Sandi ──────────────────────────── --}}
        <div class="settings-section">
            <h3 class="font-manrope font-bold text-gray-900 mb-5">Ubah Kata Sandi</h3>

            <form method="POST" action="{{ route('tenant.pengaturan.password') }}">
                @csrf
                @method('PUT')

                {{-- Kata Sandi Lama --}}
                <div class="form-group">
                    <label class="form-label">KATA SANDI LAMA</label>
                    <div class="input-prefix-wrap">
                        <input type="password" name="kata_sandi_lama"
                               class="form-input @error('kata_sandi_lama') ring-2 ring-red-300 @enderror"
                               placeholder="••••••••" autocomplete="current-password">
                        <button type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                onclick="togglePw('kata_sandi_lama', this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('kata_sandi_lama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata Sandi Baru + Ulangi (2 kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 form-group">
                    <div>
                        <label class="form-label">KATA SANDI BARU</label>
                        <div class="input-prefix-wrap">
                            <input type="password"
                                   name="kata_sandi_baru"
                                   id="kata_sandi_baru"
                                   class="form-input @error('kata_sandi_baru') ring-2 ring-red-300 @enderror"
                                   placeholder="Masukkan kata sandi disini..."
                                   autocomplete="new-password">
                            <button type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    onclick="togglePw('kata_sandi_baru', this)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('kata_sandi_baru')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">ULANGI KATA SANDI</label>
                        <div class="input-prefix-wrap">
                            <input type="password"
                                   name="kata_sandi_baru_confirmation"
                                   class="form-input"
                                   placeholder="Ulangi kata sandi disini..."
                                   autocomplete="new-password">
                            <button type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    onclick="togglePw('kata_sandi_baru_confirmation', this)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Simpan password --}}
                <div class="flex justify-end mt-2">
                    <button type="submit" class="btn-primary" style="padding:13px 40px;">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
/* ── Preview avatar sebelum upload ─────────────────────── */
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview     = document.getElementById('avatarPreview');
        const placeholder = document.getElementById('avatarPlaceholder');
        preview.src       = e.target.result;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}

/* ── Toggle show/hide password ──────────────────────────── */
function togglePw(fieldName, btn) {
    const input = document.querySelector(`[name="${fieldName}"]`);
    if (!input) return;
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    /* Swap ikon eye → eye-off */
    btn.innerHTML = isHidden
        ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
           </svg>`
        : `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
               <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
           </svg>`;
}
</script>
@endsection
