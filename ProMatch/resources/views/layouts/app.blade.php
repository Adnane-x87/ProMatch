<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ProMatch — Réservation de Terrains de Football</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
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
                            900: '#1a4a2b',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
        }

        .hero-bg {
            background-image: url('{{ asset('images/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body class="antialiased bg-white text-slate-900 font-sans">

    <!-- Navigation -->
    <header class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-7xl">
        <div class="glass-nav border border-slate-200 shadow-xl rounded-2xl md:rounded-full px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 md:h-20 items-center justify-between">
                
                <!-- Logo -->
                <div class="flex-shrink-0 relative h-16 md:h-20 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo" class="absolute -left-8 top-[52%] -translate-y-1/2 h-24 md:h-32 w-auto max-w-none">
                    </a>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex md:gap-10 items-center">
                    <a href="{{ url('/#terrains') }}" class="relative py-2 text-[13px] font-bold text-slate-600 uppercase tracking-wider group transition-colors">
                        Terrains
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ url('/#how') }}" class="relative py-2 text-[13px] font-bold text-slate-600 uppercase tracking-wider group transition-colors">
                        Comment ça marche
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ url('/contact') }}" class="relative py-2 text-[13px] font-bold text-slate-600 uppercase tracking-wider group transition-colors">
                        Contact
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </nav>

                <!-- CTA -->
                <div class="hidden md:flex items-center gap-6">
                    @guest
                        <a href="{{ url('/login') }}" class="text-sm font-bold text-slate-600 hover:text-brand-600 transition-colors">
                            Se connecter
                        </a>
                    @endguest

                    @auth
                        {{-- Profile dropdown --}}
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-brand-400 hover:bg-brand-50 transition-all group">
                                {{-- Avatar circle with initials --}}
                                <span class="w-8 h-8 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center select-none">
                                    {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->email, 0, 1)) }}
                                </span>
                                <span class="text-sm font-semibold text-slate-700 group-hover:text-brand-600 transition-colors max-w-[100px] truncate">
                                    {{ Auth::user()->first_name ?? Auth::user()->email }}
                                </span>
                                {{-- Chevron --}}
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown menu --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors rounded-b-2xl">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <a href="{{ url('/booking') }}" class="rounded-full bg-slate-900 px-8 py-3 text-sm font-bold text-white hover:bg-brand-600 transition-all hover:shadow-lg hover:shadow-slate-900/20 active:scale-95">
                        Réserver
                    </a>
                </div>

                <!-- Mobile toggle -->
                <div class="flex md:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2 text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-2 mx-4 overflow-hidden rounded-2xl border border-slate-100 bg-white/95 backdrop-blur-lg shadow-xl">
            <div class="px-4 py-6 space-y-3">
                <a href="{{ url('/#terrains') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 rounded-xl">Terrains</a>
                <a href="{{ url('/#how') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 rounded-xl">Comment ça marche</a>
                <a href="{{ url('/booking') }}" class="block w-full text-center mt-4 rounded-xl bg-brand-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-brand-600/20">
                    Réserver maintenant
                </a>
            </div>
        </div>
    </header>

    <main class="w-full">
        @yield('content')
    </main>

    <!-- PROFESSIONAL FOOTER -->
    <footer class="relative bg-neutral-900 text-white border-t border-slate-800 overflow-hidden">
        <!-- Geometric Background Pattern Overlay (Neutral Style) -->
        <div class="absolute inset-0 opacity-[0.05] pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M-10 110 L50 10 L110 110" fill="none" stroke="currentColor" stroke-width="0.2" class="text-slate-500" />
                <path d="M20 110 L65 30 L110 110" fill="none" stroke="currentColor" stroke-width="0.2" class="text-slate-500" />
                <path d="M-20 110 L40 50 L100 110" fill="none" stroke="currentColor" stroke-width="0.2" class="text-slate-500" />
            </svg>
        </div>

        <!-- Main Footer Content -->
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16">
                
                <!-- Brand Column -->
                <div class="md:col-span-5 space-y-4">
                    <a href="{{ url('/') }}" class="inline-block">
                        <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo" class="h-40 w-auto">
                    </a>
                    <p class="text-base text-slate-400 leading-relaxed max-w-md">
                        La référence pour la réservation de terrains de football au Maroc. Simple, rapide et sécurisé.
                    </p>
                    
                    <!-- Social Icons (STRICT SVG PRESERVATION) -->
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c0 .795-.646 1.44-1.441 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>
                        </a>
                    </div>

                    <!-- Back to Top Button -->
                    <div class="pt-8">
                        <a href="#" class="inline-flex items-center gap-3 px-6 py-3 border border-slate-700 rounded-sm text-[11px] font-bold uppercase tracking-widest text-slate-400 hover:bg-white hover:text-slate-900 transition-all group">
                            <x-lucide-chevron-up class="w-4 h-4 transition-transform group-hover:-translate-y-1" />
                            Back to Top
                        </a>
                    </div>
                </div>

                <!-- Spacer -->
                <div class="hidden md:block md:col-span-1"></div>

                <!-- Site Map -->
                <div class="md:col-span-3 space-y-8">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Navigation</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ url('/') }}" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Accueil</a></li>
                        <li><a href="{{ url('/#terrains') }}" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Nos Terrains</a></li>
                        <li><a href="{{ url('/#how') }}" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Comment ça marche</a></li>
                        <li><a href="{{ url('/booking') }}" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Réserver</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div class="md:col-span-3 space-y-8">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-white">Informations</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Mentions légales</a></li>
                        <li><a href="#" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Conditions générales</a></li>
                        <li><a href="#" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-sm text-slate-400 hover:text-emerald-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Copyright Bottom Bar (Neutral Reversion) -->
        <div class="border-t border-slate-800 py-6">
            <div class="mx-auto max-w-7xl px-4 text-center">
                <p class="text-sm text-slate-500">
                    © 2026 ProMatch. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
