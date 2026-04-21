@extends('layouts.guest')
@section('title', 'ProMatch — Créer un compte')

@push('styles')
<style>
    /* Password strength bar animation */
    .strength-bar {
        transition: width 0.4s ease, background-color 0.4s ease;
    }

    /* Input focus glow */
    input:focus {
        box-shadow: 0 0 0 3px rgba(77, 165, 101, 0.15);
    }

    /* Toggle password eye icon */
    .eye-btn {
        cursor: pointer;
    }

    /* Step indicator animation */
    .step-dot {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
</style>
@endpush

@push('bg-decoration')
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-brand-100/20 rounded-full blur-3xl pointer-events-none">
    </div>
@endpush

@section('content')
    <!-- Register Card -->
    <div
        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 w-full max-w-lg p-8 relative z-10 border border-slate-100 my-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ProMatch Logo" class="h-32 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Créer un compte</h1>
            <p class="text-slate-500 mt-2">Rejoignez ProMatch et réservez votre terrain</p>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-5" onsubmit="handleSubmit(event)">
            @csrf

            <!-- Step 1: Personal Info -->
            <div id="step1">

                <!-- Full Name -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="name">Nom complet</label>
                    <div class="relative">
                        <input id="name" name="name" type="text"
                            class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-500 outline-none transition-all"
                            placeholder="Prénom Nom" required autofocus>
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Phone -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="phone">Téléphone</label>
                    <div class="relative">
                        <input id="phone" name="phone" type="tel"
                            class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-500 outline-none transition-all"
                            placeholder="+212 6XX XXX XXX">
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="email">Email</label>
                    <div class="relative">
                        <input id="email" name="email" type="email"
                            class="w-full rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-500 outline-none transition-all"
                            placeholder="exemple@email.com" required>
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="password">Mot de passe</label>
                    <div class="relative">
                        <input id="password" name="password" type="password"
                            class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm focus:border-brand-500 outline-none transition-all"
                            placeholder="••••••••" oninput="checkStrength(this.value)" required>
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <button type="button"
                            class="eye-btn absolute right-3 top-2.5 text-slate-400 hover:text-slate-600"
                            onclick="togglePassword('password', this)">
                            <svg id="eye-icon-password" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Strength Bar -->
                <div class="mb-5">
                    <div class="flex gap-1 mb-1" id="strength-bars">
                        <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
                            <div id="bar1" class="h-full w-0 strength-bar rounded-full"></div>
                        </div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
                            <div id="bar2" class="h-full w-0 strength-bar rounded-full"></div>
                        </div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
                            <div id="bar3" class="h-full w-0 strength-bar rounded-full"></div>
                        </div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 overflow-hidden">
                            <div id="bar4" class="h-full w-0 strength-bar rounded-full"></div>
                        </div>
                    </div>
                    <p id="strength-label" class="text-xs text-slate-400"></p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="password_confirmation">Confirmer le
                        mot de passe</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="w-full rounded-xl border border-slate-300 pl-10 pr-10 py-2.5 text-sm focus:border-brand-500 outline-none transition-all"
                            placeholder="••••••••" required>
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <button type="button"
                            class="eye-btn absolute right-3 top-2.5 text-slate-400 hover:text-slate-600"
                            onclick="togglePassword('password_confirmation', this)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="match-error" class="text-xs text-red-500 mt-1 hidden">Les mots de passe ne correspondent pas.
                    </p>
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-6">
                    <label class="flex items-start gap-2.5 cursor-pointer group">
                        <input id="terms" name="terms" type="checkbox"
                            class="mt-0.5 rounded border-slate-300 text-brand-600 focus:ring-brand-500" required>
                        <span class="text-sm text-slate-600">
                            J'accepte les
                            <a href="#" class="font-semibold text-brand-600 hover:text-brand-700">Conditions
                                générales</a>
                            et la
                            <a href="#" class="font-semibold text-brand-600 hover:text-brand-700">Politique de
                                confidentialité</a>
                        </span>
                    </label>
                </div>

            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn"
                class="w-full rounded-xl bg-brand-500 px-6 py-3 text-sm font-bold text-white hover:bg-brand-600 active:scale-[0.98] transition-all shadow-lg shadow-brand-500/20 flex items-center justify-center gap-2">
                Créer mon compte
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <!-- Success State (hidden by default) -->
        <div id="successState" class="hidden text-center py-4">
            <div class="w-16 h-16 rounded-full bg-brand-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Compte créé avec succès !</h2>
            <p class="text-sm text-slate-500 mb-6">Un email de confirmation a été envoyé à votre adresse. Vérifiez votre
                boîte mail.</p>
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-6 py-3 text-sm font-bold text-white hover:bg-brand-600 transition-colors shadow-lg shadow-brand-500/20">
                Se connecter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

        <!-- Divider -->
        <div class="relative my-7">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-slate-500">Ou continuer avec</span>
            </div>
        </div>

        <!-- Social Login -->
        <div class="grid grid-cols-1 gap-3">
            <button
                class="flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335" />
                </svg>
                <span class="text-sm font-medium text-slate-700">Google</span>
            </button>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-slate-500 mt-8">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700">Se connecter</a>
        </p>
    </div>
@endsection

@push('scripts')
    <script>
        /* ── Password visibility toggle ─────────────────────── */
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden
                ? `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`
                : `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
        }

        /* ── Password strength checker ───────────────────────── */
        function checkStrength(value) {
            const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
            const label = document.getElementById('strength-label');
            let score = 0;

            if (value.length >= 8) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            const colors = ['bg-red-400', 'bg-amber-400', 'bg-brand-400', 'bg-brand-600'];
            const labels = ['Très faible', 'Faible', 'Moyen', 'Fort'];
            const txtCols = ['text-red-500', 'text-amber-500', 'text-brand-500', 'text-brand-600'];

            bars.forEach((bar, i) => {
                bar.className = `h-full strength-bar rounded-full ${i < score && value.length > 0 ? colors[score - 1] : 'bg-transparent'}`;
                bar.style.width = i < score && value.length > 0 ? '100%' : '0';
            });

            if (value.length === 0) {
                label.textContent = '';
            } else {
                label.textContent = labels[score - 1] || 'Très faible';
                label.className = `text-xs ${txtCols[score - 1] || 'text-red-500'}`;
            }
        }

        /* ── Form submit ─────────────────────────────────────── */
        async function handleSubmit(e) {
            e.preventDefault();

            const pwd = document.getElementById('password').value;
            const cpwd = document.getElementById('password_confirmation').value;
            const err = document.getElementById('match-error');

            if (pwd !== cpwd) {
                err.classList.remove('hidden');
                document.getElementById('password_confirmation').classList.add('border-red-400');
                return;
            }
            err.classList.add('hidden');

            const form = e.target;
            const formData = new FormData(form);
            const btn = document.getElementById('submitBtn');
            const originalText = btn.innerHTML;

            try {
                btn.disabled = true;
                btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg> Création en cours...`;

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
                    form.classList.add('hidden');
                    document.getElementById('successState').classList.remove('hidden');
                } else {
                    alert('Erreur: ' + (result.message || 'Une erreur est survenue.'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur de connexion est survenue.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
@endpush
