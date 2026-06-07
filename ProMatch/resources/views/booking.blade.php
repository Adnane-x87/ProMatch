<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ProMatch — Réserver un Terrain</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900 font-sans antialiased min-h-screen">

    <!-- Navbar (Floating Style) -->
    <header class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-7xl">
        <div class="bg-white/80 backdrop-blur-md border border-slate-200 shadow-xl rounded-full px-8 py-4 flex items-center justify-between h-16 md:h-20">
            <div class="flex-shrink-0 relative h-16 md:h-20 flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo"
                        class="absolute -left-8 top-[52%] -translate-y-1/2 h-24 md:h-32 w-auto max-w-none">
                </a>
            </div>
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-brand-600 transition-colors uppercase tracking-widest group">
                <x-lucide-chevron-left class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" />
                Retour
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-32 pb-12 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            
            <!-- Main Split Container -->
            <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-slate-200 lg:grid lg:grid-cols-2 min-h-[700px]">
                
                <!-- Left Side: Image Area -->
                <div class="relative hidden lg:block bg-slate-900 overflow-hidden">
                    <img src="{{ asset('images/side-hero.png') }}" alt="Reservation Side Hero" 
                         loading="lazy" decoding="async"
                         class="absolute inset-0 w-full h-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-br from-brand-600/40 to-slate-950/80 z-10"></div>
                    
                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-12 text-white z-20 relative">

                        <h2 class="text-3xl font-black mb-4 tracking-tight">Votre expérience ProMatch</h2>
                        <p class="text-slate-300 max-w-sm leading-relaxed">Réservez votre terrain en quelques clics et rejoignez la plus grande communauté de joueurs.</p>
                    </div>
                </div>

                <!-- Right Side: The Form -->
                <div class="p-8 md:p-12 lg:p-16 flex flex-col">
                    
                    <div class="mb-10">
                        <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Réserver votre terrain</h1>
                        <p class="text-brand-600 font-bold tracking-widest text-[10px] uppercase">Réservation par créneau d'1 heure</p>
                    </div>

                    <form method="POST" action="{{ url('/booking') }}" enctype="multipart/form-data" id="bookingForm" class="space-y-6 flex-1">
                        @csrf

                        <!-- Error Banner -->
                        <div id="errorBanner" class="{{ $errors->any() ? 'flex' : 'hidden' }} items-center gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl mb-6 transition-all duration-300">
                            <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                                <x-lucide-x-circle class="w-4 h-4" />
                            </div>
                            <div class="text-sm font-semibold" id="errorBannerText">{{ $errors->first() }}</div>
                        </div>

                        <!-- Selection Area -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Terrain -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1" for="terrain">Terrain</label>
                                <select id="terrain" name="terrain_id" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                                    <option value="" disabled @selected(!old('terrain_id'))>Choisir un terrain</option>
                                    @forelse($fields as $field)
                                        <option value="{{ $field->id }}" data-price="{{ $field->price_per_hour ?? 300 }}" @selected(old('terrain_id') == $field->id)>{{ $field->name }}</option>
                                    @empty
                                        <option value="" disabled>Aucun terrain disponible</option>
                                    @endforelse
                                </select>
                                <p data-error-for="terrain_id" class="text-xs font-semibold text-rose-600 {{ $errors->has('terrain_id') ? '' : 'hidden' }}">{{ $errors->first('terrain_id') }}</p>
                            </div>

                            <!-- Date -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1" for="date">Date</label>
                                <input type="date" id="date" name="date" value="{{ old('date') }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                                <p data-error-for="date" class="text-xs font-semibold text-rose-600 {{ $errors->has('date') ? '' : 'hidden' }}">{{ $errors->first('date') }}</p>
                            </div>
                        </div>

                        <!-- Time Slots Grid -->
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Heure disponible</label>
                            <div class="grid grid-cols-4 gap-2" id="timeSlots">
                                <div class="col-span-4 text-center text-[12px] text-slate-400 font-medium py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    Veuillez sélectionner une date pour voir les créneaux disponibles.
                                </div>
                            </div>
                            <input type="hidden" id="selectedTime" name="selected_time" value="{{ old('selected_time') }}">
                            <input type="hidden" id="timeSlotId" name="time_slot_id" value="{{ old('time_slot_id') }}">
                            <p data-error-for="selected_time" class="text-xs font-semibold text-rose-600 {{ $errors->has('selected_time') ? '' : 'hidden' }}">{{ $errors->first('selected_time') }}</p>
                        </div>

                        <!-- Personal Info -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                             <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Vos informations</label>
                             
                             <div class="grid grid-cols-2 gap-4">
                                <input type="text" placeholder="Prénom" name="first_name" required maxlength="100" pattern="[\p{L}\s'-]{2,100}" oninput="this.value = this.value.replace(/[^\p{L}\s'-]/gu, '')"
                                    value="{{ old('first_name', optional(auth()->user())->first_name) }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                                <input type="text" placeholder="Nom" name="last_name" required maxlength="100" pattern="[\p{L}\s'-]{2,100}" oninput="this.value = this.value.replace(/[^\p{L}\s'-]/gu, '')"
                                    value="{{ old('last_name', optional(auth()->user())->last_name) }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                                <p data-error-for="first_name" class="text-xs font-semibold text-rose-600 {{ $errors->has('first_name') ? '' : 'hidden' }}">{{ $errors->first('first_name') }}</p>
                                <p data-error-for="last_name" class="text-xs font-semibold text-rose-600 {{ $errors->has('last_name') ? '' : 'hidden' }}">{{ $errors->first('last_name') }}</p>
                             </div>

                             <div class="grid grid-cols-2 gap-4">
                                <input type="tel" placeholder="Téléphone" name="phone" required minlength="8" maxlength="30" pattern="[0-9+\s().-]{8,30}" inputmode="tel" oninput="this.value = this.value.replace(/[^0-9+\s().-]/g, '')"
                                    value="{{ old('phone', optional(auth()->user())->phone) }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                                <input type="email" placeholder="Email" name="email" required
                                    value="{{ old('email', optional(auth()->user())->email) }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                                <p data-error-for="phone" class="text-xs font-semibold text-rose-600 {{ $errors->has('phone') ? '' : 'hidden' }}">{{ $errors->first('phone') }}</p>
                                <p data-error-for="email" class="text-xs font-semibold text-rose-600 {{ $errors->has('email') ? '' : 'hidden' }}">{{ $errors->first('email') }}</p>
                             </div>

                             <!-- CNI Area -->
                             @php
                                 $isCniValid = auth()->check() && auth()->user()->tenant && auth()->user()->tenant->is_cni_valid;
                             @endphp

                             @if(!$isCniValid)
                             <div class="relative group" onclick="document.getElementById('cni_image').click()">
                                <div class="flex items-center gap-4 p-4 border-2 border-dashed border-slate-200 rounded-2xl group-hover:border-brand-500 group-hover:bg-brand-50 transition-all cursor-pointer">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center group-hover:bg-brand-100 group-hover:text-brand-600 transition-colors">
                                        <x-lucide-camera class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700" id="cniText">Identité (CNI)</p>
                                        <p class="text-[11px] text-slate-400 font-medium">La CNI est requise pour valider votre réservation</p>
                                    </div>
                                </div>
                                <input type="file" id="cni_image" name="cni_image" accept="image/jpeg, image/png, image/jpg" class="hidden">
                                <p data-error-for="cni_image" class="text-xs font-semibold text-rose-600 mt-1 {{ $errors->has('cni_image') ? '' : 'hidden' }}">{{ $errors->first('cni_image') }}</p>
                             </div>
                             @endif
                        </div>

                        <!-- Pricing & Action -->
                        <div class="mt-auto pt-10 border-t-4 border-brand-500 bg-slate-50/50 -mx-8 md:-mx-12 lg:-mx-16 px-8 md:px-12 lg:px-16 pb-8">
                            <div class="mb-8">
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Total estimé</p>
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span id="totalPrice" class="text-5xl font-black text-slate-900 tracking-tighter">300</span>
                                    <span class="text-2xl font-bold text-slate-400">DH</span>
                                </div>
                                <p class="text-[11px] font-bold text-slate-400 italic">Durée: 1 heure de service</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <a href="{{ url('/') }}" 
                                    class="flex-1 flex items-center justify-center gap-3 py-4 rounded-xl bg-white border-2 border-slate-900 text-slate-900 text-[11px] font-black tracking-widest hover:bg-slate-50 transition-all active:scale-95 uppercase shadow-sm">
                                    <x-lucide-arrow-left class="w-4 h-4" />
                                    Retour
                                </a>
                                <button type="submit" 
                                    class="flex-[2] flex items-center justify-center gap-3 py-4 rounded-xl bg-brand-600 text-white text-[11px] font-black tracking-widest hover:bg-brand-500 transition-all shadow-xl shadow-brand-600/20 active:scale-95 uppercase">
                                    Envoyer la demande
                                    <x-lucide-arrow-right class="w-4 h-4" />
                                </button>
                            </div>
                            <input type="hidden" id="priceInput" name="price" value="300">
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </main>

    @include('components.chatbot')

    <!-- Success Modal -->
    <div id="successModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl p-8 max-w-md mx-4 text-center shadow-2xl">
            <div
                class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-lucide-check class="w-8 h-8" />
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Demande envoyée !</h2>
            <p class="text-slate-500 mb-6">Votre réservation est en attente de validation. Vous recevrez une
                confirmation par SMS sous 24h.</p>
            <div class="bg-slate-50 rounded-lg p-4 mb-6 text-left text-sm">
                <div class="flex justify-between mb-2">
                    <span class="text-slate-500">Terrain</span>
                    <span class="font-medium text-slate-900" id="confirmTerrain">Terrain 1</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-slate-500">Date</span>
                    <span class="font-medium text-slate-900" id="confirmDate">-</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-slate-500">Heure</span>
                    <span class="font-medium text-slate-900" id="confirmTime">-</span>
                </div>
            </div>
            <button onclick="document.getElementById('successModal').classList.add('hidden')"
                class="w-full py-3 bg-slate-900 text-white font-semibold rounded-lg hover:bg-slate-800 transition-colors">
                Retour à l'accueil
            </button>
        </div>
    </div>

    <script>
        const terrainSelect = document.getElementById('terrain');
        const dateInput = document.getElementById('date');
        const timeSlotsContainer = document.getElementById('timeSlots');
        const priceDisplay = document.getElementById('totalPrice');

        async function parseJsonResponse(response) {
            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                throw new Error('Le serveur a renvoye une reponse inattendue.');
            }

            return response.json();
        }

        // Update price when terrain changes
        function updatePrice() {
            const selectedOption = terrainSelect.options[terrainSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                return;
            }
            const price = selectedOption.getAttribute('data-price') || 300;
            priceDisplay.textContent = price;
            const priceInput = document.getElementById('priceInput');
            if (priceInput) priceInput.value = price;
        }

        function clearFieldErrors() {
            document.querySelectorAll('[data-error-for]').forEach(errorElement => {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            });
        }

        function showFieldErrors(errors = {}) {
            Object.entries(errors).forEach(([field, messages]) => {
                const errorElement = document.querySelector(`[data-error-for="${field}"]`);
                if (!errorElement || !messages || messages.length === 0) {
                    return;
                }

                errorElement.textContent = messages[0];
                errorElement.classList.remove('hidden');
            });
        }
        
        terrainSelect.addEventListener('change', () => {
            updatePrice();
            fetchSlots();
        });
        
        dateInput.addEventListener('change', fetchSlots);

        // Fetch slots asynchronously
        async function fetchSlots() {
            const terrainId = terrainSelect.value;
            const date = dateInput.value;
            document.getElementById('selectedTime').value = '';
            document.getElementById('timeSlotId').value = '';

            if (!terrainId || !date) {
                return;
            }

            timeSlotsContainer.innerHTML = '<div class="col-span-4 text-center text-sm text-slate-500 py-2">Chargement...</div>';

            try {
                const response = await fetch(`/api/available-slots?field_id=${terrainId}&date=${date}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await parseJsonResponse(response);

                if (result.success && result.data.length > 0) {
                    timeSlotsContainer.innerHTML = '';
                    result.data.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'time-slot py-3 rounded-xl border border-slate-200 bg-slate-50 text-[13px] font-bold text-slate-600 hover:border-brand-500 hover:bg-brand-50 hover:text-brand-600 transition-all text-center active:scale-95';
                        // Extract just HH:mm if needed, assuming slot.start_time is HH:mm:00
                        const displayTime = slot.start_time.substring(0, 5); 
                        btn.textContent = displayTime;
                        btn.setAttribute('data-time', displayTime);
                        btn.setAttribute('data-id', slot.id || '');
                        
                        btn.addEventListener('click', () => {
                            document.querySelectorAll('.time-slot').forEach(b => {
                                b.classList.remove('border-brand-600', 'bg-brand-600', 'text-white', 'shadow-lg', 'shadow-brand-600/20', 'ring-4', 'ring-brand-500/20');
                                b.removeAttribute('aria-pressed');
                                b.style.backgroundColor = '';
                                b.style.borderColor = '';
                                b.style.color = '';
                                b.style.boxShadow = '';
                            });
                            btn.classList.add('border-brand-600', 'bg-brand-600', 'text-white', 'shadow-lg', 'shadow-brand-600/20', 'ring-4', 'ring-brand-500/20');
                            btn.setAttribute('aria-pressed', 'true');
                            btn.style.backgroundColor = '#4da565';
                            btn.style.borderColor = '#4da565';
                            btn.style.color = '#ffffff';
                            btn.style.boxShadow = '0 10px 22px rgba(77, 165, 101, 0.28)';
                            document.getElementById('selectedTime').value = displayTime;
                            document.getElementById('timeSlotId').value = slot.id || '';
                        });
                        
                        timeSlotsContainer.appendChild(btn);
                    });
                } else {
                    timeSlotsContainer.innerHTML = '<div class="col-span-4 text-center text-sm text-slate-500 py-2">Aucun créneau disponible pour cette date.</div>';
                }
            } catch (error) {
                console.error("Error fetching slots:", error);
                timeSlotsContainer.innerHTML = '<div class="col-span-4 text-center text-sm text-red-500 py-2">Erreur lors du chargement des créneaux.</div>';
            }
        }
        
        // CNI file selection visual feedback
        const cniInput = document.getElementById('cni_image');
        const cniText = document.getElementById('cniText');
        if (cniInput && cniText) {
            cniInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    cniText.innerHTML = '<span class="text-brand-600 font-medium">' + this.files[0].name + '</span> sélectionné';
                } else {
                    cniText.innerHTML = 'Glissez votre CNI ou <span class="text-brand-600 font-medium">cliquez</span>';
                }
            });
        }

        // Initial price update
        updatePrice();

        // Intercept form submission and show modal using AJAX
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Check authentication
            @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest
            
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            const errorBanner = document.getElementById('errorBanner');
            const errorBannerText = document.getElementById('errorBannerText');
            if (errorBanner) {
                errorBanner.classList.add('hidden');
                errorBanner.classList.remove('flex');
            }
            clearFieldErrors();
            
            // Basic validation
            if (!formData.get('selected_time')) {
                showFieldErrors({ selected_time: ['Veuillez sélectionner une heure.'] });
                return;
            }

            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Envoi en cours...</span>';

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const result = await parseJsonResponse(response);

                if (response.ok && result.success) {
                    // Update confirmation modal info
                    const terrainSelect = document.getElementById('terrain');
                    document.getElementById('confirmTerrain').textContent = terrainSelect.options[terrainSelect.selectedIndex].text;
                    document.getElementById('confirmDate').textContent = formData.get('date');
                    document.getElementById('confirmTime').textContent = formData.get('selected_time') + ':00';
                    
                    // Show modal
                    document.getElementById('successModal').classList.remove('hidden');
                    document.getElementById('successModal').classList.add('flex');
                    form.reset();
                } else {
                    let errorMsg = result.message || 'Une erreur est survenue lors de la réservation.';
                    if (result.errors) {
                        const firstKey = Object.keys(result.errors)[0];
                        if (firstKey && result.errors[firstKey].length > 0) {
                            errorMsg = result.errors[firstKey][0];
                        }
                        showFieldErrors(result.errors);
                    }
                    if (errorBanner && errorBannerText) {
                        errorBannerText.textContent = errorMsg;
                        errorBanner.classList.remove('hidden');
                        errorBanner.classList.add('flex');
                        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        alert('Erreur: ' + errorMsg);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const connErrorMsg = 'Une erreur de connexion est survenue. Veuillez réessayer.';
                if (errorBanner && errorBannerText) {
                    errorBannerText.textContent = connErrorMsg;
                    errorBanner.classList.remove('hidden');
                    errorBanner.classList.add('flex');
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    alert(connErrorMsg);
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    </script>
</body>
</html>
