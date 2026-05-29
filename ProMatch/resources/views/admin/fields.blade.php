@extends('layouts.admin')

@section('title', 'ProMatch — Gestion des Terrains')
@section('page-title', 'Gestion des Terrains')
@section('page-subtitle', 'Créez, modifiez et supprimez vos terrains de sport')

@section('content')
<div x-data="{ 
    search: '', 
    showAddModal: false,
    showEditModal: false,
    showDeleteModal: false,
    deleteFieldId: null,
    deleteFieldName: '',
    editField: { id: null, name: '', address: '', price_per_hour: 0, description: '', image_url: '' },
    openEdit(field) {
        this.editField = { 
            id: field.id, 
            name: field.name, 
            address: field.address, 
            price_per_hour: field.price_per_hour, 
            description: field.description || '',
            image_url: field.image_url || ''
        };
        this.showEditModal = true;
    },
    confirmDelete(id, name) {
        this.deleteFieldId = id;
        this.deleteFieldName = name;
        this.showDeleteModal = true;
    }
}">

    <!-- Success Alert -->
    @if(session('success'))
    <div class="p-4 mb-6 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="p-4 mb-6 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-100 space-y-1">
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="font-bold">Veuillez corriger les erreurs suivantes :</span>
        </div>
        <ul class="list-disc list-inside pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Action Bar -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-4 items-stretch sm:items-center justify-between">
        <!-- Search -->
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input x-model="search" type="search" placeholder="Rechercher un terrain par nom ou adresse..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all">
        </div>
        
        <!-- Add button -->
        <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-brand-500 hover:bg-brand-600 text-white transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter un terrain
        </button>
    </div>

    <!-- Terrains Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach($fields as $field)
        <div x-data="{ name: @js(strtolower($field->name)), address: @js(strtolower($field->address)) }"
             x-show="search === '' || name.includes(search.toLowerCase()) || address.includes(search.toLowerCase())"
             class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col group">
            
            <!-- Image Area -->
            <div class="h-48 relative overflow-hidden bg-slate-100">
                @if($field->image)
                    {{-- Seeded images: stored as bare filename (e.g. field1.jpg) in public/images/fields/ --}}
                    {{-- Uploaded images: stored as fields/filename.jpg in Laravel storage disk --}}
                    @php
                        $imgUrl = str_contains($field->image, '/') 
                            ? asset('storage/' . $field->image) 
                            : asset('images/fields/' . $field->image);
                    @endphp
                    <img src="{{ $imgUrl }}" alt="{{ $field->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider">Aucune image</span>
                    </div>
                @endif
                <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-xs text-white text-xs font-bold shadow-sm">
                    {{ number_format($field->price_per_hour, 0) }} MAD / h
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div class="space-y-2">
                    <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $field->name }}</h3>
                    <p class="text-slate-500 text-xs flex items-start gap-1.5 leading-snug">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $field->address }}</span>
                    </p>
                    @if($field->description)
                        <p class="text-slate-600 text-xs line-clamp-3 pt-1.5 border-t border-slate-100">{{ $field->description }}</p>
                    @else
                        <p class="text-slate-400 text-xs italic pt-1.5 border-t border-slate-100">Aucune description disponible.</p>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-2.5 mt-5 pt-4 border-t border-slate-100">
                    <button type="button" 
                            @click="openEdit(@js([
                                'id' => $field->id,
                                'name' => $field->name,
                                'address' => $field->address,
                                'price_per_hour' => $field->price_per_hour,
                                'description' => $field->description,
                                'image' => $field->image,
                                'image_url' => $field->image ? (str_contains($field->image, '/') ? asset('storage/' . $field->image) : asset('images/fields/' . $field->image)) : '',
                            ]))" 
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Modifier
                    </button>
                    
                    <button type="button" 
                            @click="confirmDelete({{ $field->id }}, @js($field->name))"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($fields->isEmpty())
        <div class="col-span-full bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <p class="text-sm font-semibold uppercase tracking-wider mb-1">Aucun terrain enregistré</p>
            <p class="text-xs text-slate-500 mb-4">Commencez par ajouter votre premier terrain pour que vos clients puissent réserver.</p>
            <button @click="showAddModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-brand-500 hover:bg-brand-600 text-white transition-colors shadow-sm">Add Terrain</button>
        </div>
        @endif
    </div>

    <!-- Alpine Modal: Add Field -->
    <div x-show="showAddModal" 
         class="fixed inset-0 z-[80] overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div class="bg-white border border-slate-200 shadow-xl rounded-2xl w-full max-w-lg overflow-hidden transform"
             @click.outside="showAddModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="flex justify-between items-center py-3.5 px-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">Ajouter un terrain</h3>
                <button type="button" @click="showAddModal = false" class="flex justify-center items-center size-8 rounded-full border border-transparent text-slate-800 hover:bg-slate-100 transition-colors">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.fields.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="p-5 space-y-4 overflow-y-auto max-h-[70vh]">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nom du terrain</label>
                        <input type="text" name="name" placeholder="Ex: Terrain Synthétique A" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Adresse</label>
                        <input type="text" name="address" placeholder="Ex: Avenue Mohammed V, Tanger" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Prix par heure (MAD)</label>
                        <input type="number" name="price_per_hour" placeholder="Ex: 250" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all" required min="0">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                        <textarea name="description" rows="3" placeholder="Ex: Terrain 5x5 avec éclairage LED nocturne..." class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Image du terrain</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-250 transition-all cursor-pointer">
                    </div>
                </div>
                
                <div class="flex justify-end items-center gap-x-2 py-3.5 px-5 border-t border-slate-100">
                    <button type="button" @click="showAddModal = false" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 transition-colors">Annuler</button>
                    <button type="submit" class="py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-500 text-white hover:bg-brand-600 transition-colors">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine Modal: Edit Field -->
    <div x-show="showEditModal" 
         class="fixed inset-0 z-[80] overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div class="bg-white border border-slate-200 shadow-xl rounded-2xl w-full max-w-lg overflow-hidden transform"
             @click.outside="showEditModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="flex justify-between items-center py-3.5 px-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">Modifier le terrain</h3>
                <button type="button" @click="showEditModal = false" class="flex justify-center items-center size-8 rounded-full border border-transparent text-slate-800 hover:bg-slate-100 transition-colors">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            
            <form method="POST" :action="'/admin/fields/' + editField.id" enctype="multipart/form-data">
                @csrf
                <div class="p-5 space-y-4 overflow-y-auto max-h-[70vh]">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nom du terrain</label>
                        <input type="text" name="name" x-model="editField.name" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Adresse</label>
                        <input type="text" name="address" x-model="editField.address" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Prix par heure (MAD)</label>
                        <input type="number" name="price_per_hour" x-model="editField.price_per_hour" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all" required min="0">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                        <textarea name="description" rows="3" x-model="editField.description" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none transition-all resize-none"></textarea>
                    </div>

                    <!-- Current Image Preview -->
                    <template x-if="editField.image_url">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-500">Image actuelle</label>
                            <div class="h-28 w-44 rounded-lg overflow-hidden border border-slate-200 bg-slate-55">
                                <img :src="editField.image_url" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </template>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Remplacer l'image (optionnel)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-250 transition-all cursor-pointer">
                    </div>
                </div>
                
                <div class="flex justify-end items-center gap-x-2 py-3.5 px-5 border-t border-slate-100">
                    <button type="button" @click="showEditModal = false" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 transition-colors">Annuler</button>
                    <button type="submit" class="py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-brand-500 text-white hover:bg-brand-600 transition-colors">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine Modal: Confirm Delete Field -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-[80] overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <div class="bg-white border border-slate-200 shadow-xl rounded-2xl w-full max-w-md overflow-hidden transform"
             @click.outside="showDeleteModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="inline-flex justify-center items-center w-12 h-12 rounded-full bg-rose-50 border border-rose-100 text-rose-500 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-bold text-slate-800">
                    Supprimer le terrain
                </h3>
                <p class="text-sm text-slate-500 mt-2">
                    Êtes-vous sûr de vouloir supprimer le terrain <span class="font-semibold text-slate-700" x-text="deleteFieldName"></span> ?
                </p>
                <div class="text-xs text-rose-500 bg-rose-50 border border-rose-100 rounded-lg p-3 mt-4 text-left flex gap-2">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Cette action est irréversible et supprimera également tous les créneaux et réservations associés.</span>
                </div>
                
                <div class="mt-6 flex justify-end gap-x-2">
                    <button type="button" @click="showDeleteModal = false" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 transition-colors">
                        Annuler
                    </button>
                    <form method="POST" :action="'/admin/fields/' + deleteFieldId" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm">
                            Supprimer le terrain
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
