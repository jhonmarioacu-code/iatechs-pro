@props([
    'portal' => 'admin',
    'menu' => [],
])

@php
    $secondarySlugs = ['ai-assistant', 'observability', 'operations'];
    $hiddenSlugs = $portal === 'admin' ? ['dashboards'] : [];
    $primaryMenu = [];
    $secondaryMenu = [];

    foreach ($menu as $item) {
        $slug = (string) ($item['slug'] ?? '');

        if (in_array($slug, $hiddenSlugs, true)) {
            continue;
        }

        if (in_array($slug, $secondarySlugs, true)) {
            $secondaryMenu[] = $item;
            continue;
        }

        $primaryMenu[] = $item;
    }
@endphp

<aside class="sidebar" :class="{ 'open': sidebarOpen }">
    <div class="sidebar-brand">
        <div class="sidebar-brand-copy">
            <span class="sidebar-brand-mark">IP</span>
            <div>
                <p class="sidebar-brand-label">IAtechs Pro</p>
                <p class="sidebar-brand-title">{{ ucfirst($portal) }} Portal</p>
            </div>
        </div>

        <button class="icon-button lg-hidden" type="button" @click="toggleSidebar()" aria-label="Cerrar menu lateral">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav" aria-label="Menu principal">
        @foreach ($primaryMenu as $item)
            @php
                $href = $item['href'] ?? route('portal.module', ['portal' => $portal, 'module' => $item['slug']]);
                $icon = $item['icon'] ?? 'MD';
                $isActive = request()->url() === $href;
            @endphp
            <a href="{{ $href }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
                <span class="sidebar-link-icon">{{ $icon }}</span>
                <span class="sidebar-link-label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    @if ($portal === 'admin' && $secondaryMenu !== [])
        <p class="sidebar-section-title">Aplicaciones</p>
        <nav class="sidebar-nav sidebar-nav-secondary" aria-label="Aplicaciones">
            @foreach ($secondaryMenu as $item)
                @php
                    $href = $item['href'] ?? route('portal.module', ['portal' => $portal, 'module' => $item['slug']]);
                    $icon = $item['icon'] ?? 'MD';
                    $isActive = request()->url() === $href;
                @endphp
                <a href="{{ $href }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
                    <span class="sidebar-link-icon">{{ $icon }}</span>
                    <span class="sidebar-link-label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    @endif

    <div class="sidebar-footer">
        <p>Enterprise SaaS</p>
        <small>Laravel 12 + PostgreSQL</small>
    </div>

    @if ($portal === 'admin')
        <div class="sidebar-upgrade-card">
            <p class="sidebar-upgrade-kicker">Plan Pro</p>
            <h3>Actualiza a Pro</h3>
            <p>Desbloquea automatizaciones y observabilidad avanzada.</p>
            <button class="btn btn-primary" type="button">Actualizar ahora</button>
        </div>
    @endif
</aside>

<div class="sidebar-backdrop" x-show="sidebarOpen" @click="toggleSidebar()" x-transition.opacity></div>
