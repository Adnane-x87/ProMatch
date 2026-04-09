<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ProMatch — Réserver un Terrain</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9f1',
                            100: '#dcf1df',
                            200: '#bbe2c3',
                            300: '#8dca9e',
                            400: '#5eac72',
                            500: '#4da565',
                            600: '#3d8a54',
                            700: '#327145',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">

    <!-- Navbar -->
    <header class="fixed w-full top-0 z-50 bg-white border-b border-slate-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <div class="flex-shrink-0 relative h-20 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo"
                            class="absolute -left-14 top-1/2 -translate-y-1/2 h-32 w-auto max-w-none">
                    </a>
                </div>
                <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Retour</a>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="pt-28 pb-12 px-4 sm:px-6">
        <div class="mx-auto max-w-3xl">

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Réserver votre terrain</h1>
                <p class="text-slate-500">Réservation par créneau d'1 heure</p>
            </div>

            <!-- Booking form -->
            <form method="POST" action="{{ url('/booking') }}" enctype="multipart/form-data" id="bookingForm">
                @csrf
                <!-- Booking Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm">

                    <!-- Terrain Selection -->
                    <div class="p-6 border-b border-slate-100">
                        <label class="block text-sm font-medium text-slate-700 mb-2" for="terrain">Terrain</label>
                        <select id="terrain" name="terrain_id"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                            <option value="1">Terrain 1 — 5vs5 (300 DH/h)</option>
                            <option value="2">Terrain 2 — 7vs7 (450 DH/h)</option>
                            <option value="3">Terrain 3 — 5vs5 (300 DH/h)</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="p-6 border-b border-slate-100">
                        <label class="block text-sm font-medium text-slate-700 mb-2" for="date">Date</label>
                        <input type="date" id="date" name="date"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    </div>

                    <!-- Time Slots -->
                    <div class="p-6 border-b border-slate-100">
                        <label class="block text-sm font-medium text-slate-700 mb-3">Heure disponible <span
                                class="text-xs font-normal text-slate-400">(créneau 1h)</span></label>
                        <div class="grid grid-cols-4 gap-3" id="timeSlots">
                            <button type="button"
                                class="time-slot py-2.5 px-3 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:border-brand-500 hover:text-brand-600 transition-colors text-center"
                                data-time="18:00">18:00</button>
                            <button type="button"
                                class="time-slot py-2.5 px-3 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:border-brand-500 hover:text-brand-600 transition-colors text-center"
                                data-time="19:00">19:00</button>
                            <button type="button"
                                class="time-slot py-2.5 px-3 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:border-brand-500 hover:text-brand-600 transition-colors text-center"
                                data-time="20:00">20:00</button>
                            <button type="button"
                                class="time-slot py-2.5 px-3 rounded-lg border border-slate-200 text-sm font-medium text-slate-400 bg-slate-50 cursor-not-allowed text-center"
                                disabled>21:00</button>
                        </div>
                        <input type="hidden" id="selectedTime" name="selected_time">
                    </div>

                    <!-- User Info -->
                    <div class="p-6 border-b border-slate-100">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Vos informations</label>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <input type="text" placeholder="Prénom" name="first_name"
                                class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                            <input type="text" placeholder="Nom" name="last_name"
                                class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                        </div>
                        <input type="tel" placeholder="Téléphone" name="phone"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none mb-3">

                        <!-- CNI Upload -->
                        <div
                            class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-brand-500 hover:bg-brand-50 transition-colors cursor-pointer relative" onclick="document.getElementById('cni_image').click()">
                            <svg class="w-6 h-6 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-slate-500">Glissez votre CNI ou <span
                                    class="text-brand-600 font-medium">cliquez</span></p>
                            <input type="file" id="cni_image" name="cni_image" accept="image/jpeg, image/png, image/jpg" class="hidden">
                        </div>
                        <p class="text-xs text-slate-400 mt-2">La CNI est requise pour valider votre réservation</p>
                    </div>

                    <!-- Total & Submit -->
                    <div class="p-6 bg-slate-50 rounded-b-xl">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-slate-600">Total <span class="text-xs text-slate-400">(1
                                    heure)</span></span>
                            <span id="totalPrice" class="text-2xl font-bold text-slate-900">300 DH</span>
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-brand-600 text-white font-semibold rounded-lg hover:bg-brand-700 transition-colors shadow-lg shadow-brand-200">
                            Envoyer la demande
                        </button>
                        <p class="text-xs text-slate-400 text-center mt-3">Paiement sur place • Confirmation sous 24h</p>
                    </div>

                </div>
            </form>

        </div>
    </main>

    <!-- Success Modal (example of modal logic, optional to implement) -->
    <div id="successModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl p-8 max-w-md mx-4 text-center shadow-2xl">
            <div
                class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
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
        // Simple script to handle time slot selection visually for the form
        document.querySelectorAll('.time-slot:not([disabled])').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.time-slot').forEach(b => {
                    b.classList.remove('border-brand-500', 'text-brand-600', 'bg-brand-50');
                });
                button.classList.add('border-brand-500', 'text-brand-600', 'bg-brand-50');
                document.getElementById('selectedTime').value = button.getAttribute('data-time');
            });
        });

        // Intercept form submission and show modal using AJAX
        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Basic validation
            if (!formData.get('selected_time')) {
                alert('Veuillez sélectionner une heure.');
                return;
            }

            try {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Envoi en cours...</span>';

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const result = await response.json();

                if (result.success) {
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
                    alert('Erreur: ' + (result.message || 'Une erreur est survenue lors de la réservation.'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur de connexion est survenue. Veuillez réessayer.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    </script>
</body>
</html>
