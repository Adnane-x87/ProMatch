<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'ProMatch — Employee Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                <a href="{{ route('employee.dashboard') }}" class="block">
                    <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo"
                        class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-32 w-auto max-w-none">
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('employee.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-lg @if(request()->routeIs('employee.dashboard')) bg-brand-50 text-brand-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Planning du jour
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

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-slate-100 space-y-3">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}&background=4da565&color=fff" alt="UserAvatar"
                        class="w-9 h-9 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64">

            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-slate-200">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-slate-900">@yield('page-title')</h1>
                            <p class="text-sm text-slate-500">@yield('page-subtitle')</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto space-y-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/preline/dist/preline.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => window.HSStaticMethods?.autoInit());
    </script>
    @stack('scripts')
</body>

</html>
