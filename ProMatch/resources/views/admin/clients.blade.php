@extends('layouts.admin')

@section('title', 'ProMatch — Clients')
@section('page-title', 'Clients')
@section('page-subtitle', 'Gestion de la base clients')

@section('content')
    <!-- Header override removed -->

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Clients</p>
            <p class="text-3xl font-bold text-slate-900">{{ $totalClients }}</p>
            <p class="text-xs text-brand-600 font-medium mt-1">Base totale</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Actifs (30j)</p>
            <p class="text-3xl font-bold text-slate-900">{{ $activeClients }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Utilisateurs récents</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">CNI Validée</p>
            <p class="text-3xl font-bold text-slate-900">{{ $validatedCniCount }}</p>
            <p class="text-xs text-emerald-600 font-medium mt-1">Identités vérifiées</p>
        </div>
        <div class="bg-rose-50 rounded-xl border border-rose-100 p-5">
            <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider mb-1">En attente CNI</p>
            <p class="text-3xl font-bold text-rose-700">{{ $pendingValidationsCount }}</p>
            <p class="text-xs text-rose-500 font-medium mt-1">À vérifier</p>
        </div>
    </div>

    <div x-data="{ 
        search: '', 
        status: '', 
        blockStatus: '',
        selectedClient: null,
        showDetailsModal: false,
        openDetails(client) {
            this.selectedClient = client;
            this.showDetailsModal = true;
        },
        closeDetails() {
            this.showDetailsModal = false;
            this.selectedClient = null;
        }
    }">
        <!-- Filters & Search -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm mt-6">
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
                <!-- Search -->
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="search" type="text" placeholder="Rechercher un client..." class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none">
                </div>
                <!-- Select -->
                <div class="flex gap-2">
                    <select x-model="blockStatus" class="py-2 px-3 block bg-white border border-slate-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 outline-none">
                        <option value="">Tous (Compte)</option>
                        <option value="BLOCKED">Bloqués</option>
                        <option value="UNBLOCKED">Actifs</option>
                    </select>
                    <select x-model="status" class="py-2 px-3 block bg-white border border-slate-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 outline-none">
                        <option value="">Tous (CNI)</option>
                        <option value="VALID">CNI Validée</option>
                        <option value="PENDING">En attente</option>
                        <option value="MISSING">Manquant</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm mt-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50">
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Client</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Contact</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Réservations</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">Dépensé</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500">CNI</th>
                            <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($clients as $client)
                        <tr class="hover:bg-slate-50/60 transition-colors"
                            x-data="{
                                name: '{{ strtolower(addslashes(($client->user->first_name ?? '') . ' ' . ($client->user->last_name ?? ''))) }}',
                                phone: '{{ $client->phone ?? '' }}',
                                cniStatus: '{{ $client->is_cni_valid ? 'VALID' : ($client->cni_image ? 'PENDING' : 'MISSING') }}',
                                isBlocked: {{ $client->user && $client->user->is_blocked ? 'true' : 'false' }},
                                isLoading: false,
                                toggleBlock() {
                                    if(this.isLoading) return;
                                    this.isLoading = true;
                                    let url = this.isBlocked ? '{{ url('/admin/clients') }}/{{ $client->id }}/unblock' : '{{ url('/admin/clients') }}/{{ $client->id }}/block';
                                    fetch(url, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            this.isBlocked = data.is_blocked;
                                        }
                                    })
                                    .finally(() => {
                                        this.isLoading = false;
                                    });
                                }
                            }"
                            x-show="(search === '' || name.includes(search.toLowerCase()) || phone.includes(search)) && 
                                    (status === '' || cniStatus === status) &&
                                    (blockStatus === '' || (blockStatus === 'BLOCKED' && isBlocked) || (blockStatus === 'UNBLOCKED' && !isBlocked))"
                        >
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($client->user->first_name ?? '?', 0, 1) . substr($client->user->last_name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $client->user->first_name ?? '' }} {{ $client->user->last_name ?? '' }}</span>
                                    <span x-cloak x-show="isBlocked" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-semibold border border-rose-100 ml-1">Bloqué</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-slate-600 text-xs">{{ $client->user->email ?? '' }}</p>
                                <p class="text-slate-400 text-xs mt-0.5">{{ $client->phone ?? 'N/A' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-slate-700 font-semibold">{{ $client->reservations_count ?? $client->reservations()->count() }}</span>
                                <span class="text-slate-400 text-xs"> séances</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-700">{{ number_format($client->reservations_sum_price ?? 0, 0) }} MAD</td>
                            <td class="px-5 py-4">
                                @if($client->is_cni_valid)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Validée
                                </span>
                                @elseif($client->cni_image)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    En attente
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-50 text-slate-500 text-xs font-semibold border border-slate-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    Manquant
                                </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" 
                                            @click="openDetails({
                                                id: {{ $client->id }},
                                                first_name: '{{ addslashes($client->user->first_name ?? '') }}',
                                                last_name: '{{ addslashes($client->user->last_name ?? '') }}',
                                                initials: '{{ strtoupper(substr($client->user->first_name ?? '?', 0, 1) . substr($client->user->last_name ?? '?', 0, 1)) }}',
                                                email: '{{ addslashes($client->user->email ?? '') }}',
                                                phone: '{{ addslashes($client->phone ?? 'N/A') }}',
                                                reservations_count: {{ $client->reservations_count ?? $client->reservations()->count() }},
                                                spent: '{{ number_format($client->reservations_sum_price ?? 0, 0) }}',
                                                is_cni_valid: {{ $client->is_cni_valid ? 'true' : 'false' }},
                                                cni_image: '{{ $client->cni_image ? asset('storage/' . $client->cni_image) : '' }}'
                                            })" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200 transition-colors shadow-sm">
                                        Voir
                                    </button>
                                    @if($client->user)
                                        <button type="button" 
                                                @click="toggleBlock()" 
                                                :disabled="isLoading"
                                                :class="isBlocked ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-emerald-200' : 'bg-rose-50 text-rose-600 hover:bg-rose-100 border-rose-200'"
                                                class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors shadow-sm disabled:opacity-50 min-w-[85px]">
                                            <span x-show="isLoading" class="mr-1">
                                                <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            </span>
                                            <span x-text="isBlocked ? 'Débloquer' : 'Bloquer'"></span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            <!-- Pagination -->
            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">Affichage de 1–10 sur {{ count($clients) }} clients</p>
                <div class="flex gap-1">
                    {{-- TODO: replace with {{ $clients->links() }} --}}
                    <button class="px-3 py-1.5 text-xs font-medium text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-50 disabled:opacity-50" disabled>Préc.</button>
                    <button class="px-3 py-1.5 text-xs font-bold text-white bg-brand-500 rounded-lg">1</button>
                    <button class="px-3 py-1.5 text-xs font-medium text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-50">2</button>
                    <button class="px-3 py-1.5 text-xs font-medium text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-50">Suiv.</button>
                </div>
            </div>
        </div>


    <!-- Modals (Hidden by default, triggered by JS) -->
    <!-- Preline Modal: Add Client -->
    <div id="hs-add-client-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div class="flex flex-col bg-white border shadow-sm rounded-2xl pointer-events-auto">
                <form method="POST" action="{{ url('/admin/clients') }}">
                    @csrf
                    <div class="flex justify-between items-center py-3 px-4 border-b">
                        <h3 class="font-bold text-slate-900">Ajouter un client</h3>
                        <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-slate-800 hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-add-client-modal">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Prénom</label>
                                <input type="text" name="first_name" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nom</label>
                                <input type="text" name="last_name" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                            <input type="email" name="email" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Téléphone</label>
                            <input type="tel" name="phone" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none">
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t">
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50" data-hs-overlay="#hs-add-client-modal">Annuler</button>
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-500 text-white hover:bg-brand-600">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine Modal: Client Details -->
    <div x-show="showDetailsModal" 
         class="fixed inset-0 z-[80] overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="bg-white border border-slate-200 shadow-xl rounded-2xl w-full max-w-lg overflow-hidden transform"
             @click.outside="closeDetails()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="flex justify-between items-center py-3 px-4 border-b">
                <h3 class="font-bold text-slate-900">Détails du client</h3>
                <button type="button" @click="closeDetails()" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-slate-800 hover:bg-slate-100">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            
            <div class="p-5 space-y-4">
                <template x-if="selectedClient">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-bold bg-brand-100 text-brand-700" x-text="selectedClient.initials"></div>
                            <div>
                                <p class="font-bold text-slate-900" x-text="selectedClient.first_name + ' ' + selectedClient.last_name"></p>
                                <p class="text-sm text-slate-500" x-text="selectedClient.email"></p>
                                <p class="text-sm text-slate-400" x-text="selectedClient.phone"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <p class="text-lg font-bold text-slate-900" x-text="selectedClient.reservations_count"></p>
                                <p class="text-xs text-slate-500 mt-0.5">Réservations</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <p class="text-lg font-bold text-slate-900"><span x-text="selectedClient.spent"></span> MAD</p>
                                <p class="text-xs text-slate-500 mt-0.5">Dépensé</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <span x-show="selectedClient.is_cni_valid" class="inline-flex items-center justify-center text-emerald-600 font-bold text-lg">✓</span>
                                <span x-show="!selectedClient.is_cni_valid && selectedClient.cni_image" class="inline-flex items-center justify-center text-amber-600 font-bold text-lg">⚡</span>
                                <span x-show="!selectedClient.is_cni_valid && !selectedClient.cni_image" class="inline-flex items-center justify-center text-slate-400 font-bold text-lg">✗</span>
                                <p class="text-xs text-slate-500 mt-0.5">CNI</p>
                            </div>
                        </div>

                        <!-- CNI Document Preview if present -->
                        <div x-show="selectedClient.cni_image" class="mt-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Document CNI</p>
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center p-2">
                                <img :src="selectedClient.cni_image" alt="CNI Client" class="max-h-48 object-contain rounded-lg">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t" x-show="selectedClient">
                <button type="button" @click="closeDetails()" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50">Fermer</button>
                
                <!-- Dynamic form for CNI validation -->
                <template x-if="selectedClient && !selectedClient.is_cni_valid && selectedClient.cni_image">
                    <form method="POST" :action="`{{ url('/admin/clients') }}/${selectedClient.id}/validate-cni`" class="inline-block">
                        @csrf
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-500 text-white hover:bg-brand-600">
                            Valider CNI
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Preline JS for models -->
    <script src="https://unpkg.com/preline/dist/preline.js"></script>
    <script>
        // Init preline if dynamically added
        window.HSStaticMethods.autoInit();
    </script>
@endpush
