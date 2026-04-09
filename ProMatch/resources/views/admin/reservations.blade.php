@extends('layouts.admin')

@section('title', 'ProMatch — Réservations')
@section('page-title', 'Réservations')
@section('page-subtitle', 'Gérez toutes les réservations de terrains')

@section('content')
    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 flex flex-wrap gap-4 items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-brand-500 w-64">
            </div>
            <select class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-brand-500 text-slate-600">
                <option>Tous les statuts</option>
                <option>Confirmé</option>
                <option>En attente</option>
                <option>Annulé</option>
            </select>
            <input type="date" class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-brand-500 text-slate-600">
        </div>
        <div class="flex items-center gap-2">
            <button class="p-2 text-slate-400 hover:text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </button>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Client</th>
                        <th class="px-6 py-4">Terrain</th>
                        <th class="px-6 py-4">Date & Heure</th>
                        <th class="px-6 py-4">Prix</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($reservations as $reservation)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-mono text-slate-500">#RES-{{ $reservation->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">
                                    {{ strtoupper(substr($reservation->first_name, 0, 1) . substr($reservation->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $reservation->first_name }} {{ $reservation->last_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $reservation->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                                {{ $reservation->field->name ?? 'Terrain' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $reservation->request_date }} {{ $reservation->start_time ? substr($reservation->start_time, 0, 5) : '' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $reservation->price ?? '300' }} DH
                        </td>
                        <td class="px-6 py-4">
                            @if($reservation->status === 'PENDING')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                En attente
                            </span>
                            @elseif($reservation->status === 'APPROVED' || $reservation->status === 'CONFIRMED')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Confirmé
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-medium border border-rose-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                {{ $reservation->status }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($reservation->status === 'PENDING')
                                <form method="POST" action="{{ url('/admin/reservations/'.$reservation->id.'/confirm') }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Confirmer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ url('/admin/reservations/'.$reservation->id.'/cancel') }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Annuler">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                                @else
                                <button class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-sm text-slate-500">Affichage de <span class="font-medium text-slate-900">1</span> à <span class="font-medium text-slate-900">10</span> sur <span class="font-medium text-slate-900">128</span> résultats</p>
            <div class="flex gap-2">
                {{-- TODO: wire up {{ $reservations->links() }} --}}
                <button class="px-3 py-1 text-sm border border-slate-200 rounded-lg text-slate-400 cursor-not-allowed" disabled>Précédent</button>
                <button class="px-3 py-1 text-sm border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50">Suivant</button>
            </div>
        </div>
    </div>
@endsection
