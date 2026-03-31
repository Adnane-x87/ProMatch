@extends('layouts.admin')

@section('title', 'ProMatch — Dashboard Admin')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Aperçu de vos terrains aujourd\'hui')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-xl border border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-slate-500">Recettes aujourd'hui</p>
                {{-- TODO: wire up $revenueGrowth --}}
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">{{ $revenueGrowth ?? '+12%' }}</span>
            </div>
            {{-- TODO: wire up $todayRevenue --}}
            <p class="text-2xl font-bold text-slate-900">{{ number_format($todayRevenue ?? 1240, 0, ',', ' ') }} <span class="text-sm font-medium text-slate-400">MAD</span></p>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200">
            <p class="text-sm font-medium text-slate-500 mb-3">Réservations</p>
            {{-- TODO: wire up $todayReservationsCount and $totalCapacity --}}
            <p class="text-2xl font-bold text-slate-900">{{ $todayReservationsCount ?? 8 }} <span class="text-sm font-medium text-slate-400">/ {{ $totalCapacity ?? 12 }}</span></p>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200">
            <p class="text-sm font-medium text-slate-500 mb-3">Joueurs actifs</p>
            {{-- TODO: wire up $activePlayersCount --}}
            <p class="text-2xl font-bold text-slate-900">{{ $activePlayersCount ?? 42 }}</p>
        </div>

        <div class="bg-rose-50 p-5 rounded-xl border border-rose-100 cursor-pointer hover:bg-rose-100 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-rose-600">Validations CNI</p>
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
            </div>
            {{-- TODO: wire up $pendingValidationsCount --}}
            <p class="text-2xl font-bold text-rose-700">{{ $pendingValidationsCount ?? 2 }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Main Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Today's Schedule -->
            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900">Planning du jour</h2>
                    <span class="text-xs font-medium text-slate-400">16h - 22h</span>
                </div>
                <div class="p-5">
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-slate-100 -translate-y-1/2"></div>
                        <div class="grid grid-cols-4 gap-3 relative">
                            {{-- TODO: loop through $scheduleSlots --}}
                            
                            <div class="bg-brand-50 border border-brand-100 p-3 rounded-lg text-center">
                                <div class="w-2 h-2 rounded-full bg-brand-500 mx-auto mb-2"></div>
                                <p class="text-xs font-semibold text-brand-700">16:00</p>
                                <p class="text-xs text-slate-600 mt-1 truncate">Yassine M.</p>
                                <p class="text-[10px] text-brand-400 font-medium mt-0.5">T1</p>
                            </div>

                            <div class="bg-emerald-50 border border-emerald-100 p-3 rounded-lg text-center">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 mx-auto mb-2"></div>
                                <p class="text-xs font-semibold text-emerald-700">17:00</p>
                                <p class="text-xs text-slate-600 mt-1 truncate">Club Junior</p>
                                <p class="text-[10px] text-emerald-400 font-medium mt-0.5">T2</p>
                            </div>

                            <div class="bg-white border-2 border-dashed border-slate-200 p-3 rounded-lg text-center opacity-60">
                                <div class="w-2 h-2 rounded-full bg-slate-300 mx-auto mb-2"></div>
                                <p class="text-xs font-semibold text-slate-400">18:00</p>
                                <p class="text-[10px] text-slate-300 font-medium mt-2">LIBRE</p>
                            </div>

                            <div class="bg-amber-50 border border-amber-100 p-3 rounded-lg text-center">
                                <div class="w-2 h-2 rounded-full bg-amber-500 mx-auto mb-2"></div>
                                <p class="text-xs font-semibold text-amber-700">19:00</p>
                                <p class="text-xs text-slate-600 mt-1 truncate">Tournoi</p>
                                <p class="text-[10px] text-amber-400 font-medium mt-0.5">T1</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservations Table -->
            <div class="bg-white rounded-xl border border-slate-200">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900">Dernières réservations</h2>
                    <a href="{{ url('admin/reservations') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Voir tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase">
                            <tr>
                                <th class="px-5 py-3">Client</th>
                                <th class="px-5 py-3">Terrain</th>
                                <th class="px-5 py-3">Heure</th>
                                <th class="px-5 py-3">Statut</th>
                                <th class="px-5 py-3 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            {{-- TODO: loop through $recentReservations --}}
                            
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">YM</div>
                                        <span class="font-medium text-slate-900">Yassine M.</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-500">Terrain 1</td>
                                <td class="px-5 py-3 font-medium text-slate-900">18:00</td>
                                <td class="px-5 py-3"><span class="px-2 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium">En attente</span></td>
                                <td class="px-5 py-3 text-right">
                                    <button class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-xs font-semibold text-brand-600">OB</div>
                                        <span class="font-medium text-slate-900">Omar B.</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-500">Terrain 2</td>
                                <td class="px-5 py-3 font-medium text-slate-900">20:00</td>
                                <td class="px-5 py-3"><span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">Confirmé</span></td>
                                <td class="px-5 py-3 text-right">
                                    <button class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-xs font-semibold text-purple-600">KD</div>
                                        <span class="font-medium text-slate-900">Karim D.</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-500">Terrain 1</td>
                                <td class="px-5 py-3 font-medium text-slate-900">10:00</td>
                                <td class="px-5 py-3"><span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">Confirmé</span></td>
                                <td class="px-5 py-3 text-right">
                                    <button class="text-slate-400 hover:text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            
            <!-- CNI Tasks -->
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <h2 class="font-semibold text-slate-900 mb-4">Validations en attente</h2>
                
                <div class="space-y-3">
                    {{-- TODO: loop through $pendingValidations --}}
                    
                    <div class="p-4 border border-slate-200 rounded-lg">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">YM</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Yassine Moukrim</p>
                                <p class="text-xs text-slate-500">CNI en attente</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="flex-1 py-2 text-xs font-semibold text-white bg-slate-900 rounded-lg hover:bg-slate-800">Vérifier</button>
                            <button class="px-3 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">Ignorer</button>
                        </div>
                    </div>

                    <div class="p-4 border border-slate-200 rounded-lg">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">AH</div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Amine Hassani</p>
                                <p class="text-xs text-slate-500">CNI en attente</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="flex-1 py-2 text-xs font-semibold text-white bg-slate-900 rounded-lg hover:bg-slate-800">Vérifier</button>
                            <button class="px-3 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">Ignorer</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
