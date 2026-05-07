@extends('layouts.guest')
@section('title', 'ProMatch — Se connecter')

@section('content')
    <!-- Login Card -->
    <div
        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 w-full max-w-md p-8 relative z-10 border border-slate-100">

        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo" class="h-32 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Bon retour !</h1>
            <p class="text-slate-500 mt-2">Connectez-vous pour gérer vos réservations</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none transition-all"
                        placeholder="exemple@email.com" required autofocus>
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Mot de passe</label>
                <div class="relative">
                    <input type="password" name="password"
                        class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none transition-all"
                        placeholder="••••••••" required>
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-slate-600">Se souvenir de moi</span>
                </label>
                <a href="{{ route('password.request') }}" class="font-medium text-brand-600 hover:text-brand-700">Mot de
                    passe oublié ?</a>
            </div>

            <button id="login-btn" type="submit"
                onclick="this.disabled=true; this.innerHTML='<svg class=\'w-4 h-4 animate-spin\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'/><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8v8H4z\'/></svg> Connexion...'; this.form.submit();"
                class="w-full rounded-xl bg-brand-500 px-6 py-3 text-sm font-bold text-white hover:bg-brand-600 transition-colors shadow-lg shadow-brand-500/20 flex items-center justify-center gap-2">
                Se connecter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <!-- Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-slate-500">Ou continuer avec</span>
            </div>
        </div>

        <!-- Social Login -->
        <div class="grid grid-cols-1 gap-3">
            <button
                class="flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335" />
                </svg>
                <span class="text-sm font-medium text-slate-700">Google</span>
            </button>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-slate-500 mt-8">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-700">Créer un compte</a>
        </p>
    </div>

@endsection