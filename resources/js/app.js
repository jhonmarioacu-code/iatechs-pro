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
        assistantEnabled: true,
        assistantPanelOpen: false,
        initTheme() {
            const stored = window.localStorage.getItem('iatechs-theme');
            this.darkMode = stored === 'dark';
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
        },
        toggleTheme() {
            this.darkMode = !this.darkMode;
            window.localStorage.setItem('iatechs-theme', this.darkMode ? 'dark' : 'light');
        },
    };
};

document.addEventListener('DOMContentLoaded', () => {
    refreshHealth();
    document.querySelector('[data-health-refresh]')?.addEventListener('click', refreshHealth);
    initCompanyCrudPages();
});

Alpine.start();
