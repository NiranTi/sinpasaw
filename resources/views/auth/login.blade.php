{{-- resources/views/auth/login.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Tenant</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- GOOGLE FONT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Design tokens ─────────────────────────────────────── */
        :root {
            --primary:      #007E43;
            --primary-soft: #EAF7F1;
            --danger:       #BA1A1A;
            --danger-soft:  #FFDAD6;
        }

        /* ── Base ──────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;

            background: linear-gradient(
                to top right,
                #f0fff0 0%,   /* hijau sangat muda */
                #ffffff 25%,  /* putih */
                #f0fff0 50%,  /* hijau sangat muda */
                #ffffff 75%,  /* putih */
                #f0fff0 100%  /* hijau sangat muda */
            );
        }

        .font-manrope { font-family: 'Manrope', sans-serif; }

        .btn-submit:hover { background-color: #009750; }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#F8FAF7] via-[#F6F8F4] to-[#EAF7EE] flex items-center justify-center px-5 py-10 relative">

    <section class="relative z-10 w-full max-w-md flex flex-col items-center justify-center">

        {{-- TITLE --}}
        <div class="text-center my-8">

            <h1 class="font-manrope font-bold text-xl text-[#1A1C19]">
                Portal Sinpasa
            </h1>

            <p class="mt-2 text-[#5E6470] text-base leading-8 max-w-sm mx-auto text-center">
                Platform digital terintegrasi untuk pengelolaan tenant dan administrasi pasar.
            </p>

        </div>

        {{-- CARD --}}
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl
                    w-full max-w-sm
                    border border-white/60
                    shadow-xl
                    p-8">

            {{-- HEADING --}}
            <div>

                <h2 class="font-manrope text-lg text-[#1A1C19]">
                    Masuk
                </h2>

                <p class="font-manrope text-[#40493D] text-sm">
                    Isi kredensial Anda untuk masuk ke platform dan mulai mengelola.
                </p>

            </div>

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mt-6 rounded-xl bg-red-50 border border-red-200 p-4">
                    <ul class="space-y-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('login.authenticate') }}" method="POST" class="mt-4 space-y-3">
                @csrf

                {{-- EMAIL --}}
                <div>

                    <label class="text-[0.7rem] text-[#525252] uppercase">
                        Email
                    </label>

                    <div class="mt-2 flex items-center h-12
                                bg-[#F5F7F3]
                                rounded-2xl
                                px-4
                                border border-transparent
                                focus-within:outline-2
                                focus-within:outline-[#007E43]
                                transition-all duration-300">

                        {{-- ICON --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-[#9CA3AF]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>

                        <input
                            type="email"
                            name="email"
                            placeholder="Masukkan email disini..."
                            class="w-full bg-transparent outline-none border-none text-xs ml-3 placeholder:text-[#A1A1AA]"
                            required
                        >
                    </div>

                </div>

                {{-- PASSWORD --}}
                <div>

                    <label class="text-[0.7rem] text-[#525252] uppercase">
                        Kata Sandi
                    </label>

                    <div class="mt-2 flex items-center h-12
                                bg-[#F5F7F3]
                                rounded-2xl
                                px-5
                                border border-transparent
                                focus-within:outline-2
                                focus-within:outline-[#007E43]
                                transition-all duration-300">

                        {{-- ICON --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-[#9CA3AF]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.7"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z"/>
                        </svg>

                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan kata sandi disini..."
                            class="w-full bg-transparent outline-none border-none text-xs ml-3 placeholder:text-[#A1A1AA]"
                            required
                        >
                    </div>
                    {{-- FORGOT PASSWORD --}}
                    <div>
                        <a href="{{ route('password.request') }}" class="text-[#007e43] text-[0.7rem] hover:underline py-6">
                            Lupa kata sandi?
                        </a>
                    </div>
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="btn-submit w-full
                        rounded-full
                        bg-[#007e43]
                        transition-all duration-300
                        text-white
                        text-sm
                        py-2">
                    Masuk
                </button>

            </form>

            {{-- REGISTER --}}
            <div class="mt-8 text-[0.7rem] text-[#5E6470]">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="text-[#007e43] hover:underline">
                    Daftar disini
                </a>
            </div>

        </div>

    </section>

</body>
</html>
