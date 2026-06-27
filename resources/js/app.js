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
        notifications: [],
        notificationsLoading: false,
        unreadNotificationsCount: 0,
        realtimeEnabled: false,
        realtimeConnected: false,
        realtimeStatusLabel: 'Realtime inactivo',
        realtimeConnection: 'log',
        realtimeCompanyChannel: '',
        realtimeUserChannel: '',
        notificationsUrl: '',
        notificationReadUrlTemplate: '',
        echo: null,
        realtimePollId: null,
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

            const portalHost = document.querySelector('[data-assistant-enabled]');
            if (portalHost) {
                this.assistantEnabled = portalHost.getAttribute('data-assistant-enabled') === '1';
            }

            const aiHost = document.querySelector('[data-ai-enabled]');
            if (aiHost) {
                this.assistantChatUrl = aiHost.getAttribute('data-ai-chat-url') || '';
                this.assistantConversationsUrl = aiHost.getAttribute('data-ai-conversations-url') || '';
                this.assistantMessagesUrlTemplate = aiHost.getAttribute('data-ai-messages-url-template') || '';
            }

            this.bootstrapRealtime();
        },
        bootstrapRealtime() {
            const host = document.querySelector('[data-realtime-enabled]');

            if (!host) {
                return;
            }

            this.realtimeEnabled = host.getAttribute('data-realtime-enabled') === '1';
            this.realtimeConnection = host.getAttribute('data-broadcast-connection') || 'log';
            this.realtimeCompanyChannel = host.getAttribute('data-company-channel') || '';
            this.realtimeUserChannel = host.getAttribute('data-user-channel') || '';
            this.notificationsUrl = host.getAttribute('data-notifications-url') || '';
            this.notificationReadUrlTemplate = host.getAttribute('data-notification-read-template') || '';

            if (!this.realtimeEnabled) {
                this.realtimeStatusLabel = 'Sin permiso de notificaciones';
                return;
            }

            void this.loadNotifications();
            this.initRealtimeConnection(host);
        },
        initRealtimeConnection(host) {
            if (this.echo || typeof window.createPortalEcho !== 'function') {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const runtime = {
                broadcaster: this.realtimeConnection,
                authEndpoint: '/broadcasting/auth',
                csrfToken,
            };

            if (this.realtimeConnection === 'pusher') {
                runtime.key = host.getAttribute('data-pusher-key') || '';
                runtime.host = host.getAttribute('data-pusher-host') || '';
                runtime.port = host.getAttribute('data-pusher-port') || '';
                runtime.scheme = host.getAttribute('data-pusher-scheme') || 'https';
                runtime.cluster = host.getAttribute('data-pusher-cluster') || 'mt1';
            } else {
                runtime.key = host.getAttribute('data-reverb-key') || '';
                runtime.host = host.getAttribute('data-reverb-host') || '';
                runtime.port = host.getAttribute('data-reverb-port') || '';
                runtime.scheme = host.getAttribute('data-reverb-scheme') || 'http';
            }

            this.echo = window.createPortalEcho(runtime);

            if (!this.echo) {
                this.realtimeStatusLabel = 'Realtime deshabilitado';
                return;
            }

            this.registerRealtimeConnectionState();
            this.subscribeNotificationChannels();

            if (this.realtimePollId === null) {
                this.realtimePollId = window.setInterval(() => {
                    if (!this.realtimeConnected) {
                        void this.loadNotifications();
                    }
                }, 60000);
            }
        },
        registerRealtimeConnectionState() {
            const pusher = this.echo?.connector?.pusher;
            const connection = pusher?.connection;

            if (!connection) {
                this.realtimeStatusLabel = 'Realtime sin conector';
                return;
            }

            connection.bind('connected', () => {
                this.realtimeConnected = true;
                this.realtimeStatusLabel = 'Realtime conectado';
            });

            connection.bind('disconnected', () => {
                this.realtimeConnected = false;
                this.realtimeStatusLabel = 'Realtime desconectado';
            });

            connection.bind('unavailable', () => {
                this.realtimeConnected = false;
                this.realtimeStatusLabel = 'Realtime no disponible';
            });

            connection.bind('error', () => {
                this.realtimeConnected = false;
                this.realtimeStatusLabel = 'Realtime con error';
            });
        },
        subscribeNotificationChannels() {
            if (!this.echo) {
                return;
            }

            const bindChannel = (channelName) => {
                if (!channelName) {
                    return;
                }

                this.echo
                    .private(channelName)
                    .listen('.notifications.streamed', (payload) => {
                        this.handleRealtimeNotification(payload);
                    });
            };

            bindChannel(this.realtimeCompanyChannel);
            if (this.realtimeUserChannel && this.realtimeUserChannel !== this.realtimeCompanyChannel) {
                bindChannel(this.realtimeUserChannel);
            }
        },
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        toggleNotifications() {
            const willOpen = !this.notificationsOpen;
            this.notificationsOpen = willOpen;

            if (willOpen) {
                this.assistantPanelOpen = false;
            }

            if (willOpen && this.notifications.length === 0 && this.realtimeEnabled) {
                void this.loadNotifications();
            }
        },
        toggleAssistant() {
            this.toggleAssistantPanel();
        },
        toggleAssistantPanel() {
            const willOpen = !this.assistantPanelOpen;
            this.assistantPanelOpen = willOpen;

            if (willOpen) {
                this.notificationsOpen = false;
                this.assistantError = '';
                this.loadAssistantConversations();
            }
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            window.localStorage.setItem('iatechs-theme', this.darkMode ? 'dark' : 'light');
        },
        async loadNotifications() {
            if (!this.notificationsUrl || !this.realtimeEnabled) {
                return;
            }

            this.notificationsLoading = true;

            try {
                const response = await fetch(this.notificationsUrl, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`No se pudieron cargar notificaciones (${response.status})`);
                }

                const payload = await response.json();
                const rows = Array.isArray(payload?.data) ? payload.data : [];
                this.notifications = rows.slice(0, 30);
                this.syncUnreadCount();
            } catch (error) {
                this.realtimeStatusLabel = error.message || 'Error cargando notificaciones';
            } finally {
                this.notificationsLoading = false;
            }
        },
        handleRealtimeNotification(payload) {
            const item = payload?.notification;

            if (!item || typeof item.id === 'undefined') {
                return;
            }

            const index = this.notifications.findIndex((notification) => notification.id === item.id);

            if (index >= 0) {
                this.notifications.splice(index, 1, item);
            } else {
                this.notifications.unshift(item);
            }

            this.notifications = this.notifications.slice(0, 30);
            this.syncUnreadCount();
        },
        async markNotificationAsRead(notificationId) {
            if (!this.notificationReadUrlTemplate || !notificationId) {
                return;
            }

            const url = this.notificationReadUrlTemplate.replace('__NOTIFICATION__', String(notificationId));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (!response.ok) {
                    throw new Error(`No se pudo marcar notificacion (${response.status})`);
                }

                const payload = await response.json();
                const updated = payload?.data;

                if (updated?.id) {
                    this.handleRealtimeNotification({
                        notification: updated,
                    });
                    return;
                }

                const index = this.notifications.findIndex((notification) => notification.id === notificationId);
                if (index >= 0) {
                    this.notifications[index] = {
                        ...this.notifications[index],
                        status: 'READ',
                        read_at: new Date().toISOString(),
                    };
                    this.syncUnreadCount();
                }
            } catch (error) {
                this.realtimeStatusLabel = error.message || 'Error marcando notificacion';
            }
        },
        syncUnreadCount() {
            this.unreadNotificationsCount = this.notifications.reduce((total, item) => (
                item?.status === 'READ' ? total : total + 1
            ), 0);
        },
        notificationTimestamp(item) {
            const raw = item?.read_at || item?.sent_at || item?.created_at || item?.updated_at || '';

            if (!raw) {
                return 'Sin fecha';
            }

            const date = new Date(raw);

            if (Number.isNaN(date.getTime())) {
                return 'Sin fecha';
            }

            return date.toLocaleString();
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
