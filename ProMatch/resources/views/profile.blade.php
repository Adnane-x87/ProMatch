@extends('layouts.app')

@section('content')
    <!-- Container with padding for navbar -->
    <div class="min-h-screen bg-slate-50 pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            
            <!-- Header title -->
            <div class="mb-10">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Mon Espace Client</h1>
                <p class="text-brand-600 font-bold tracking-widest text-[10px] uppercase">Gérer vos informations et vos réservations</p>
            </div>

            <!-- Session Alerts -->
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl mb-8 transition-all duration-300">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                        <x-lucide-check-circle class="w-4 h-4" />
                    </div>
                    <div class="text-sm font-semibold">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl mb-8 transition-all duration-300">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                        <x-lucide-x-circle class="w-4 h-4" />
                    </div>
                    <div class="text-sm font-semibold">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: User Profile Card (4 cols) -->
                <div class="lg:col-span-4 bg-white rounded-[2rem] shadow-xl p-8 border border-slate-200 flex flex-col items-center text-center">
                    
                    <!-- Avatar bubble -->
                    <div class="relative w-24 h-24 rounded-full bg-brand-600 text-white font-black text-3xl flex items-center justify-center shadow-lg shadow-brand-600/20 mb-6 overflow-hidden">
                        @if(!empty($user->avatar))
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="absolute inset-0 w-full h-full object-cover z-10">
                        @else
                            <span class="absolute inset-0 rounded-full bg-white/10"></span>
                            <span class="relative z-10">{{ strtoupper(substr($user->first_name ?? $user->email, 0, 1)) }}</span>
                        @endif
                    </div>

                    <h2 class="text-2xl font-black text-slate-900 leading-tight mb-1">
                        {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Mon compte' }}
                    </h2>
                    
                    <p class="text-sm font-semibold text-slate-400 mb-6">Client ProMatch</p>

                    <!-- User details list -->
                    <div class="w-full space-y-4 text-left border-t border-slate-100 pt-6">
                        
                        <!-- Email -->
                        <div class="flex items-start gap-3">
                            <span class="text-slate-400 mt-0.5">
                                <x-lucide-mail class="w-4 h-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Adresse email</p>
                                <p class="text-sm font-bold text-slate-700 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-3">
                            <span class="text-slate-400 mt-0.5">
                                <x-lucide-phone class="w-4 h-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Téléphone</p>
                                <p class="text-sm font-bold text-slate-700">{{ $user->phone ?: 'Non renseigné' }}</p>
                            </div>
                        </div>

                        <!-- CNI Identity status -->
                        <div class="flex items-start gap-3">
                            <span class="text-slate-400 mt-0.5">
                                <x-lucide-shield-check class="w-4 h-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Statut CNI</p>
                                
                                @if($user->tenant && $user->tenant->is_cni_valid)
                                    <div class="mt-1 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Vérifiée & validée
                                    </div>
                                @elseif($user->tenant && $user->tenant->cni_image)
                                    <div class="mt-1 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-700 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        En cours de vérification
                                    </div>
                                @else
                                    <div class="mt-1 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Non fournie (CNI requise)
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- New booking shortcut -->
                    <div class="w-full mt-8 pt-6 border-t border-slate-100">
                        <a href="{{ url('/booking') }}" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-slate-900 text-white text-[11px] font-black tracking-widest hover:bg-brand-600 transition-all shadow-md hover:shadow-lg active:scale-95 uppercase">
                            <x-lucide-plus-circle class="w-4 h-4" />
                            Réserver un match
                        </a>
                    </div>
                </div>

                <!-- Right Column: Reservations History (8 cols) -->
                <div class="lg:col-span-8 bg-white rounded-[2rem] shadow-xl p-8 border border-slate-200">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight mb-6 border-b border-slate-100 pb-4 flex items-center gap-2">
                        <x-lucide-calendar class="w-5 h-5 text-brand-600" />
                        Historique de mes réservations
                    </h3>

                    @if($reservations->isEmpty())
                        <!-- Empty Placeholder state -->
                        <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-slate-50 border border-dashed border-slate-200 text-slate-400 flex items-center justify-center mb-4">
                                <x-lucide-calendar-days class="w-8 h-8" />
                            </div>
                            <h4 class="text-base font-bold text-slate-800 mb-1">Aucune réservation trouvée</h4>
                            <p class="text-sm text-slate-400 max-w-sm leading-relaxed mb-6">Vous n'avez pas encore effectué de réservation sur ProMatch.</p>
                            
                            <a href="{{ url('/booking') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white text-xs font-bold rounded-xl hover:bg-brand-500 transition-all shadow-md shadow-brand-600/10 active:scale-95 uppercase">
                                Réserver maintenant
                                <x-lucide-arrow-right class="w-4 h-4" />
                            </a>
                        </div>
                    @else
                        <!-- List of reservations -->
                        <div class="space-y-6">
                            @foreach($reservations as $res)
                                <div class="group relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6 p-6 rounded-2xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition-all duration-300">
                                    
                                    <!-- Field Info & Date -->
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                            <x-lucide-layout class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <h4 class="text-base font-black text-slate-900 group-hover:text-brand-600 transition-colors">
                                                {{ $res->field->name ?? 'Terrain de Football' }}
                                            </h4>
                                            
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-400 font-semibold">
                                                <!-- Date display -->
                                                <span class="flex items-center gap-1">
                                                    <x-lucide-calendar class="w-3.5 h-3.5" />
                                                    {{ \Carbon\Carbon::parse($res->request_date)->translatedFormat('d F Y') }}
                                                </span>
                                                <!-- Time display -->
                                                @if($res->start_time)
                                                    <span class="flex items-center gap-1">
                                                        <x-lucide-clock class="w-3.5 h-3.5" />
                                                        {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price, Status and Actions Group -->
                                    <div class="w-full md:w-auto flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 border-t border-slate-100 md:border-t-0 pt-4 md:pt-0">
                                        
                                        <!-- Price -->
                                        <div class="text-right">
                                            <span class="text-lg font-black text-slate-900">{{ $res->price ?? '300' }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase">DH</span>
                                        </div>

                                        <!-- Status & Action -->
                                        <div class="flex items-center gap-3">
                                            
                                            <!-- Status badge -->
                                            @if($res->status === 'APPROVED')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Confirmée
                                                </span>
                                            @elseif($res->status === 'PENDING')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-amber-700 text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                    En attente
                                                </span>
                                            @elseif($res->status === 'REJECTED')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Refusée
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 border border-slate-100 text-slate-500 text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    Annulée
                                                </span>
                                            @endif

                                            <!-- Cancel button if applicable -->
                                            @if(in_array($res->status, ['PENDING', 'APPROVED']))
                                                <button onclick="confirmCancellation({{ $res->id }})"
                                                    class="p-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none focus:ring-4 focus:ring-rose-100 shadow-sm"
                                                    title="Annuler cette réservation">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                </button>
                                                
                                                <!-- Cancellation hidden form -->
                                                <form id="cancel-form-{{ $res->id }}" action="{{ route('profile.reservations.cancel', $res->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                            @endif

                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="cancelModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[2rem] p-8 max-w-sm w-full mx-4 text-center shadow-2xl transform scale-95 transition-transform duration-300" id="cancelModalContent">
            
            <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-6 shadow-inner shadow-rose-100">
                <x-lucide-alert-triangle class="w-10 h-10" />
            </div>
            
            <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Annuler la réservation ?</h2>
            <p class="text-sm text-slate-500 leading-relaxed mb-8">
                Êtes-vous sûr de vouloir annuler cette réservation ? Cette action est irréversible et le terrain sera libéré.
            </p>
            
            <div class="flex items-center gap-3">
                <button onclick="closeModal()" type="button" class="flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors focus:ring-4 focus:ring-slate-100 active:scale-95">
                    Non, garder
                </button>
                <button onclick="submitCancellation()" type="button" class="flex-1 py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl shadow-lg transition-all active:scale-95">
                    Oui, annuler
                </button>
            </div>
            
        </div>
    </div>

    <!-- Confirmation Modal JS -->
    <script>
        let currentCancelId = null;
        const modal = document.getElementById('cancelModal');
        const modalContent = document.getElementById('cancelModalContent');

        function confirmCancellation(id) {
            currentCancelId = id;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger animations
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                currentCancelId = null;
            }, 300);
        }

        function submitCancellation() {
            if (currentCancelId) {
                document.getElementById('cancel-form-' + currentCancelId).submit();
            }
        }
    </script>
@endsection
