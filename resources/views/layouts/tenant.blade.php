{{-- resources/views/layouts/tenant.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Portal Tenant')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary:      #007E43;
            --primary-soft: #EAF7F1;

            --danger:       #BA1A1A;
            --danger-soft:  #FFDAD6;

            --orange:       #B66E00;
            --orange-soft:  #FBDDB0;
        }

        /* ───────────────── BASE ───────────────── */
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #FAFAF5;
            color: #1a1a1a;
        }

        .font-manrope {
            font-family: 'Manrope', sans-serif;
        }

        /* ───────────────── SCROLLBAR ───────────────── */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 2px;
        }

        /* ───────────────── BUTTONS ───────────────── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            background-color: var(--primary);
            color: #fff;

            padding: 10px 22px;
            border-radius: 999px;

            font-size: .7rem;
            font-weight: 500;

            text-decoration: none;
            border: none;
            cursor: pointer;

            transition: background-color .15s;
        }

        .btn-primary:hover {
            background-color: #009750;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            background: #fff;
            color: #374151;

            padding: 7px 14px;
            border-radius: 999px;

            transition:
                border-color .15s,
                background .15s,
                color .15s;
        }

        .btn-outline:hover {
            background-color: #E8E8E3;
            color: var(--primary);
        }

        .btn-outline:active {
            background: var(--primary);
            color: #fff;
        }

        .btn-outline-sm {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            background: #fff;
            color: #374151;

            padding: 6px 12px;
            border-radius: 10px;

            font-size: 12px;
            font-weight: 500;

            transition: .15s;
        }

        .btn-outline-sm:hover {
            background: #E8E8E3;
            color: var(--primary);
        }

        .btn-danger-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            background: var(--danger-soft);
            color: var(--danger);

            padding: 6px 10px;
            border-radius: 10px;

            font-size: 12px;
            font-weight: 500;

            transition: .15s;
        }

        .btn-danger-outline:hover {
            background: var(--danger);
            color: #fff;
        }

        .btn-lunasi {
            border: 1.5px solid #d1d5db;
            background: #fff;
            color: #374151;

            padding: 5px 18px;
            border-radius: 999px;

            font-size: 12px;
            font-weight: 500;

            transition: .15s;
        }

        .btn-lunasi:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-lunasi:active {
            background: var(--primary);
            color: #fff;
        }

        /* ───────────────── CARD ───────────────── */
        .section-card {
            background: #F4F4EF;
            border-radius: 16px;
        }

        .form-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
        }

        .stat-card {
            background: #F4F4EF;
            border-radius: 16px;
            padding: 1.5rem 1.8rem;
        }

        .stat-card.danger-card {
            background-color: var(--danger-soft);
        }

        .kasbon-card {
            background: #fff;
            border-radius: 12px;
            padding: 1rem 1.1rem;
        }

        /* ───────────────── BADGE ───────────────── */
        .badge-success { color: var(--primary); }
        .badge-orange  { color: var(--orange); }
        .badge-danger  { color: var(--danger); }
        .badge-gray    { color: #6b7280; }

        /* ───────────────── INPUT ───────────────── */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;

            font-size: 11px;
            font-weight: 700;

            letter-spacing: .07em;
            text-transform: uppercase;

            color: #6b7280;

            margin-bottom: 5px;
        }

        .form-input {
            width: 100%;

            padding: 12px 14px;

            border-radius: 12px;
            border: none;
            outline: none;

            background: #f0f2f1;

            font-size: 14px;

            transition: .15s;
        }

        .form-input:focus {
            background: #e8f5ef;
            box-shadow: 0 0 0 2px rgba(0,126,67,.2);
        }

        /* ───────────────── PAGE ───────────────── */
        .page-label {
            font-size: 12px;
            font-weight: 700;

            letter-spacing: .08em;
            text-transform: uppercase;

            color: var(--primary);

            margin-bottom: 4px;
        }

        .page-title {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;

            line-height: 1.1;
            letter-spacing: -.02em;
        }

        .page-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 6px;
        }

        /* ───────────────── HAMBURGER ───────────────── */
        .hamburger-btn {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 2rem;
            height: 2rem;

            border-radius: 999px;

            transition: .15s;
        }

        .hamburger-btn:hover {
            background: var(--primary-soft);
        }

        .hamburger-btn:active {
            background: var(--primary);
        }

        .hamburger-btn:active svg {
            color: white !important;
        }

        @yield('styles')
    </style>
</head>

<body>
    <x-tenant.sidebar />

    <main class="lg:ml-56 min-h-screen p-4 lg:p-8">

        {{-- Mobile topbar --}}
        <div class="flex items-center justify-between mb-5 lg:hidden">
            <button onclick="openSidebar()"
                    class="hamburger-btn"
                    aria-label="Buka menu">

                <svg class="w-4 h-4 text-gray-600"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            @if(isset($tenant))
                @if($tenant->foto)
                    <img src="{{ asset($tenant->foto) }}"
                         alt="{{ $tenant->nama_tenant }}"
                         class="w-6 h-6 rounded-full object-cover">
                @else
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold"
                         style="background-color:var(--primary);">
                        {{ strtoupper(substr($tenant->nama_tenant, 0, 1)) }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Alert --}}
        @include('components.tenant.alert-modal')

        {{-- Content --}}
        @yield('content')

    </main>

    @yield('modals')

    <script>
        function setPeriode(val) {
            document.getElementById('periodeInput').value = val;

            document.querySelectorAll('.periode-btn')
                .forEach(btn => btn.classList.remove('active'));

            document.getElementById('btn-' + val)
                ?.classList.add('active');

            document.getElementById('periodeForm')
                ?.submit();
        }
    </script>

    @yield('scripts')
</body>
</html>
