@extends('layouts.employee')

@section('title', 'ProMatch — Espace Employé')
@section('page-title', 'Espace Employé')
@section('page-subtitle', 'Gérer les présences et le planning d\'aujourd\'hui')

@section('content')
    <div x-data="employeeDashboard()">
        <!-- Summary Counters -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Total Reservations -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm shadow-slate-100 flex items-center gap-4 hover-lift">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Réservations Aujourd'hui</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1" x-text="stats.total">0</p>
                </div>
            </div>

            <!-- Arrived Counter -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm shadow-slate-100 flex items-center gap-4 hover-lift">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Clients Présents</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1 text-emerald-600" x-text="stats.arrived">0</p>
                </div>
            </div>

            <!-- Absent Counter -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm shadow-slate-100 flex items-center gap-4 hover-lift">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Clients Absents</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1 text-rose-600" x-text="stats.absent">0</p>
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm shadow-slate-100 mt-8 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Planning du jour</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Vérifier l'arrivée et enregistrer la présence des clients</p>
                </div>
                <span class="text-xs font-semibold text-brand-700 bg-brand-50 px-3 py-1.5 rounded-full" x-text="todayDate()"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Terrain</th>
                            <th class="px-6 py-4">Créneau Horaire</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="res in reservations" :key="res.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Client -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 flex-shrink-0"
                                            x-text="getInitials(res.first_name, res.last_name)"></div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-950 truncate" x-text="(res.first_name || '') + ' ' + (res.last_name || '')"></p>
                                            <p class="text-xs text-slate-400 truncate mt-0.5" x-text="res.email"></p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Field -->
                                <td class="px-6 py-4 font-semibold text-slate-700" x-text="res.field ? res.field.name : 'N/A'"></td>

                                <!-- Time Slot -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-bold text-slate-900 bg-slate-100/80 px-2.5 py-1 rounded-md text-xs" x-text="formatTimeSlot(res)"></span>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4">
                                    <div class="inline-block">
                                        <!-- Dynamic Badge based on status -->
                                        <span x-show="res.status === 'PENDING'" class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/50 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            En attente
                                        </span>
                                        <span x-show="res.status === 'APPROVED'" class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/50 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Confirmé
                                        </span>
                                        <span x-show="res.status === 'REJECTED'" class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/50 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Rejeté
                                        </span>
                                        <span x-show="res.status === 'CANCELED'" class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/50 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Annulé
                                        </span>
                                        <span x-show="res.status === 'ARRIVED'" class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/50 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Présent
                                        </span>
                                        <span x-show="res.status === 'ABSENT'" class="px-2.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/50 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Absent
                                        </span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center">
                                    <!-- Display action buttons for APPROVED status -->
                                    <div x-show="res.status === 'APPROVED'" class="flex items-center justify-center gap-2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                        <button @click="markArrived(res.id)" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm shadow-emerald-600/10 hover-lift cursor-pointer transition-colors">
                                            Présent
                                        </button>
                                        <button @click="markAbsent(res.id)" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-sm shadow-rose-600/10 hover-lift cursor-pointer transition-colors">
                                            Absent
                                        </button>
                                    </div>
                                    <!-- Placeholder text when not approved -->
                                    <div x-show="res.status !== 'APPROVED'" class="text-xs text-slate-400 font-medium italic">
                                        Aucune action requise
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="reservations.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                Aucun planning ni réservation disponible pour aujourd'hui.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function employeeDashboard() {
        return {
            stats: @json($stats),
            reservations: @json($reservations),

            async markArrived(id) {
                try {
                    const response = await fetch(`/employee/reservations/${id}/arrive`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.stats = result.stats;
                        const res = this.reservations.find(r => r.id === id);
                        if (res) {
                            res.status = 'ARRIVED';
                        }
                    }
                } catch (error) {
                    console.error('Error marking client as arrived:', error);
                }
            },

            async markAbsent(id) {
                try {
                    const response = await fetch(`/employee/reservations/${id}/absent`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.stats = result.stats;
                        const res = this.reservations.find(r => r.id === id);
                        if (res) {
                            res.status = 'ABSENT';
                        }
                    }
                } catch (error) {
                    console.error('Error marking client as absent:', error);
                }
            },

            formatTime(timeStr) {
                if (!timeStr) return '';
                if (timeStr.includes(' ')) {
                    return timeStr.split(' ')[1].substring(0, 5);
                }
                return timeStr.substring(0, 5);
            },

            formatTimeSlot(res) {
                let start = '';
                let end = '';
                if (res.start_time) {
                    start = this.formatTime(res.start_time);
                } else if (res.time_slot) {
                    start = this.formatTime(res.time_slot.start_time);
                }
                if (res.end_time) {
                    end = this.formatTime(res.end_time);
                } else if (res.time_slot) {
                    end = this.formatTime(res.time_slot.end_time);
                }
                return start && end ? `${start} → ${end}` : 'N/A';
            },

            getInitials(first, last) {
                return ((first ? first[0] : '') + (last ? last[0] : '')).toUpperCase();
            },

            todayDate() {
                const now = new Date();
                return now.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
        };
    }
</script>
@endpush
