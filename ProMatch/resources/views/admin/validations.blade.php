@extends('layouts.admin')

@section('title', 'ProMatch — Validations CNI')
@section('page-title', 'Validations CNI')
@section('page-subtitle', 'Vérifiez les identités des utilisateurs')

@section('content')
    <div class="flex justify-end mb-6 -mt-10 lg:-mt-16 relative z-40 pr-6">
        <div class="flex items-center gap-2">
            {{-- TODO: wire up $pendingValidationsCount --}}
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-sm font-medium border border-rose-100">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                {{ $pendingValidationsCount ?? 2 }} demandes en attente
            </span>
        </div>
    </div>

    <!-- Validation List -->
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        @forelse($pendingValidations as $validation)
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">
                            {{ strtoupper(substr($validation->user->first_name ?? '?', 0, 1) . substr($validation->user->last_name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">{{ $validation->user->first_name ?? '' }} {{ $validation->user->last_name ?? '' }}</h3>
                            <p class="text-xs text-slate-500">Inscrit le {{ $validation->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium border border-amber-100">En attente</span>
                </div>
            </div>
            <div class="p-5 bg-slate-50/50">
                <p class="text-xs font-medium text-slate-500 uppercase mb-3">Document fourni</p>
                <div class="relative group aspect-video bg-slate-200 rounded-lg overflow-hidden border border-slate-200">
                    @if($validation->cni_image)
                        <img src="{{ asset('storage/' . $validation->cni_image) }}" alt="CNI" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name=CNI+Missing&background=random&size=512" alt="CNI Recto" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <a href="{{ $validation->cni_image ? asset('storage/' . $validation->cni_image) : '#' }}" target="_blank" class="px-4 py-2 bg-white rounded-lg text-sm font-semibold text-slate-900 hover:bg-slate-50">Agrandir</a>
                    </div>
                </div>
            </div>
            <div class="p-5 border-t border-slate-100 flex gap-3">
                <form method="POST" action="{{ url('/admin/validations/'.$validation->id.'/approve') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-brand-500 text-white text-sm font-semibold rounded-lg hover:bg-brand-600 transition-colors">
                        Valider
                    </button>
                </form>
                <form method="POST" action="{{ url('/admin/validations/'.$validation->id.'/reject') }}" class="flex-1">
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
