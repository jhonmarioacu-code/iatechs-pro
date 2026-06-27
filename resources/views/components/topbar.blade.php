@props(['portal' => 'admin'])

@php
    $workspace = auth()->user()?->company?->name ?? ucfirst($portal).' Workspace';
    $authUser = auth()->user();
    $userName = $authUser?->name ?? 'Usuario';
    $roleName = $authUser?->getRoleNames()->first() ?? 'user';
    $parts = preg_split('/\s+/', trim($userName)) ?: [];
    $initials = '';

    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }

    if ($initials === '') {
        $initials = 'US';
    }

    $rangeStart = now()->startOfMonth()->format('j M Y');
    $rangeEnd = now()->endOfMonth()->format('j M Y');
@endphp

<header class="topbar" x-data="{ now: new Date().toLocaleTimeString(), initClock() { setInterval(() => this.now = new Date().toLocaleTimeString(), 1000) } }" x-init="initClock()">
    <div class="topbar-start">
        <button class="icon-button lg-hidden" type="button" @click="toggleSidebar()" aria-label="Abrir menu lateral">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
        <label class="search-wrap" for="global-search">
            <span class="search-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="6" />
                    <path d="M20 20l-4.3-4.3" />
                </svg>
            </span>
            <input id="global-search" type="search" class="search-input" placeholder="Buscar cualquier cosa..." />
        </label>
    </div>

    <div class="topbar-end">
        <span class="date-chip">{{ $rangeStart }} - {{ $rangeEnd }}</span>
        <span class="clock-chip" x-text="now"></span>

        <button class="icon-button" type="button" @click="toggleTheme()" aria-label="Cambiar tema">
            <span class="theme-toggle-text" x-text="darkMode ? 'DK' : 'LG'"></span>
        </button>

        <button class="icon-button notification-toggle" type="button" @click="$dispatch('portal-toggle-notifications')" aria-label="Notificaciones">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 10a6 6 0 0 1 12 0v5l2 2H4l2-2v-5Z" />
                <path d="M10 19a2 2 0 0 0 4 0" />
            </svg>
            <span class="notification-badge" x-show="unreadNotificationsCount > 0" x-text="unreadNotificationsCount > 99 ? '99+' : unreadNotificationsCount"></span>
        </button>

        <button class="icon-button" type="button" @click="$dispatch('portal-toggle-assistant')" aria-label="IA Assistant" x-show="assistantEnabled" x-cloak>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3 9.8 8.8 4 11l5.8 2.2L12 19l2.2-5.8L20 11l-5.8-2.2L12 3Z" />
            </svg>
        </button>

        <select class="company-selector" aria-label="Selector de empresa">
            <option>{{ $workspace }}</option>
        </select>

        @auth
            <div class="user-chip">
                <span class="user-avatar">{{ $initials }}</span>
                <span>
                    <strong>{{ $userName }}</strong>
                    <span>{{ $roleName }}</span>
                </span>
            </div>
            <a class="btn btn-secondary topbar-action-link" href="{{ route('portal.account.security.edit') }}">Seguridad</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-secondary topbar-action-link" type="submit">Salir</button>
            </form>
        @endauth
    </div>
</header>
