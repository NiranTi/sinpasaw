<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tenant</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #007E43;
            --primary-soft: #EAF7F1;
            --danger: #BA1A1A;
            --danger-soft: #FFDAD6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: linear-gradient(
                to top right,
                #f0fff0 0%,
                #ffffff 25%,
                #f0fff0 50%,
                #ffffff 75%,
                #f0fff0 100%
            );
        }

        .font-manrope {
            font-family: 'Manrope', sans-serif;
        }

        .btn-submit:hover {
            background-color: #009750;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-5 py-10">

<section class="w-full max-w-md flex flex-col items-center">

    {{-- TITLE --}}
    <div class="text-center my-8">

        <h1 class="font-manrope font-bold text-xl text-[#1A1C19]">
            Portal Sinpasa
        </h1>

        <p class="mt-2 text-[#5E6470] text-base leading-8 max-w-sm mx-auto">
            Platform digital terintegrasi untuk pengelolaan tenant dan administrasi pasar.
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl
                w-full max-w-sm
                border border-white/60
                shadow-xl
                p-8">

        <div>
            <h2 class="font-manrope text-lg text-[#1A1C19]">
                Daftar
            </h2>

            <p class="font-manrope text-[#40493D] text-sm">
                Isi formulir pendaftaran tenant untuk mendapatkan akses ke platform manajemen.
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
        <form method="POST" action="{{ route('register.store') }}" class="mt-4 space-y-3">
            @csrf

            {{-- NAMA TENANT --}}
            <div>
                <label class="text-[0.7rem] text-[#525252] uppercase">
                    Nama Tenant
                </label>

                <div class="mt-2 flex items-center h-12 bg-[#F5F7F3] rounded-2xl px-4 focus-within:outline-2
                                focus-within:outline-[#007E43]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-[#9CA3AF]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.7"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>

                    <input
                        type="text"
                        name="name"
                        placeholder="Masukkan nama tenant disini..."
                        class="w-full bg-transparent outline-none border-none text-xs ml-3 placeholder:text-[#A1A1AA]"
                        required
                    >
                </div>
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-[0.7rem] text-[#525252] uppercase">
                    Email
                </label>

                <div class="mt-2 flex items-center h-12 bg-[#F5F7F3] rounded-2xl px-4 focus-within:outline-2
                                focus-within:outline-[#007E43]">
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

                <div class="mt-2 flex items-center h-12 bg-[#F5F7F3] rounded-2xl px-4 focus-within:outline-2
                                focus-within:outline-[#007E43]">
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
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div>
                <label class="text-[0.7rem] text-[#525252] uppercase">
                    Ulangi Kata Sandi
                </label>

                <div class="mt-2 flex items-center h-12 bg-[#F5F7F3] rounded-2xl px-4 focus-within:outline-2
                                focus-within:outline-[#007E43]">
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
                        name="password_confirmation"
                        placeholder="Ulangi kata sandi disini..."
                        class="w-full bg-transparent outline-none border-none text-xs ml-3 placeholder:text-[#A1A1AA]"
                        required
                    >
                </div>
            </div>

            <button
                type="submit"
                class="w-full rounded-full bg-[#007e43]
                    transition-all duration-300
                    text-white text-sm py-2">
                Daftar
            </button>
        </form>

        <div class="mt-8 text-[0.7rem] text-[#5E6470]">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="text-[#007e43] hover:underline">
                Masuk disini
            </a>
        </div>

    </div>

</section>

</body>
</html>
