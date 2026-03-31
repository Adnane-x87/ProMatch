@extends('layouts.app')

@section('content')
    <!-- HERO SECTION -->
    <section class="relative min-h-[600px] lg:min-h-[700px] hero-bg flex items-center">
        
        <!-- Overlays -->
        <div class="absolute inset-0 bg-slate-900/60"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-slate-900/30"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 w-full pt-28">
            <div class="max-w-2xl py-16">
                
                <!-- Headline -->
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-[1.1] mb-4">
                    Votre terrain<br>
                    <span class="text-brand-400">vous attend.</span>
                </h1>

                <!-- Description -->
                <p class="text-base text-slate-200 leading-relaxed max-w-lg mb-8">
4 terrains de football avec gazon synthétique, éclairage LED et vestiaires. Réservation en ligne simple.                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/booking') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-500 transition-colors shadow-lg shadow-brand-600/25">
                        Réserver maintenant
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="#terrains" class="inline-flex items-center gap-2 rounded-lg bg-white/10 backdrop-blur-sm px-5 py-2.5 text-sm font-bold text-white border border-white/20 hover:bg-white/20 transition-colors">
                        Voir les terrains
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Terrains Section -->
    <section id="terrains" class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-50 text-brand-600 text-xs font-bold uppercase tracking-wide mb-3">
                    Nos Installations
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                    Des terrains professionnels
                </h2>
                <p class="text-base text-slate-500">
                    Gazon synthétique dernière génération, éclairage LED homologué et vestiaires premium.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                
                <!-- Terrain 1 -->
                <article class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover-lift">
                    <div class="h-44 bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center relative">
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                            Disponible
                        </span>
                        <svg class="w-16 h-16 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-base font-bold text-slate-900">Terrain N°1</h3>
                            <span class="text-xs font-bold text-slate-400 uppercase">5vs5</span>
                        </div>
                        <ul class="space-y-1.5 mb-4 text-sm text-slate-600">
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Gazon synthétique Pro
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Éclairage LED 500 Lux
                            </li>
                        </ul>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase block">À partir de</span>
                                <span class="text-lg font-bold text-slate-900">300 Dh<span class="text-xs text-slate-400">/h</span></span>
                            </div>
                            <a href="{{ url('/booking') }}" class="px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-brand-600 transition-colors">
                                Réserver
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Terrain 2 -->
                <article class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover-lift">
                    <div class="h-44 bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center relative">
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">
                            Derniers créneaux
                        </span>
                        <svg class="w-16 h-16 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-base font-bold text-slate-900">Terrain N°2</h3>
                            <span class="text-xs font-bold text-slate-400 uppercase">7vs7</span>
                        </div>
                        <ul class="space-y-1.5 mb-4 text-sm text-slate-600">
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Gazon synthétique Pro
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Éclairage LED 500 Lux
                            </li>
                        </ul>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase block">À partir de</span>
                                <span class="text-lg font-bold text-slate-900">450 Dh<span class="text-xs text-slate-400">/h</span></span>
                            </div>
                            <a href="{{ url('/booking') }}" class="px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-brand-600 transition-colors">
                                Réserver
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Terrain 3 -->
                <article class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover-lift">
                    <div class="h-44 bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center relative">
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                            Disponible
                        </span>
                        <svg class="w-16 h-16 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-base font-bold text-slate-900">Terrain N°3</h3>
                            <span class="text-xs font-bold text-slate-400 uppercase">5vs5</span>
                        </div>
                        <ul class="space-y-1.5 mb-4 text-sm text-slate-600">
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Gazon synthétique Pro
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Éclairage LED 500 Lux
                            </li>
                        </ul>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase block">À partir de</span>
                                <span class="text-lg font-bold text-slate-900">300 Dh<span class="text-xs text-slate-400">/h</span></span>
                            </div>
                            <a href="{{ url('/booking') }}" class="px-4 py-2 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-brand-600 transition-colors">
                                Réserver
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Terrain 4 - Unavailable -->
                <article class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden opacity-60">
                    <div class="h-44 bg-slate-100 flex items-center justify-center relative">
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-slate-200 text-slate-500 text-xs font-bold">
                            Complet
                        </span>
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-base font-bold text-slate-500">Terrain N°4</h3>
                            <span class="text-xs font-bold text-slate-400 uppercase">5vs5</span>
                        </div>
                        <ul class="space-y-1.5 mb-4 text-sm text-slate-400">
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Gazon synthétique Pro
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Éclairage LED 500 Lux
                            </li>
                        </ul>
                        <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                            <div>
                                <span class="text-xs text-slate-400 font-bold uppercase block">À partir de</span>
                                <span class="text-lg font-bold text-slate-400">300 Dh<span class="text-xs text-slate-400">/h</span></span>
                            </div>
                            <button disabled class="px-4 py-2 bg-slate-200 text-slate-400 text-sm font-bold rounded-lg cursor-not-allowed">
                                Complet
                            </button>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how" class="py-20 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-50 text-brand-600 text-xs font-bold uppercase tracking-wide mb-3">
                    Simple & Rapide
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                    Comment ça marche ?
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                
                <!-- Step 1 -->
                <div class="relative">
                    <div class="w-14 h-14 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center text-xl font-bold mb-5">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Choisissez votre terrain</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Consultez les disponibilités en temps réel et sélectionnez le créneau qui vous convient.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="w-14 h-14 rounded-xl bg-brand-600 text-white flex items-center justify-center text-xl font-bold mb-5 shadow-lg shadow-brand-200">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Vérification rapide</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Upload de votre CNI pour validation. Processus sécurisé et rapide par nos administrateurs.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="w-14 h-14 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold mb-5">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Confirmation instantanée</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Recevez votre confirmation par email. Paiement sur place avant le match.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-neutral-900">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-white tracking-tight mb-4">
                Prêt à jouer ?
            </h2>
            <p class="text-base text-neutral-300 mb-8">
                Réservez votre terrain maintenant et rejoignez plus de 2,500 joueurs actifs.
            </p>
            <a href="{{ url('/booking') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-8 py-3 text-base font-bold text-white hover:bg-emerald-500 transition-colors shadow-lg shadow-emerald-600/25">
                Réserver maintenant
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </section>
@endsection
