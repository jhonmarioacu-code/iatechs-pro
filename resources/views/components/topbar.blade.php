@props(['portal' => 'admin'])

@php
    $workspace = auth()->user()?->company?->name ?? ucfirst($portal).' Workspace';
@endphp

<header class="topbar" x-data="{ now: new Date().toLocaleTimeString(), initClock() { setInterval(() => this.now = new Date().toLocaleTimeString(), 1000) } }" x-init="initClock()">
    <div class="topbar-start">
        <button class="icon-button lg-hidden" type="button" @click="toggleSidebar()" aria-label="Abrir menu lateral">
            MN
        </button>
        <label class="search-wrap" for="global-search">
            <span class="search-icon">SR</span>
            <input id="global-search" type="search" class="search-input" placeholder="Buscar tickets, clientes, facturas" />
        </label>
    </div>

    <div class="topbar-end">
        @auth
            <div class="user-chip">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</span>
            </div>
        @endauth
        <span class="clock-chip" x-text="now"></span>
        <button class="icon-button notification-toggle" type="button" @click="toggleNotifications()" aria-label="Notificaciones">
            NT
            <span class="notification-badge" x-show="unreadNotificationsCount > 0" x-text="unreadNotificationsCount"></span>
        </button>
        <button class="icon-button" type="button" @click="toggleAssistant()" aria-label="IA Assistant">
            AI
        </button>
        <button class="icon-button" type="button" @click="toggleTheme()" aria-label="Cambiar tema">
            <span x-text="darkMode ? 'DK' : 'LG'"></span>
        </button>
        <select class="company-selector" aria-label="Selector de empresa">
            <option>{{ $workspace }}</option>
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
