@props([
    'portal' => 'admin',
    'menu' => [],
])

<aside class="sidebar" :class="{ 'open': sidebarOpen }">
    <div class="sidebar-brand">
        <div>
            <p class="sidebar-brand-label">IAtechs Pro</p>
            <p class="sidebar-brand-title">{{ ucfirst($portal) }} Portal</p>
        </div>
        <button class="icon-button lg-hidden" type="button" @click="toggleSidebar()" aria-label="Cerrar menu lateral">
            X
        </button>
    </div>

    <nav class="sidebar-nav" aria-label="Menu principal">
        @foreach ($menu as $item)
            @php
                $canView = true;
                if (auth()->check() && isset($item['permission'])) {
                    $canView = auth()->user()->hasRole('super_admin')
                        || auth()->user()->can($item['permission']);
                }

                if (!$canView) {
                    continue;
                }

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

    <div class="sidebar-footer">
        <p>Enterprise SaaS</p>
        <small>Laravel 12 + PostgreSQL</small>
    </div>
</aside>

<div class="sidebar-backdrop" x-show="sidebarOpen" @click="toggleSidebar()" x-transition.opacity></div>
