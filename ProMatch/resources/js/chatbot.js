const initPromatchChatbot = () => {
    const root = document.querySelector('[data-promatch-chatbot]');

    if (!root) return;

    const endpoint = root.dataset.chatbotEndpoint;
    const panel = root.querySelector('[data-chatbot-panel]');
    const toggle = root.querySelector('[data-chatbot-toggle]');
    const close = root.querySelector('[data-chatbot-close]');
    const form = root.querySelector('[data-chatbot-form]');
    const input = root.querySelector('[data-chatbot-input]');
    const messagesEl = root.querySelector('[data-chatbot-messages]');
    const submit = root.querySelector('[data-chatbot-submit]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const history = [];
    let reservationState = {};

    const setOpen = (isOpen) => {
        panel.classList.toggle('hidden', !isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        if (isOpen) input.focus();
    };

    const scrollMessages = () => {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    };

    const addMessage = (role, content, isLoading = false) => {
        const bubble = document.createElement('div');
        bubble.className = role === 'user'
            ? 'ml-auto max-w-[85%] rounded-2xl rounded-br-md bg-brand-600 px-4 py-3 text-sm font-semibold text-white shadow-sm'
            : 'max-w-[85%] rounded-2xl rounded-bl-md bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200';
        bubble.textContent = content;

        if (isLoading) {
            bubble.dataset.chatbotLoading = 'true';
            bubble.classList.add('text-slate-400');
        }

        messagesEl.appendChild(bubble);
        scrollMessages();
        return bubble;
    };

    const setBusy = (isBusy) => {
        submit.disabled = isBusy;
        input.disabled = isBusy;
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    close.addEventListener('click', () => setOpen(false));

    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = `${Math.min(input.scrollHeight, 112)}px`;
    });

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const userText = input.value.trim();
        if (!userText || !endpoint) return;

        input.value = '';
        input.style.height = 'auto';
        history.push({ role: 'user', content: userText });
        addMessage('user', userText);
        const loadingBubble = addMessage('assistant', '...', true);
        setBusy(true);

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    messages: history.slice(-12),
                    state: reservationState,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Erreur du chatbot.');
            }

            const reply = data.reply || 'Pouvez-vous preciser votre demande ?';
            reservationState = data.state || {};
            loadingBubble.textContent = reply;
            loadingBubble.classList.remove('text-slate-400');
            delete loadingBubble.dataset.chatbotLoading;
            history.push({ role: 'assistant', content: reply });
        } catch (error) {
            loadingBubble.textContent = error.message || 'Le chatbot est indisponible pour le moment.';
            loadingBubble.classList.remove('text-slate-400');
            loadingBubble.classList.add('text-rose-600');
        } finally {
            setBusy(false);
            input.focus();
            scrollMessages();
        }
    });
};

document.addEventListener('DOMContentLoaded', initPromatchChatbot);
