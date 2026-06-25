import Alpine from 'alpinejs';
import './bootstrap';
import '../css/app.css';
import { initCompanyCrudPages } from './company-crud';

window.Alpine = Alpine;

const stateText = {
    ok: 'OK',
    error: 'Error',
};

async function checkEndpoint(path) {
    const response = await fetch(path, {
        headers: {
            Accept: 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return response.json();
}

async function refreshHealth() {
    const button = document.querySelector('[data-health-refresh]');
    const appField = document.querySelector('[data-health-field="app"]');
    const apiField = document.querySelector('[data-health-field="api"]');
    const webRow = document.querySelector('[data-health-row="web"]');
    const apiRow = document.querySelector('[data-health-row="api"]');

    if (!button || !appField || !apiField || !webRow || !apiRow) {
        return;
    }

    button.setAttribute('disabled', 'disabled');

    try {
        const [web, api] = await Promise.all([
            checkEndpoint('/health'),
            checkEndpoint('/api/health'),
        ]);

        appField.textContent = web.status === 'ok' ? stateText.ok : stateText.error;
        apiField.textContent = api.status === 'ok' ? stateText.ok : stateText.error;
        webRow.textContent = `${web.service}: ${web.status}`;
        apiRow.textContent = `${api.service}: ${api.version}`;
    } catch (error) {
        appField.textContent = stateText.error;
        apiField.textContent = stateText.error;
        webRow.textContent = error.message;
        apiRow.textContent = error.message;
    } finally {
        button.removeAttribute('disabled');
    }
}

window.portalUi = function portalUi() {
    return {
        sidebarOpen: false,
        darkMode: false,
        notificationsOpen: false,
        assistantEnabled: false,
        assistantPanelOpen: false,
        assistantInput: '',
        assistantMessages: [],
        assistantLoading: false,
        assistantError: '',
        assistantConversationId: null,
        assistantConversations: [],
        assistantChatUrl: '',
        assistantConversationsUrl: '',
        assistantMessagesUrlTemplate: '',
        initTheme() {
            const stored = window.localStorage.getItem('iatechs-theme');
            this.darkMode = stored === 'dark';

            const assistantHost = document.querySelector('[data-assistant-enabled]');
            if (assistantHost) {
                this.assistantEnabled = assistantHost.getAttribute('data-assistant-enabled') === '1';
            }

            const aiHost = document.querySelector('[data-ai-enabled]');
            if (aiHost) {
                this.assistantChatUrl = aiHost.getAttribute('data-ai-chat-url') || '';
                this.assistantConversationsUrl = aiHost.getAttribute('data-ai-conversations-url') || '';
                this.assistantMessagesUrlTemplate = aiHost.getAttribute('data-ai-messages-url-template') || '';
            }
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        toggleNotifications() {
            this.notificationsOpen = !this.notificationsOpen;
        },
        toggleAssistant() {
            this.assistantPanelOpen = !this.assistantPanelOpen;
        },
        toggleAssistantPanel() {
            this.assistantPanelOpen = !this.assistantPanelOpen;
            if (this.assistantPanelOpen) {
                this.loadAssistantConversations();
            }
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            window.localStorage.setItem('iatechs-theme', this.darkMode ? 'dark' : 'light');
        },
        async loadAssistantConversations() {
            if (!this.assistantEnabled || !this.assistantConversationsUrl) {
                return;
            }

            try {
                const response = await fetch(this.assistantConversationsUrl, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`No se pudo cargar conversaciones (${response.status})`);
                }

                const payload = await response.json();
                const conversations = payload?.data || [];
                this.assistantConversations = Array.isArray(conversations) ? conversations : [];

                if (this.assistantConversations.length > 0) {
                    if (!this.assistantConversationId) {
                        this.assistantConversationId = String(this.assistantConversations[0].id || '');
                    }

                    await this.loadSelectedConversationMessages();
                } else {
                    this.assistantConversationId = '';
                    this.assistantMessages = [];
                }
            } catch (error) {
                this.assistantError = error.message || 'Error cargando conversaciones.';
            }
        },
        async loadSelectedConversationMessages() {
            if (!this.assistantEnabled) {
                return;
            }

            if (!this.assistantConversationId) {
                this.assistantMessages = [];
                return;
            }

            if (!this.assistantMessagesUrlTemplate || this.assistantMessagesUrlTemplate.indexOf('__CONVERSATION__') === -1) {
                return;
            }

            const url = this.assistantMessagesUrlTemplate.replace('__CONVERSATION__', String(this.assistantConversationId));

            try {
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`No se pudo cargar mensajes (${response.status})`);
                }

                const payload = await response.json();
                const messages = payload?.data || [];

                this.assistantMessages = Array.isArray(messages)
                    ? messages.map((item) => ({
                        role: item.role,
                        content: item.content,
                    }))
                    : [];
            } catch (error) {
                this.assistantError = error.message || 'Error cargando mensajes.';
            }
        },
        conversationLabel(item) {
            const title = item?.title || `Conversacion #${item?.id ?? ''}`;
            const preview = (item?.last_message_preview || '').trim();
            const whenRaw = item?.last_message_at || item?.updated_at || item?.created_at || '';

            let when = '';
            if (whenRaw) {
                const date = new Date(whenRaw);
                if (!Number.isNaN(date.getTime())) {
                    when = date.toLocaleString();
                }
            }

            const parts = [title];
            if (preview) {
                parts.push(`- ${preview}`);
            }
            if (when) {
                parts.push(`(${when})`);
            }

            return parts.join(' ');
        },
        async sendAssistantMessage() {
            const message = this.assistantInput.trim();

            if (!this.assistantEnabled || !this.assistantChatUrl || message === '') {
                return;
            }

            this.assistantLoading = true;
            this.assistantError = '';
            this.assistantMessages.push({
                role: 'user',
                content: message,
            });

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                const response = await fetch(this.assistantChatUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        conversation_id: this.assistantConversationId || null,
                        message,
                    }),
                });

                if (!response.ok) {
                    const payload = await response.json().catch(() => ({}));
                    throw new Error(payload?.message || `Error de IA (${response.status})`);
                }

                const payload = await response.json();
                this.assistantConversationId = String(payload?.conversation_id || this.assistantConversationId || '');
                this.assistantMessages.push({
                    role: 'assistant',
                    content: payload?.message || 'Sin respuesta',
                });
                this.assistantInput = '';
                await this.loadAssistantConversations();
            } catch (error) {
                this.assistantError = error.message || 'No fue posible consultar al asistente.';
            } finally {
                this.assistantLoading = false;
            }
        },
    };
};

document.addEventListener('DOMContentLoaded', () => {
    refreshHealth();
    document.querySelector('[data-health-refresh]')?.addEventListener('click', refreshHealth);
    initCompanyCrudPages();
});

Alpine.start();
