@extends('layouts.app')

@section('content')
    <!-- HERO SECTION -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <!-- Background Asset (Kept identical) -->
        <div class="absolute inset-0 hero-bg"></div>

        <!-- Modern Overlays (Refined for clarity) -->
        <div class="absolute inset-0 bg-slate-900/40"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/50 to-transparent"></div>

        <!-- Content Container -->
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 w-full pt-20">
            <div class="max-w-3xl">
                <!-- Headline (NO Text Changes) -->
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tighter leading-[0.9] mb-8 animate-fade-in-up">
                    Votre terrain<br>
                    <span class="text-brand-400">vous attend.</span>
                </h1>

                <!-- Description (NO Text Changes) -->
                <p class="text-lg md:text-xl text-slate-200 leading-relaxed max-w-xl mb-12 opacity-90">
                    4 terrains de football avec gazon synthétique, éclairage LED et vestiaires. Réservation en ligne simple.
                </p>

                <!-- CTA Modern Container (Mimicking screenshot input bar style) -->
                <div class="inline-flex items-center p-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl md:rounded-full shadow-2xl shadow-slate-950/50">
                    <div class="hidden sm:flex items-center px-6 py-2 border-r border-white/10">
                        <x-lucide-map-pin class="w-5 h-5 text-brand-400 mr-3" />
                        <span class="text-sm font-bold text-white uppercase tracking-widest">Tangier</span>
                    </div>
                    
                    <!-- CTA Button (NO Text Changes) -->
                    <a href="{{ url('/booking') }}"
                        class="inline-flex items-center gap-3 rounded-xl md:rounded-full bg-brand-600 px-8 py-4 text-sm md:text-base font-black text-white hover:bg-brand-500 transition-all hover:scale-105 active:scale-95 shadow-xl shadow-brand-600/30">
                        Réserver maintenant
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                            <x-lucide-arrow-right class="w-4 h-4" />
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- Terrains Section -->
   <section id="terrains" class="py-20 bg-gradient-to-b from-slate-50 to-white">
    <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
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
            <article class="group relative bg-slate-900 rounded-[2.5rem] overflow-hidden hover:-translate-y-4 transition-all duration-700 flex flex-col shadow-2xl shadow-slate-950/40">
                <!-- Image Header -->
                <div class="relative h-60 overflow-hidden">
                    @if($field->image)
                        <img src="{{ asset('images/fields/' . $field->image) }}" 
                             alt="{{ $field->name }}" 
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000 opacity-90 group-hover:opacity-100">
                    @else
                        <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                            <x-lucide-layout class="w-12 h-12 text-slate-700" />
                        </div>
                    @endif
                    
                    <!-- Gradient Overlay for Image -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                    
                    <!-- Location Badge -->
                    <div class="absolute top-6 right-6 z-10">
                        <div class="px-4 py-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center gap-2 text-white shadow-xl">
                            <x-lucide-map-pin class="w-4 h-4 text-brand-400" />
                            <span class="text-[10px] font-black uppercase tracking-widest">Tangier</span>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6 -mt-12 bg-slate-800/95 backdrop-blur-sm relative z-20 rounded-t-[2.5rem] flex flex-col flex-1">
                    <!-- Title & Price Badge -->
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-xl font-black text-white leading-tight pr-4">
                            {{ $field->name }}
                        </h3>
                        <div class="px-4 py-2 bg-slate-700/80 rounded-2xl border border-white/10 flex items-center gap-1 shadow-lg">
                            <span class="text-lg font-black text-white">{{ $field->price_per_hour ?? 300 }}</span>
                            <span class="text-[10px] font-bold text-slate-400">DH</span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-sm text-slate-300 leading-relaxed mb-6 line-clamp-2">
                        Vivez une expérience de jeu exceptionnelle sur ce terrain de gazon synthétique premium avec éclairage professionnel.
                    </p>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-8">

                        <div class="px-3 py-1.5 bg-slate-700/50 rounded-2xl border border-white/5 text-[11px] font-bold text-white">
                            Gazon Pro
                        </div>
                        <div class="px-3 py-1.5 bg-slate-700/50 rounded-2xl border border-white/5 text-[11px] font-bold text-white">
                            Match 1h
                        </div>
                    </div>
                    
                    <!-- CTA Button -->
                    <div class="mt-auto">
                        <a href="{{ url('/booking') }}" class="w-full flex items-center justify-center py-4 bg-white text-slate-950 text-sm font-black rounded-3xl hover:bg-emerald-400 hover:text-white transition-all duration-500 shadow-xl shadow-white/5 active:scale-95 group/btn">
                            Réserver
                            <x-lucide-arrow-right class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform" />
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        </div>
    </div>
</section>

    <!-- How it Works -->
    <section id="how" class="py-20 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-12">

                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
                    Comment ça marche ?
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-12 relative">
                
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-10 left-[20%] right-[20%] h-px border-t-2 border-dashed border-slate-200 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-brand-100 text-brand-600 flex items-center justify-center shadow-2xl shadow-brand-100/20 mb-8 transform transition-transform hover:scale-110">
                        <x-lucide-search class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 tracking-tight">Choisissez votre terrain</h3>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-[250px]">
                        Consultez les disponibilités en temps réel et sélectionnez le créneau qui vous convient.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-brand-600 text-white flex items-center justify-center shadow-2xl shadow-brand-600/20 mb-8 transform transition-transform hover:scale-110">
                        <x-lucide-shield-check class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 tracking-tight">Vérification rapide</h3>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-[250px]">
                        Upload de votre CNI pour validation. Processus sécurisé et rapide par nos administrateurs.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-[2rem] bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-2xl shadow-emerald-100/20 mb-8 transform transition-transform hover:scale-110">
                        <x-lucide-check-circle class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 tracking-tight">Confirmation instantanée</h3>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-[250px]">
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
                <x-lucide-arrow-right class="w-5 h-5" />
            </a>
        </div>
    </section>
@endsection