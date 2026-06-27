<aside class="notification-panel" x-show="notificationsOpen" x-transition.opacity>
    <header>
        <h3>Notificaciones</h3>
        <button type="button" class="icon-button" @click="toggleNotifications()" aria-label="Cerrar notificaciones">X</button>
    </header>

    <div class="notification-status">
        <span class="status-dot" :class="{ 'online': realtimeConnected, 'offline': !realtimeConnected }"></span>
        <span x-text="realtimeStatusLabel"></span>
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

    <ul x-show="!notificationsLoading && notifications.length > 0">
        <template x-for="item in notifications" :key="item.id">
            <li :class="{ 'is-unread': item.status !== 'READ' }">
                <div class="notification-row">
                    <strong x-text="item.title || 'Sin titulo'"></strong>
                    <small x-text="item.type || 'INFO'"></small>
                </div>
                <p x-text="item.message || ''"></p>
                <div class="notification-row">
                    <small x-text="notificationTimestamp(item)"></small>
                    <button
                        x-show="item.status !== 'READ'"
                        type="button"
                        class="btn btn-secondary"
                        @click="markNotificationAsRead(item.id)"
                    >
                        Marcar leida
                    </button>
                </div>
            </li>
        </template>
    </ul>
</aside>
