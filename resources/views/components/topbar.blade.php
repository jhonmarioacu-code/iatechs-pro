@props(['portal' => 'admin'])

<header class="topbar">
    <div class="topbar-start">
        <button class="icon-button lg-hidden" type="button" @click="toggleSidebar()" aria-label="Abrir menu lateral">
            M
        </button>
        <label class="search-wrap" for="global-search">
            <span class="search-icon">Q</span>
            <input id="global-search" type="search" class="search-input" placeholder="Buscar tickets, clientes, facturas..." />
        </label>
    </div>

    <div class="topbar-end">
        @auth
            <div class="user-chip">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</span>
            </div>
        @endauth
        <button class="icon-button" type="button" @click="toggleNotifications()" aria-label="Notificaciones">
            N
        </button>
        <button class="icon-button" type="button" @click="toggleAssistant()" aria-label="IA Assistant">
            AI
        </button>
        <button class="icon-button" type="button" @click="toggleTheme()" aria-label="Cambiar tema">
            <span x-text="darkMode ? 'dark' : 'light'"></span>
        </button>
        <select class="company-selector" aria-label="Selector de empresa">
            <option>{{ ucfirst($portal) }} Workspace</option>
            <option>Acme Electronics</option>
            <option>North Service Group</option>
        </select>
        @auth
            <a class="btn btn-secondary" href="{{ route('portal.account.security.edit') }}">Seguridad</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-secondary" type="submit">Salir</button>
            </form>
        @endauth
    </div>
</header>
