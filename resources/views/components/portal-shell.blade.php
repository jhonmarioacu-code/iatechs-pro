@props([
    'portal' => 'admin',
    'title' => 'Portal',
    'subtitle' => '',
    'kpis' => [],
])

@php
    /** @var \App\Domains\Users\Models\User|null $authUser */
    $authUser = auth()->user();

    $menu = match ($portal) {
        'admin' => [
            ['label' => 'Dashboard', 'slug' => 'dashboard', 'permission' => 'analytics.view'],
            ['label' => 'Dashboards', 'slug' => 'dashboards', 'permission' => 'analytics.view'],
            ['label' => 'Clientes', 'slug' => 'customers', 'permission' => 'customers.view'],
            ['label' => 'CRM', 'slug' => 'crm', 'permission' => 'crm.view'],
            ['label' => 'Marketplace', 'slug' => 'marketplace', 'permission' => 'products.view'],
            ['label' => 'Service Desk', 'slug' => 'service-desk', 'permission' => 'tickets.view'],
            ['label' => 'Inventory', 'slug' => 'inventory', 'permission' => 'products.view'],
            ['label' => 'Accounting', 'slug' => 'accounting', 'permission' => 'reports.view'],
            ['label' => 'Knowledge Base', 'slug' => 'knowledge-base', 'permission' => 'knowledge.view'],
            ['label' => 'AI Assistant', 'slug' => 'ai-assistant', 'permission' => 'ai.use'],
            ['label' => 'Reports', 'slug' => 'reports', 'permission' => 'reports.view'],
            ['label' => 'Observability', 'slug' => 'observability', 'permission' => 'analytics.view'],
            ['label' => 'Operations', 'slug' => 'operations', 'permission' => 'companies.create'],
            ['label' => 'Settings', 'slug' => 'settings', 'permission' => 'system-settings.view'],
        ],
        'company' => [
            ['label' => 'Dashboard', 'slug' => 'dashboard', 'permission' => 'analytics.view'],
            ['label' => 'Customers', 'slug' => 'customers', 'permission' => 'customers.view'],
            ['label' => 'Devices', 'slug' => 'devices', 'permission' => 'devices.view'],
            ['label' => 'Tickets', 'slug' => 'tickets', 'permission' => 'tickets.view'],
            ['label' => 'AI Assistant', 'slug' => 'ai-assistant', 'permission' => 'ai.use'],
            ['label' => 'Products', 'slug' => 'products', 'permission' => 'products.view'],
            ['label' => 'Invoices', 'slug' => 'invoices', 'permission' => 'invoices.view'],
            ['label' => 'Payments', 'slug' => 'payments', 'permission' => 'payments.view'],
            ['label' => 'Analytics', 'slug' => 'analytics', 'permission' => 'analytics.view'],
            ['label' => 'Settings', 'slug' => 'settings', 'permission' => 'system-settings.view'],
        ],
        'technician' => [
            ['label' => 'Dashboard', 'slug' => 'dashboard', 'permission' => 'analytics.view'],
            ['label' => 'Tickets', 'slug' => 'tickets', 'permission' => 'tickets.view'],
            ['label' => 'Diagnostics', 'slug' => 'diagnostics', 'permission' => 'diagnostics.view'],
            ['label' => 'Repairs', 'slug' => 'repairs', 'permission' => 'repairs.view'],
            ['label' => 'AI Assistant', 'slug' => 'ai-assistant', 'permission' => 'ai.use'],
            ['label' => 'Work Orders', 'slug' => 'work-orders', 'permission' => 'work-orders.view'],
            ['label' => 'Assigned Inventory', 'slug' => 'assigned-inventory', 'permission' => 'inventory.view'],
        ],
        'customer' => [
            ['label' => 'Dashboard', 'slug' => 'dashboard', 'permission' => 'customer.portal.view'],
            ['label' => 'My Tickets', 'slug' => 'tickets', 'permission' => 'customer.portal.tickets.view'],
            ['label' => 'My Invoices', 'slug' => 'invoices', 'permission' => 'customer.portal.invoices.view'],
            ['label' => 'Marketplace', 'slug' => 'marketplace', 'permission' => 'customer.portal.marketplace.view'],
        ],
        default => [],
    };

    if ($portal === 'company' && $authUser !== null && !$authUser->hasRole('super_admin')) {
        $menu = array_values(array_filter($menu, static function (array $item) use ($authUser): bool {
            return \App\Support\PlanAccess::canUseCompanyModule($authUser, $item['slug']);
        }));
    }

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
