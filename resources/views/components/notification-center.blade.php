<aside class="notification-panel" x-show="notificationsOpen" x-transition.opacity>
    <header>
        <h3>Notificaciones</h3>
        <button type="button" @click="toggleNotifications()" aria-label="Cerrar notificaciones">✕</button>
    </header>
    <ul>
        <li>
            <strong>Tickets:</strong> 5 tickets cambiaron de estado.
        </li>
        <li>
            <strong>Inventario:</strong> 2 productos en stock crítico.
        </li>
        <li>
            <strong>Finanzas:</strong> 1 factura próxima a vencimiento.
        </li>
    </ul>
</aside>

