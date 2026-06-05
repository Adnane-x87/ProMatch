@extends('layouts.admin')

@section('title', 'ProMatch — Dashboard Admin')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Aperçu de vos terrains aujourd\'hui')

@section('content')
    <div x-data="dashboard()" x-init="init()">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-slate-500">Recettes aujourd'hui</p>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold" x-text="'+12%'"></span>
                </div>
                <p class="text-2xl font-bold text-slate-900">
                    <span x-text="formatNumber(stats.todays_income)">0</span>
                    <span class="text-sm font-medium text-slate-400">MAD</span>
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-sm font-medium text-slate-500 mb-3">Réservations actives</p>
                <p class="text-2xl font-bold text-slate-900">
                    <span x-text="stats.active_reservations || 0">0</span>
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <p class="text-sm font-medium text-slate-500 mb-3">Joueurs actifs</p>
                <p class="text-2xl font-bold text-slate-900" x-text="stats.active_users || 0">0</p>
            </div>

            <div class="bg-rose-50 p-5 rounded-xl border border-rose-100 cursor-pointer hover:bg-rose-100 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-rose-600">Validations CNI</p>
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" x-show="stats.pending_cnis > 0"></span>
                </div>
                <p class="text-2xl font-bold text-rose-700" x-text="stats.pending_cnis || 0">0</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mt-6">
            
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Daily Plan -->
                <div class="bg-white rounded-xl border border-slate-200">
                    <div class="px-5 py-4 border-b border-slate-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="font-semibold text-slate-900">Daily plan</h2>
                            <p class="text-xs text-slate-500">Reservations confirmees et statut d'arrivee</p>
                        </div>
                        <input x-model="selectedDate" @change="fetchPlanning()" type="date" class="w-full sm:w-auto px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-brand-500 text-slate-600">
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                            <div class="rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3">
                                <p class="text-xs font-medium text-emerald-700">Arrives</p>
                                <p class="mt-1 text-2xl font-bold text-emerald-800" x-text="planningCount('ARRIVED')">0</p>
                            </div>
                            <div class="rounded-lg border border-amber-100 bg-amber-50 px-4 py-3">
                                <p class="text-xs font-medium text-amber-700">Pas encore arrives</p>
                                <p class="mt-1 text-2xl font-bold text-amber-800" x-text="planningCount('APPROVED')">0</p>
                            </div>
                            <div class="rounded-lg border border-rose-100 bg-rose-50 px-4 py-3">
                                <p class="text-xs font-medium text-rose-700">Absents</p>
                                <p class="mt-1 text-2xl font-bold text-rose-800" x-text="planningCount('ABSENT')">0</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-xs font-medium text-slate-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-lg">Heure</th>
                                        <th class="px-4 py-3">Client</th>
                                        <th class="px-4 py-3">Terrain</th>
                                        <th class="px-4 py-3">Reservation</th>
                                        <th class="px-4 py-3 rounded-r-lg">Arrivee</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="slot in planning" :key="slot.id">
                                        <tr class="hover:bg-slate-50/70">
                                            <td class="px-4 py-3 font-semibold text-slate-900" x-text="formatPlanningTime(slot)"></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600" x-text="getInitials(slot.first_name, slot.last_name)"></div>
                                                    <div class="min-w-0">
                                                        <p class="font-medium text-slate-900 truncate" x-text="slot.first_name ? slot.first_name + ' ' + slot.last_name : 'Client'"></p>
                                                        <p class="text-xs text-slate-500 truncate" x-text="slot.phone || slot.email || ''"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-slate-600" x-text="slot.field ? slot.field.name : 'Terrain'"></td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700" x-text="statusLabel(slot.status)"></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span :class="attendanceClass(slot.status)" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold" x-text="attendanceLabel(slot.status)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="planning.length === 0">
                                        <td colspan="5" class="px-4 py-6 text-center text-slate-400">Aucun planning disponible pour cette date.</td>
                                    </tr>
                                </tbody>
                            </table>
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
                                    <th class="px-5 py-3">Date</th>
                                    <th class="px-5 py-3">Heure</th>
                                    <th class="px-5 py-3">Statut</th>
                                    <th class="px-5 py-3 text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="res in recentReservations" :key="res.id">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600" x-text="getInitials(res.first_name, res.last_name)"></div>
                                                <span class="font-medium text-slate-900" x-text="(res.first_name || '') + ' ' + (res.last_name || '')"></span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-slate-500" x-text="res.field ? res.field.name : 'N/A'"></td>
                                        <td class="px-5 py-3 text-slate-500" x-text="formatDate(res.request_date)"></td>
                                        <td class="px-5 py-3 font-medium text-slate-900" x-text="formatTime(res.start_time)"></td>
                                        <td class="px-5 py-3">
                                            <span :class="{
                                                'bg-amber-50 text-amber-700': res.status === 'PENDING',
                                                'bg-emerald-50 text-emerald-700': res.status === 'APPROVED',
                                                'bg-blue-50 text-blue-700': res.status === 'ARRIVED',
                                                'bg-rose-50 text-rose-700': res.status === 'REJECTED' || res.status === 'CANCELED' || res.status === 'ABSENT'
                                            }" class="px-2 py-1 rounded-full text-xs font-medium" x-text="statusLabel(res.status)"></span>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="{{ url('admin/reservations') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Voir</a>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="recentReservations.length === 0">
                                    <td colspan="6" class="px-5 py-6 text-center text-slate-400">Aucune reservation recente.</td>
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
                        <template x-for="task in pendingTasks" :key="task.id">
                            <div class="p-4 border border-slate-200 rounded-lg">
                                <div class="flex items-start gap-3 mb-3">
                                    <a :href="task.cni_image_url" target="_blank" class="w-16 h-16 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0">
                                        <img :src="task.cni_image_url" alt="CNI" class="w-full h-full object-cover">
                                    </a>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900" x-text="(task.first_name || '') + ' ' + (task.last_name || '')"></p>
                                        <p class="text-xs text-slate-500" x-text="task.field_name ? 'Terrain: ' + task.field_name : 'CNI en attente'"></p>
                                        <p class="text-xs text-slate-400 mt-1" x-text="'Reservation du ' + formatDate(task.request_date)"></p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a :href="task.cni_image_url" target="_blank" class="flex-1 py-2 text-center text-xs font-semibold text-white bg-slate-900 rounded-lg hover:bg-slate-800">Voir CNI</a>
                                    <a href="{{ url('admin/reservations') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">Reservations</a>
                                </div>
                            </div>
                        </template>
                        <div x-show="pendingTasks.length === 0" class="text-center py-4 text-slate-400 text-sm italic">
                            Aucune validation en attente.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function dashboard() {
        return {
            stats: @json($stats),
            recentReservations: @json($recentReservations),
            planning: [],
            selectedDate: '',
            pendingTasks: @json($pendingCniTasks),

            async init() {
                this.selectedDate = this.todayIsoLocal();
                await this.fetchStats();
                await this.fetchPlanning();
            },

            async fetchStats() {
                try {
                    const response = await fetch('/admin/api/stats', {
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }

                    const result = await response.json();
                    if (result.success) {
                        this.stats = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching stats:', error);
                }
            },

            async fetchPlanning() {
                try {
                    const response = await fetch(`/admin/api/planning?date=${this.selectedDate}`, {
                        headers: { 'Accept': 'application/json' }
                    });

                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }

                    const result = await response.json();
                    if (result.success) {
                        this.planning = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching planning:', error);
                }
            },

            formatNumber(num) {
                return new Intl.NumberFormat('fr-FR').format(num || 0);
            },

            formatTime(timeStr) {
                if (!timeStr) return '';
                // Check if it's a full datetime or just time
                if (timeStr.includes(' ')) {
                    return timeStr.split(' ')[1].substring(0, 5);
                }
                return timeStr.substring(0, 5);
            },

            formatPlanningTime(slot) {
                return this.formatTime(slot.start_time || slot.time_slot?.start_time || slot.request_date);
            },

            formatDate(dateStr) {
                if (!dateStr) return '';

                const date = new Date(dateStr);
                if (Number.isNaN(date.getTime())) {
                    return dateStr;
                }

                return date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            },

            getInitials(first, last) {
                return ((first ? first[0] : '') + (last ? last[0] : '')).toUpperCase();
            },

            planningCount(status) {
                return this.planning.filter((slot) => slot.status === status).length;
            },

            attendanceLabel(status) {
                const labels = {
                    ARRIVED: 'Arrive',
                    APPROVED: 'Pas encore arrive',
                    ABSENT: 'Absent',
                };

                return labels[status] || 'Non defini';
            },

            attendanceClass(status) {
                const classes = {
                    ARRIVED: 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                    APPROVED: 'bg-amber-50 text-amber-700 border border-amber-100',
                    ABSENT: 'bg-rose-50 text-rose-700 border border-rose-100',
                };

                return classes[status] || 'bg-slate-50 text-slate-600 border border-slate-100';
            },

            statusLabel(status) {
                const labels = {
                    PENDING: 'En attente',
                    APPROVED: 'Confirme',
                    REJECTED: 'Rejetee',
                    CANCELED: 'Annulee',
                    ARRIVED: 'Present',
                    ABSENT: 'Absent',
                };

                return labels[status] || status || '';
            },

            todayIsoLocal() {
                const now = new Date();
                const offset = now.getTimezoneOffset();
                const localDate = new Date(now.getTime() - (offset * 60 * 1000));

                return localDate.toISOString().split('T')[0];
            },

            todayDate() {
                const now = new Date();
                return now.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short' });
            }
        }
    }
</script>
@endpush
