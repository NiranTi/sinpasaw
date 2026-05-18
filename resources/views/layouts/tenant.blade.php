<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">
</head>

<style>
    body{
        font-family: 'Be Vietnam Pro', sans-serif;
    }

    .font-manrope{
        font-family: 'Manrope', sans-serif;
    }
</style>

<body class="bg-[#F7F8F4]">

    <div class="flex min-h-screen">

        @include('components.tenant.sidebar')

        <main class="flex-1 px-10 py-8 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</body>
</html>
