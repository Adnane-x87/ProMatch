<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ProMatch — Auth')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @stack('styles')
</head>

<body
    class="antialiased bg-slate-50 text-slate-900 font-sans min-h-screen flex items-center justify-center p-4 relative">

    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl"></div>
        @stack('bg-decoration')
    </div>

    @yield('content')

    @stack('scripts')
</body>

</html>
