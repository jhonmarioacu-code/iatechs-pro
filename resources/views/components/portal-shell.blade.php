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

    $realtimeEnabled = $authUser !== null && $authUser->can('notifications.view');
    $broadcastConnection = (string) config('broadcasting.default', 'log');
    $reverbConfig = (array) config('broadcasting.connections.reverb', []);
    $pusherConfig = (array) config('broadcasting.connections.pusher', []);
    $reverbOptions = (array) ($reverbConfig['options'] ?? []);
    $pusherOptions = (array) ($pusherConfig['options'] ?? []);
    $notificationIndexUrl = route('notifications.index');
    $notificationReadUrlTemplate = route('notifications.read', ['notification' => '__NOTIFICATION__']);
    $companyChannel = $authUser !== null ? 'company.'.$authUser->company_id.'.notifications' : '';
    $userChannel = $authUser !== null ? 'user.'.$authUser->id.'.notifications' : '';
@endphp

<div
    class="portal-grid"
    @portal-toggle-notifications.window="toggleNotifications()"
    @portal-close-notifications.window="notificationsOpen = false"
    @portal-toggle-assistant.window="toggleAssistantPanel()"
    @portal-close-assistant.window="assistantPanelOpen = false"
    data-assistant-enabled="{{ $assistantEnabled ? '1' : '0' }}"
    data-portal-theme="{{ $portal }}"
    data-realtime-enabled="{{ $realtimeEnabled ? '1' : '0' }}"
    data-broadcast-connection="{{ $broadcastConnection }}"
    data-reverb-key="{{ (string) ($reverbConfig['key'] ?? '') }}"
    data-reverb-host="{{ (string) ($reverbOptions['host'] ?? '') }}"
    data-reverb-port="{{ (string) ($reverbOptions['port'] ?? '') }}"
    data-reverb-scheme="{{ (string) ($reverbOptions['scheme'] ?? 'http') }}"
    data-pusher-key="{{ (string) ($pusherConfig['key'] ?? '') }}"
    data-pusher-host="{{ (string) ($pusherOptions['host'] ?? '') }}"
    data-pusher-port="{{ (string) ($pusherOptions['port'] ?? '') }}"
    data-pusher-scheme="{{ (string) ($pusherOptions['scheme'] ?? '') }}"
    data-pusher-cluster="{{ (string) ($pusherOptions['cluster'] ?? '') }}"
    data-notifications-url="{{ $notificationIndexUrl }}"
    data-notification-read-template="{{ $notificationReadUrlTemplate }}"
    data-company-channel="{{ $companyChannel }}"
    data-user-channel="{{ $userChannel }}"
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
