@extends('layouts.admin')

@section('title', 'ProMatch - Validations CNI')
@section('page-title', 'Validations CNI')
@section('page-subtitle', 'Verifiez les derniers documents envoyes avec les reservations')

@section('content')
    <div class="flex justify-end mb-6 -mt-10 lg:-mt-16 relative z-40 pr-6">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-sm font-medium border border-rose-100">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                {{ $pendingValidationsCount ?? 0 }} demandes en attente
            </span>
        </div>
    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($pendingValidations as $validation)
            @php
                $user = optional($validation->tenant)->user;
                $firstName = $user->first_name ?? $validation->first_name;
                $lastName = $user->last_name ?? $validation->last_name;
                $initials = strtoupper(substr($firstName ?: '?', 0, 1) . substr($lastName ?: '?', 0, 1));
                $cniUrl = $validation->cni_image ? asset('storage/' . $validation->cni_image) : null;
            @endphp

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-900 truncate">{{ trim(($firstName ?? '') . ' ' . ($lastName ?? '')) }}</h3>
                                <p class="text-xs text-slate-500 truncate">{{ $validation->email }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Reservation du {{ \Illuminate\Support\Carbon::parse($validation->request_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100">En attente</span>
                    </div>
                </div>

                <div class="p-5 bg-slate-50/50 space-y-4">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $validation->field?->name ?? 'Terrain non renseigne' }}</span>
                        <span>{{ $validation->start_time ? \Illuminate\Support\Carbon::parse($validation->start_time)->format('H:i') : '-' }}</span>
                    </div>

                    <div class="relative group aspect-video bg-slate-200 rounded-lg overflow-hidden border border-slate-200">
                        @if($cniUrl)
                            <img src="{{ $cniUrl }}" alt="CNI" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-sm font-medium text-slate-500 bg-slate-100">
                                CNI introuvable
                            </div>
                        @endif

                        @if($cniUrl)
                            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="{{ $cniUrl }}" target="_blank" class="px-4 py-2 bg-white rounded-lg text-sm font-semibold text-slate-900 hover:bg-slate-50">Agrandir</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 flex gap-3">
                    <form method="POST" action="{{ url('/admin/validations/' . $validation->id . '/approve') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-lg hover:bg-brand-600 transition-colors">
                            Valider
                        </button>
                    </form>
                    <form method="POST" action="{{ url('/admin/validations/' . $validation->id . '/reject') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            Refuser
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-xl border border-slate-200">
                <p class="text-slate-500">Aucune demande de validation en attente.</p>
            </div>
        @endforelse
    </div>
@endsection
