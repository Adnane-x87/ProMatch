<div
    class="fixed bottom-5 right-5 z-[90] font-sans"
    data-promatch-chatbot
    data-chatbot-endpoint="{{ route('chatbot.message') }}"
>
    <section
        class="mb-4 hidden w-[min(calc(100vw-2rem),24rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20"
        data-chatbot-panel
        aria-label="Assistant ProMatch"
    >
        <div class="flex items-center justify-between bg-slate-900 px-4 py-3 text-white">
            <div>
                <p class="text-sm font-black">Assistant ProMatch</p>
                <p class="text-xs text-emerald-100">Reservation rapide</p>
            </div>
            <button
                type="button"
                class="rounded-full p-2 text-white/70 transition hover:bg-white/10 hover:text-white"
                data-chatbot-close
                aria-label="Fermer le chat"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div class="h-80 space-y-3 overflow-y-auto bg-slate-50 p-4" data-chatbot-messages>
            <div class="max-w-[85%] rounded-2xl rounded-bl-md bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200">
                Bonjour, quel terrain voulez-vous reserver ?
            </div>
        </div>

        <form class="flex items-end gap-2 border-t border-slate-200 bg-white p-3" data-chatbot-form>
            <textarea
                class="max-h-28 min-h-11 flex-1 resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10"
                data-chatbot-input
                rows="1"
                maxlength="1000"
                placeholder="Votre demande..."
                aria-label="Votre message"
                required
            ></textarea>
            <button
                type="submit"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-500 disabled:cursor-not-allowed disabled:opacity-60"
                data-chatbot-submit
                aria-label="Envoyer"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m22 2-7 20-4-9-9-4Z" />
                    <path d="M22 2 11 13" />
                </svg>
            </button>
        </form>
    </section>

    <button
        type="button"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-600 text-white shadow-2xl shadow-brand-600/30 ring-4 ring-white transition hover:-translate-y-0.5 hover:bg-brand-500"
        data-chatbot-toggle
        aria-label="Ouvrir le chat"
        aria-expanded="false"
    >
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z" />
            <path d="M8 10h8" />
            <path d="M8 14h5" />
        </svg>
    </button>
</div>
