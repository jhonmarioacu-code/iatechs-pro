@props([
    'portal' => 'admin',
    'title' => 'Portal',
    'subtitle' => '',
    'kpis' => [],
])

@php
    /** @var \App\Domains\Users\Models\User|null $authUser */
    $authUser = auth()->user();

    $menu = $authUser !== null
        ? \App\Support\PortalMatrix::menuForPortal($authUser, $portal)
        : [];

    $assistantEnabled = $authUser !== null
        && $authUser->can('ai.use')
        && in_array($portal, ['admin', 'company', 'technician'], true);

    if ($assistantEnabled && $portal === 'company' && $authUser !== null && !$authUser->hasRole('super_admin')) {
        $assistantEnabled = \App\Support\PlanAccess::canUseCompanyModule($authUser, 'ai-assistant');
    }
@endphp

<div
    class="portal-grid"
    data-assistant-enabled="{{ $assistantEnabled ? '1' : '0' }}"
    data-portal-theme="{{ $portal }}"
>
    <x-sidebar :portal="$portal" :menu="$menu" />

    <div class="portal-main">
        <x-topbar :portal="$portal" />

        <main class="portal-content">
            <header class="portal-header" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 80)">
                <div class="portal-header-grid">
                    <div class="portal-header-copy" :class="{ 'is-visible': mounted }">
                        <p class="portal-eyebrow">{{ strtoupper($portal) }}</p>
                        <h1 class="portal-title">{{ $title }}</h1>
                        @if ($subtitle !== '')
                            <p class="portal-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                    <div class="portal-header-meta" :class="{ 'is-visible': mounted }">
                        <span class="meta-pill">Tenant isolation</span>
                        <span class="meta-pill">RBAC active</span>
                        <span class="meta-pill">Audit ready</span>
                    </div>
                </div>
            </header>

            <section class="kpi-grid">
                @foreach ($kpis as $kpi)
                    <x-kpi-card
                        :label="$kpi['label']"
                        :value="$kpi['value']"
                        :trend="$kpi['trend']"
                    />
                @endforeach
            </section>

            {{ $slot }}
        </main>
    </div>
</div>

<x-floating-ai :portal="$portal" :enabled="$assistantEnabled" />
<x-notification-center />
