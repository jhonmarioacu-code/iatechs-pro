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
        <button class="icon-button lg-hidden" type="button" @click="toggleSidebar()" aria-label="Cerrar menú lateral">
            ✕
        </button>
    </div>

    <nav class="sidebar-nav" aria-label="Menú principal">
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

                $isCompanyCrudModule = $portal === 'company'
                    && in_array($item['slug'], ['customers', 'tickets', 'invoices'], true);
                $isAdminObservability = $portal === 'admin'
                    && $item['slug'] === 'observability';
                $isAdminOperations = $portal === 'admin'
                    && $item['slug'] === 'operations';
                $isCustomerTickets = $portal === 'customer'
                    && $item['slug'] === 'tickets';
                $isCustomerInvoices = $portal === 'customer'
                    && $item['slug'] === 'invoices';
                $isCustomerMarketplace = $portal === 'customer'
                    && $item['slug'] === 'marketplace';

                if ($item['slug'] === 'dashboard') {
                    $href = route('portal.'.$portal.'.dashboard');
                } elseif ($isAdminObservability) {
                    $href = route('portal.admin.observability');
                } elseif ($isAdminOperations) {
                    $href = route('portal.admin.operations');
                } elseif ($isCustomerTickets) {
                    $href = route('portal.customer.tickets.index');
                } elseif ($isCustomerInvoices) {
                    $href = route('portal.customer.invoices.index');
                } elseif ($isCustomerMarketplace) {
                    $href = route('portal.customer.marketplace');
                } elseif ($isCompanyCrudModule) {
                    $href = route('portal.company.module.index', ['module' => $item['slug']]);
                } else {
                    $href = route('portal.module', ['portal' => $portal, 'module' => $item['slug']]);
                }
                $isActive = request()->url() === $href;
            @endphp
            <a href="{{ $href }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>

<div class="sidebar-backdrop" x-show="sidebarOpen" @click="toggleSidebar()" x-transition.opacity></div>
