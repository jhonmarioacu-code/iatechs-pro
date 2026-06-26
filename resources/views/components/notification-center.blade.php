<aside class="notification-panel" x-show="notificationsOpen" x-transition.opacity>
    <header>
        <h3>Notificaciones</h3>
        <button type="button" class="icon-button" @click="toggleNotifications()" aria-label="Cerrar notificaciones">X</button>
    </header>
    <ul>
        <li>
            <strong>Tickets</strong>
            <p>5 tickets cambiaron de estado en la ultima hora.</p>
        </li>
        <li>
            <strong>Inventario</strong>
            <p>2 productos entraron en umbral de stock critico.</p>
        </li>
        <li>
            <strong>Facturacion</strong>
            <p>1 factura vence hoy y requiere seguimiento.</p>
        </li>
    </ul>
</aside>
