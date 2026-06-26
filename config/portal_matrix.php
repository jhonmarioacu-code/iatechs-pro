<?php

declare(strict_types=1);

return [
    'portals' => [
        'admin' => [
            'roles' => [],
            'modules' => [
                'dashboard' => ['label' => 'Dashboard', 'permission' => 'analytics.view', 'icon' => 'DB', 'route_name' => 'portal.{portal}.dashboard', 'state' => 'active'],
                'dashboards' => ['label' => 'Dashboards', 'permission' => 'analytics.view', 'icon' => 'BI', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'customers' => ['label' => 'Clientes', 'permission' => 'customers.view', 'icon' => 'CU', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'crm' => ['label' => 'CRM', 'permission' => 'crm.view', 'icon' => 'CM', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'marketplace' => ['label' => 'Marketplace', 'permission' => 'products.view', 'icon' => 'MK', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'service-desk' => ['label' => 'Service Desk', 'permission' => 'tickets.view', 'icon' => 'SD', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'inventory' => ['label' => 'Inventory', 'permission' => 'products.view', 'icon' => 'IV', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'accounting' => ['label' => 'Accounting', 'permission' => 'reports.view', 'icon' => 'AC', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'knowledge-base' => ['label' => 'Knowledge Base', 'permission' => 'knowledge.view', 'icon' => 'KB', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'ai-assistant' => ['label' => 'AI Assistant', 'permission' => 'ai.use', 'icon' => 'AI', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'reports' => ['label' => 'Reports', 'permission' => 'reports.view', 'icon' => 'RP', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'observability' => ['label' => 'Observability', 'permission' => 'analytics.view', 'icon' => 'OB', 'route_name' => 'portal.admin.observability', 'state' => 'active'],
                'operations' => ['label' => 'Operations', 'permission' => 'companies.create', 'icon' => 'OP', 'route_name' => 'portal.admin.operations', 'state' => 'active'],
                'settings' => ['label' => 'Settings', 'permission' => 'system-settings.view', 'icon' => 'ST', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
            ],
        ],
        'company' => [
            'roles' => ['owner', 'administrator', 'manager', 'receptionist', 'warehouse', 'accountant'],
            'modules' => [
                'dashboard' => ['label' => 'Dashboard', 'permission' => '', 'icon' => 'DB', 'route_name' => 'portal.{portal}.dashboard', 'state' => 'active'],
                'customers' => ['label' => 'Customers', 'permission' => 'customers.view', 'icon' => 'CU', 'route_name' => 'portal.company.module.index', 'route_params' => ['module' => '{module}'], 'state' => 'active'],
                'devices' => ['label' => 'Devices', 'permission' => 'devices.view', 'icon' => 'DV', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'tickets' => ['label' => 'Tickets', 'permission' => 'tickets.view', 'icon' => 'TK', 'route_name' => 'portal.company.module.index', 'route_params' => ['module' => '{module}'], 'state' => 'active'],
                'ai-assistant' => ['label' => 'AI Assistant', 'permission' => 'ai.use', 'icon' => 'AI', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'products' => ['label' => 'Products', 'permission' => 'products.view', 'icon' => 'PR', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'invoices' => ['label' => 'Invoices', 'permission' => 'invoices.view', 'icon' => 'IN', 'route_name' => 'portal.company.module.index', 'route_params' => ['module' => '{module}'], 'state' => 'active'],
                'payments' => ['label' => 'Payments', 'permission' => 'payments.view', 'icon' => 'PY', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'analytics' => ['label' => 'Analytics', 'permission' => 'analytics.view', 'icon' => 'AN', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'settings' => ['label' => 'Settings', 'permission' => 'system-settings.view', 'icon' => 'ST', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
            ],
        ],
        'technician' => [
            'roles' => ['technician'],
            'modules' => [
                'dashboard' => ['label' => 'Dashboard', 'permission' => '', 'icon' => 'DB', 'route_name' => 'portal.{portal}.dashboard', 'state' => 'active'],
                'tickets' => ['label' => 'Tickets', 'permission' => 'tickets.view', 'icon' => 'TK', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'diagnostics' => ['label' => 'Diagnostics', 'permission' => 'diagnostics.view', 'icon' => 'DG', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'repairs' => ['label' => 'Repairs', 'permission' => 'repairs.view', 'icon' => 'RE', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'ai-assistant' => ['label' => 'AI Assistant', 'permission' => 'ai.use', 'icon' => 'AI', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'work-orders' => ['label' => 'Work Orders', 'permission' => 'work-orders.view', 'icon' => 'WO', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
                'assigned-inventory' => ['label' => 'Assigned Inventory', 'permission' => 'inventory.view', 'icon' => 'IV', 'route_name' => 'portal.module', 'route_params' => ['portal' => '{portal}', 'module' => '{module}'], 'state' => 'active'],
            ],
        ],
        'customer' => [
            'roles' => ['customer'],
            'modules' => [
                'dashboard' => ['label' => 'Dashboard', 'permission' => 'customer.portal.view', 'icon' => 'DB', 'route_name' => 'portal.{portal}.dashboard', 'state' => 'active'],
                'tickets' => ['label' => 'My Tickets', 'permission' => 'customer.portal.tickets.view', 'icon' => 'TK', 'route_name' => 'portal.customer.tickets.index', 'state' => 'active'],
                'invoices' => ['label' => 'My Invoices', 'permission' => 'customer.portal.invoices.view', 'icon' => 'IN', 'route_name' => 'portal.customer.invoices.index', 'state' => 'active'],
                'marketplace' => ['label' => 'Marketplace', 'permission' => 'customer.portal.marketplace.view', 'icon' => 'MK', 'route_name' => 'portal.customer.marketplace', 'state' => 'active'],
            ],
        ],
    ],
];
