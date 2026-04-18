<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ProMatch — Admin Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Add AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#f0f9f1',
                            100: '#dcf1df',
                            200: '#bbe2c3',
                            300: '#8dca9e',
                            400: '#5eac72',
                            500: '#4da565',
                            600: '#3d8a54',
                            700: '#327145',
                        }
                    }
                },
            },
        };
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">

    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="hidden lg:flex w-64 flex-col fixed inset-y-0 bg-white border-r border-slate-200">

            <!-- Logo -->
            <div class="h-20 relative flex items-center justify-center border-b border-slate-100">
                <a href="{{ url('/admin/dashboard') }}" class="block">
                    <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo"
                        class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-32 w-auto max-w-none">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ url('/admin/dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-lg @if(request()->is('admin/dashboard*')) bg-brand-50 text-brand-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Tableau de bord
                </a>
                <a href="{{ url('/admin/reservations') }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg @if(request()->is('admin/reservations*')) bg-brand-50 text-brand-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Réservations
                </a>
                <a href="{{ url('/admin/validations') }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg @if(request()->is('admin/validations*')) bg-brand-50 text-brand-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Validations CNI
                    {{-- TODO: wire up $pendingValidationsCount --}}
                    <span
                        class="ml-auto w-5 h-5 flex items-center justify-center rounded-full bg-rose-100 text-rose-600 text-xs font-bold">{{ $pendingValidationsCount ?? 0 }}</span>
                </a>
                <a href="{{ url('/admin/clients') }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg @if(request()->is('admin/clients*')) bg-brand-50 text-brand-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Clients
                </a>
                <a href="{{ url('/') }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-t mt-4 pt-4 border-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Accueil
                </a>
            </nav>

            <!-- User -->
            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin"
                        class="w-9 h-9 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">Administrateur</p>
                        <p class="text-xs text-slate-500 truncate">admin@promatch.ma</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64">

            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">@yield('page-title')</h1>
                            <p class="text-sm text-slate-500">@yield('page-subtitle')</p>
                        </div>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto space-y-6">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>