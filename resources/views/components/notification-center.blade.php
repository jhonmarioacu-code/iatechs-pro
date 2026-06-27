<div class="notification-layer" x-show="notificationsOpen" x-transition.opacity x-cloak @keydown.escape.window="closeNotifications()">
    <div class="notification-overlay" @click="closeNotifications()"></div>

    <aside class="notification-panel" @click.stop>
        <header class="notification-header">
            <div>
                <h3>Notificaciones</h3>
                <p>Centro en tiempo real por rol y tenant.</p>
            </div>
            <button type="button" class="icon-button" @click="closeNotifications()" aria-label="Cerrar notificaciones">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>
        </header>

        <div class="notification-toolbar">
            <div class="notification-status">
                <span class="status-dot" :class="{ 'online': realtimeConnected, 'offline': !realtimeConnected }"></span>
                <span x-text="realtimeStatusLabel"></span>
            </div>
            <span class="notification-counter" x-text="`${unreadNotificationsCount} sin leer`"></span>
        </div>

        <template x-if="notificationsLoading">
            <div class="notification-empty">
                Cargando notificaciones...
            </div>
        </template>

        <template x-if="!notificationsLoading && notifications.length === 0">
            <div class="notification-empty">
                No hay notificaciones por ahora.
            </div>
        </template>

        <ul class="notification-list" x-show="!notificationsLoading && notifications.length > 0">
            <template x-for="item in notifications" :key="item.id">
                <li class="notification-item" :class="{ 'is-unread': item.status !== 'READ' }">
                    <div class="notification-row">
                        <strong x-text="item.title || 'Sin titulo'"></strong>
                        <span class="notification-type" x-text="item.type || 'INFO'"></span>
                    </div>
                    <p x-text="item.message || ''"></p>
                    <div class="notification-row">
                        <small x-text="notificationTimestamp(item)"></small>
                        <button
                            x-show="item.status !== 'READ'"
                            type="button"
                            class="btn btn-secondary notification-action"
                            @click="markNotificationAsRead(item.id)"
                        >
                            Marcar leida
                        </button>
                    </div>
                </li>
            </template>
        </ul>
    </aside>
</div>
