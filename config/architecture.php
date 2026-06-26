<?php

declare(strict_types=1);

return [
    'excluded_domains' => [
        'Shared',
    ],

    'required_layers' => [
        'Controllers',
        'Models',
        'Services',
        'Repositories',
        'Policies',
        'Requests',
        'Resources',
    ],

    'empty_layer_exceptions' => [
        'RolesPermissions' => [
            'Models',
        ],
    ],

    'tenant_trait_exceptions' => [
        App\Domains\Accounting\Models\JournalEntryLine::class,
        App\Domains\AIAssistant\Models\AIMessage::class,
        App\Domains\AIAssistant\Models\AIProvider::class,
        App\Domains\Companies\Models\Company::class,
        App\Domains\CRM\Models\Activity::class,
        App\Domains\CRM\Models\Note::class,
        App\Domains\CRM\Models\Opportunity::class,
        App\Domains\Dashboard\Models\DashboardWidget::class,
        App\Domains\GoodsReceipts\Models\GoodsReceiptItem::class,
        App\Domains\KnowledgeBase\Models\KnowledgeAttachment::class,
        App\Domains\Plans\Models\Plan::class,
        App\Domains\PurchaseOrders\Models\PurchaseOrderItem::class,
        App\Domains\Quotes\Models\QuoteItem::class,
        App\Domains\Reports\Models\ReportExport::class,
        App\Domains\Users\Models\User::class,
    ],

    'domains' => [
        'Accounting' => [
            'docs' => ['docs/modules-enterprise/33-Accounting.md'],
            'api_routes' => ['accounting.php'],
        ],
        'AIAssistant' => [
            'docs' => ['docs/modules/29-AI-Assistant.md', 'docs/architecture/06-AI-Architecture.md'],
            'api_routes' => [],
        ],
        'Analytics' => [
            'docs' => ['docs/modules/26-Analytics.md'],
            'api_routes' => ['analytics.php'],
        ],
        'Assets' => [
            'docs' => ['docs/modules-enterprise/36-Assets.md'],
            'api_routes' => ['assets.php'],
        ],
        'AuditLogs' => [
            'docs' => ['docs/modules/27-AuditLogs.md'],
            'api_routes' => ['audit-logs.php'],
        ],
        'Billing' => [
            'docs' => ['docs/modules/02-Subscriptions.md'],
            'api_routes' => ['billings.php'],
        ],
        'Branches' => [
            'docs' => ['docs/modules/06-Branches.md'],
            'api_routes' => ['branches.php'],
        ],
        'BusinessIntelligence' => [
            'docs' => ['docs/modules-enterprise/40-BusinessIntelligence.md'],
            'api_routes' => ['business-intelligence.php'],
        ],
        'Companies' => [
            'docs' => ['docs/modules/01-Companies.md'],
            'api_routes' => ['companies.php'],
        ],
        'Compliance' => [
            'docs' => ['docs/modules-enterprise/39-Compliance.md'],
            'api_routes' => ['compliance.php'],
        ],
        'CRM' => [
            'docs' => ['docs/modules-enterprise/31-CRM.md'],
            'api_routes' => ['crm.php'],
        ],
        'Customers' => [
            'docs' => ['docs/modules/07-Customers.md'],
            'api_routes' => ['customers.php'],
        ],
        'Dashboard' => [
            'docs' => ['docs/architecture/12-Dashboard-Architecture.md'],
            'api_routes' => [],
        ],
        'Devices' => [
            'docs' => ['docs/modules/08-Devices.md'],
            'api_routes' => ['devices.php'],
        ],
        'Diagnostics' => [
            'docs' => ['docs/modules/10-Diagnostics.md'],
            'api_routes' => ['diagnostics.php'],
        ],
        'DocumentManagement' => [
            'docs' => ['docs/modules-enterprise/38-DocumentManagement.md'],
            'api_routes' => ['document-management.php'],
        ],
        'FileManager' => [
            'docs' => ['docs/modules/28-FileManager.md'],
            'api_routes' => ['file-manager.php'],
        ],
        'GoodsReceipts' => [
            'docs' => ['docs/modules/17-GoodsReceipts.md'],
            'api_routes' => ['goods-receipts.php'],
        ],
        'HumanResources' => [
            'docs' => ['docs/modules-enterprise/34-HumanResources.md'],
            'api_routes' => ['human-resources.php'],
        ],
        'Inventory' => [
            'docs' => ['docs/modules/13-Inventory.md'],
            'api_routes' => ['inventory.php'],
        ],
        'Invoices' => [
            'docs' => ['docs/modules/18-Invoices.md'],
            'api_routes' => ['invoices.php', 'invoice-items.php'],
        ],
        'KnowledgeBase' => [
            'docs' => ['docs/modules-enterprise/32-KnowledgeBase.md'],
            'api_routes' => ['knowledge-base.php'],
        ],
        'Notifications' => [
            'docs' => ['docs/modules/24-Notifications.md'],
            'api_routes' => ['notifications.php'],
        ],
        'Payments' => [
            'docs' => ['docs/modules/19-Payments.md'],
            'api_routes' => ['payments.php'],
        ],
        'Plans' => [
            'docs' => ['docs/modules/03-Plans.md'],
            'api_routes' => ['plans.php'],
        ],
        'Procurement' => [
            'docs' => ['docs/modules-enterprise/37-Procurement.md'],
            'api_routes' => ['procurement.php'],
        ],
        'Products' => [
            'docs' => ['docs/modules/13-Inventory.md'],
            'api_routes' => ['products.php'],
        ],
        'Projects' => [
            'docs' => ['docs/modules-enterprise/35-Projects.md'],
            'api_routes' => ['projects.php'],
        ],
        'PurchaseOrders' => [
            'docs' => ['docs/modules/16-PurchaseOrders.md'],
            'api_routes' => ['purchase-orders.php'],
        ],
        'Purchases' => [
            'docs' => ['docs/modules/15-Purchases.md'],
            'api_routes' => ['purchases.php'],
        ],
        'Quotes' => [
            'docs' => ['docs/modules/11-Quotes.md'],
            'api_routes' => ['quotes.php'],
        ],
        'Repairs' => [
            'docs' => ['docs/modules/12-Repairs.md'],
            'api_routes' => ['repairs.php'],
        ],
        'Reports' => [
            'docs' => ['docs/modules/25-Reports.md'],
            'api_routes' => ['reports.php'],
        ],
        'RolesPermissions' => [
            'docs' => ['docs/modules/05-Roles-Permissions.md'],
            'api_routes' => [],
        ],
        'ServiceContracts' => [
            'docs' => ['docs/modules/21-ServiceContracts.md'],
            'api_routes' => ['service-contracts.php'],
        ],
        'Subscriptions' => [
            'docs' => ['docs/modules/02-Subscriptions.md'],
            'api_routes' => ['subscriptions.php'],
        ],
        'Suppliers' => [
            'docs' => ['docs/modules/14-Suppliers.md'],
            'api_routes' => ['suppliers.php'],
        ],
        'SystemSettings' => [
            'docs' => ['docs/modules/30-SystemSettings.md'],
            'api_routes' => ['system-settings.php'],
        ],
        'TechnicianSchedules' => [
            'docs' => ['docs/modules/23-TechnicianSchedules.md'],
            'api_routes' => ['technician-schedules.php'],
        ],
        'Tickets' => [
            'docs' => ['docs/modules/09-Tickets.md'],
            'api_routes' => ['tickets.php'],
        ],
        'Users' => [
            'docs' => ['docs/modules/04-Users.md'],
            'api_routes' => ['users.php'],
        ],
        'Warranties' => [
            'docs' => ['docs/modules/20-Warranties.md'],
            'api_routes' => ['warranties.php'],
        ],
        'WorkOrders' => [
            'docs' => ['docs/modules/22-WorkOrders.md'],
            'api_routes' => ['work-orders.php'],
        ],
    ],
];
