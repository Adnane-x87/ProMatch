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
                    4 terrains de football avec gazon synthétique, éclairage LED et vestiaires. Réservation en ligne simple.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/booking') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-500 transition-colors shadow-lg shadow-brand-600/25">
                        Réserver maintenant
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Terrains Section -->
   <section id="terrains" class="py-20 bg-gradient-to-b from-slate-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold uppercase tracking-wider mb-4 border border-emerald-100">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Nos Installations
            </span>
            <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                Des terrains professionnels
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                Gazon synthétique dernière génération, éclairage LED homologué et vestiaires premium.
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">

            @foreach($fields as $index => $field)
            <!-- Terrain Card -->
            <article class="group relative bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-emerald-100/50 hover:-translate-y-2 transition-all duration-500 flex flex-col">
                <!-- Image/Visual Header -->
                <div class="relative h-52 bg-gradient-to-br from-emerald-50 via-slate-50 to-emerald-100/30 flex flex-col items-center justify-center p-6 overflow-hidden">
                    <!-- Decorative Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <pattern id="grid-{{ $index }}" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5" class="text-emerald-600"/>
                            </pattern>
                            <rect width="100" height="100" fill="url(#grid-{{ $index }})"/>
                        </svg>
                    </div>
                    
                    <!-- Availability Badge -->
                    <div class="absolute top-4 left-4 z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500 text-white text-[11px] font-bold tracking-wide shadow-lg shadow-emerald-500/25">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Disponible
                        </span>
                    </div>
                    
                    <!-- PRO Badge -->
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-900/90 text-white text-[10px] font-black uppercase tracking-widest backdrop-blur-sm">
                            PRO
                        </span>
                    </div>
                    
                    <!-- Icon -->
                    <div class="relative z-10 transform group-hover:scale-110 transition-transform duration-500">
                        <div class="w-20 h-20 rounded-2xl bg-white shadow-xl shadow-emerald-200/50 flex items-center justify-center border border-emerald-100">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6 flex flex-col flex-1">
                    <!-- Title -->
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-slate-900 leading-tight group-hover:text-emerald-700 transition-colors">
                            {{ $field->name }}
                        </h3>
                    </div>
                    
                    <!-- Features -->
                    <ul class="space-y-3 mb-6 flex-1">
                        <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                            <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span>Gazon synthétique Pro</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                            <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span>{{ $field->address ?? 'Tangier-castilla' }}</span>
                        </li>
                    </ul>
                    
                    <!-- Price & CTA -->
                    <div class="flex items-center justify-between pt-5 border-t border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mb-0.5">À partir de</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-slate-900">{{ $field->price_per_hour ?? 300 }}</span>
                                <span class="text-sm font-bold text-slate-500">Dh/h</span>
                            </div>
                        </div>
                        <a href="{{ url('/booking') }}" class="group/btn inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 transition-all duration-300 shadow-lg shadow-slate-900/20 hover:shadow-emerald-600/30">
                            Réserver
                            <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Hover Accent Line -->
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 group-hover:w-full transition-all duration-500"></div>
            </article>
            @endforeach

        </div>
    </div>
</section>

    <!-- How it Works -->
    <section id="how" class="py-20 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-12">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-brand-50 text-brand-600 text-xs font-bold uppercase tracking-wide mb-3">
                    Simple & Rapide
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                    Comment ça marche ?
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">

                <!-- Step 1 -->
                <div class="relative">
                    <div
                        class="w-14 h-14 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center text-xl font-bold mb-5">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Choisissez votre terrain</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Consultez les disponibilités en temps réel et sélectionnez le créneau qui vous convient.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div
                        class="w-14 h-14 rounded-xl bg-brand-600 text-white flex items-center justify-center text-xl font-bold mb-5 shadow-lg shadow-brand-200">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Vérification rapide</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Upload de votre CNI pour validation. Processus sécurisé et rapide par nos administrateurs.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div
                        class="w-14 h-14 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold mb-5">
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
            <a href="{{ url('/booking') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-8 py-3 text-base font-bold text-white hover:bg-emerald-500 transition-colors shadow-lg shadow-emerald-600/25">
                Réserver maintenant
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </section>
@endsection