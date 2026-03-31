@extends('layouts.admin')

@section('title', 'ProMatch — Clients')
@section('page-title', 'Clients')
@section('page-subtitle', 'Gestion de la base clients')

@section('content')
    <!-- Header override for the add button -->
    @push('styles')
        <style>
            /* We can use the layout's header, but since we need an Add button we might need to inject it. 
               For now, we embed the button in the content area if we can't override the layout header directly easily.
               Or we could redefine the header in the layout using a yield. Let's add it at the top of the content. */
        </style>
    @endpush

    <div class="flex justify-end mb-6 -mt-10 lg:-mt-16 relative z-40 pr-6">
        <!-- Preline Trigger for Add Modal -->
        <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-500 text-white text-sm font-semibold rounded-lg hover:bg-brand-600 transition-colors" data-hs-overlay="#hs-add-client-modal">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un client
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Clients</p>
            {{-- TODO: wire up $totalClients --}}
            <p class="text-3xl font-bold text-slate-900">{{ $totalClients ?? 142 }}</p>
            <p class="text-xs text-brand-600 font-medium mt-1">↑ +8 ce mois</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Actifs (30j)</p>
            {{-- TODO: wire up $activeClients --}}
            <p class="text-3xl font-bold text-slate-900">{{ $activeClients ?? 58 }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">40.9% du total</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">CNI Validée</p>
            {{-- TODO: wire up $validatedCniCount --}}
            <p class="text-3xl font-bold text-slate-900">{{ $validatedCniCount ?? 120 }}</p>
            <p class="text-xs text-emerald-600 font-medium mt-1">84.5% validés</p>
        </div>
        <div class="bg-rose-50 rounded-xl border border-rose-100 p-5">
            <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider mb-1">En attente CNI</p>
            {{-- TODO: wire up $pendingValidationsCount --}}
            <p class="text-3xl font-bold text-rose-700">{{ $pendingValidationsCount ?? 22 }}</p>
            <p class="text-xs text-rose-500 font-medium mt-1">À valider</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm mt-6">
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <!-- Search -->
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Rechercher un client..." class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none">
            </div>
            <!-- Select -->
            <div class="flex gap-2">
                <select class="py-2 px-3 block bg-white border border-slate-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 outline-none">
                    <option selected>Tous les clients</option>
                    <option>CNI Validée</option>
                    <option>En attente</option>
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
                    {{-- TODO: loop through $clients --}}
                    
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center text-xs font-bold">YM</div>
                                <span class="font-semibold text-slate-800">Yassine Moussaoui</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-slate-600 text-xs">yassine.m@gmail.com</p>
                            <p class="text-slate-400 text-xs mt-0.5">+212 6 12 34 56 78</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-slate-700 font-semibold">12</span>
                            <span class="text-slate-400 text-xs"> séances</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-700">3,600 MAD</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Validée
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button type="button" class="text-xs text-brand-600 hover:text-brand-700 font-semibold" data-hs-overlay="#hs-client-details-modal">Voir</button>
                        </td>
                    </tr>
                    
                    <!-- Add other dynamic rows here based on $clients data... -->
                    <!-- Hardcoded for example -->
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-bold">OB</div>
                                <span class="font-semibold text-slate-800">Omar Benali</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-slate-600 text-xs">omar.b@outlook.com</p>
                            <p class="text-slate-400 text-xs mt-0.5">+212 6 98 76 54 32</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-slate-700 font-semibold">8</span>
                            <span class="text-slate-400 text-xs"> séances</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-700">2,400 MAD</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                En attente
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button type="button" class="text-xs text-brand-600 hover:text-brand-700 font-semibold" data-hs-overlay="#hs-client-details-modal">Voir</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-500">Affichage de 1–10 sur 142 clients</p>
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

    <!-- Preline Modal: Client Details -->
    <div id="hs-client-details-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div class="flex flex-col bg-white border shadow-sm rounded-2xl pointer-events-auto">
                <div class="flex justify-between items-center py-3 px-4 border-b">
                    <h3 class="font-bold text-slate-900">Détails du client</h3>
                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-slate-800 hover:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-client-details-modal">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto space-y-4">
                    <!-- Static Preview Content -->
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-bold bg-brand-100 text-brand-700">YM</div>
                        <div>
                            <p class="font-bold text-slate-900">Yassine Moussaoui</p>
                            <p class="text-sm text-slate-500">yassine.m@gmail.com</p>
                            <p class="text-sm text-slate-400">+212 6 12 34 56 78</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 pt-2">
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-bold text-slate-900">12</p>
                            <p class="text-xs text-slate-500 mt-0.5">Réservations</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-bold text-slate-900">3,600</p>
                            <p class="text-xs text-slate-500 mt-0.5">MAD dépensé</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-bold text-emerald-600">✓</p>
                            <p class="text-xs text-slate-500 mt-0.5">CNI</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t">
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50" data-hs-overlay="#hs-client-details-modal">Fermer</button>
                    <!-- Update form for CNI validation if needed -->
                    <form method="POST" action="{{ url('/admin/clients/1/validate-cni') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-500 text-white hover:bg-brand-600">Valider CNI</button>
                    </form>
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
