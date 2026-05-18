<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
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
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-5 py-10">

<section class="w-full max-w-md flex flex-col items-center">

    <div class="text-center my-8">

        <h1 class="font-manrope font-bold text-xl text-[#1A1C19]">
            Portal Sinpasa
        </h1>

        <p class="mt-2 text-[#5E6470] text-base leading-8 max-w-sm mx-auto">
            Platform digital terintegrasi untuk pengelolaan tenant dan administrasi pasar.
        </p>

    </div>

    <div class="bg-white/90 backdrop-blur-sm rounded-3xl
                w-full max-w-sm
                border border-white/60
                shadow-xl
                p-8">

        <div>
            <h2 class="font-manrope text-lg text-[#1A1C19]">
                Password Baru
            </h2>

            <p class="font-manrope text-[#40493D] text-sm">
                Silahkan tulis password baru Anda.
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="mt-4 space-y-3">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request()->email }}">

            {{-- PASSWORD --}}
            <div>
                <label class="text-[0.7rem] text-[#525252] uppercase">
                    Kata Sandi
                </label>

                <div class="mt-2 flex items-center h-12 bg-[#F5F7F3] rounded-2xl px-4">

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

            {{-- PASSWORD CONFIRMATION --}}
            <div>
                <label class="text-[0.7rem] text-[#525252] uppercase">
                    Ulangi Kata Sandi
                </label>

                <div class="mt-2 flex items-center h-12 bg-[#F5F7F3] rounded-2xl px-4">

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
                Konfirmasi Password
            </button>

        </form>

    </div>

</section>

</body>
</html>
