<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/*
|--------------------------------------------------------------------------
| Policies
|--------------------------------------------------------------------------
*/

/* Companies */
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Policies\CompanyPolicy;

/*Plans */
use App\Domains\Plans\Models\Plan;
use App\Domains\Plans\Policies\PlanPolicy;

/*subscriptions */
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Subscriptions\Policies\SubscriptionPolicy;

/* Billing */
use App\Domains\Billing\Models\Billing;
use App\Domains\Billing\Policies\BillingPolicy;

/* Core Operations */
use App\Domains\Users\Models\User;
use App\Domains\Users\Policies\UserPolicy;
use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Policies\CustomerPolicy;
use App\Domains\Devices\Models\Device;
use App\Domains\Devices\Policies\DevicePolicy;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Tickets\Policies\TicketPolicy;
use App\Domains\Diagnostics\Models\Diagnostic;
use App\Domains\Diagnostics\Policies\DiagnosticPolicy;
use App\Domains\Repairs\Models\Repair;
use App\Domains\Repairs\Policies\RepairPolicy;
use App\Domains\Quotes\Models\Quote;
use App\Domains\Quotes\Policies\QuotePolicy;

/* Products */
use App\Domains\Products\Models\Product;
use App\Domains\Products\Policies\ProductPolicy;

/* Inventory */
use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Inventory\Policies\InventoryPolicy;

use App\Domains\Inventory\Models\StockTransfer;
use App\Domains\Inventory\Policies\StockTransferPolicy;

/* Suppliers */
use App\Domains\Suppliers\Models\Supplier;
use App\Domains\Suppliers\Policies\SupplierPolicy;

/* Purchase Orders */
use App\Domains\PurchaseOrders\Models\PurchaseOrder;
use App\Domains\PurchaseOrders\Policies\PurchaseOrderPolicy;

/* Goods Receipts */
use App\Domains\GoodsReceipts\Models\GoodsReceipt;
use App\Domains\GoodsReceipts\Policies\GoodsReceiptPolicy;

/* Audit Logs */
use App\Domains\AuditLogs\Models\AuditLog;
use App\Domains\AuditLogs\Policies\AuditLogPolicy;

/* Notifications */
use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Policies\NotificationPolicy;

/* Reports */
use App\Domains\Reports\Models\Report;
use App\Domains\Reports\Policies\ReportPolicy;

/* Dashboards */
use App\Domains\Dashboard\Models\Dashboard;
use App\Domains\Dashboard\Policies\DashboardPolicy;

/* AI Assistant */
use App\Domains\AIAssistant\Models\AIConversation;
use App\Domains\AIAssistant\Policies\AIAssistantPolicy;

/* Knowledge Base */
use App\Domains\KnowledgeBase\Models\KnowledgeArticle;
use App\Domains\KnowledgeBase\Policies\KnowledgeBasePolicy;

/* CRM */
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\Opportunity;
use App\Domains\CRM\Policies\CRMPolicy;

/* Accounting */
use App\Domains\Accounting\Models\Account;
use App\Domains\Accounting\Models\JournalEntry;
use App\Domains\Accounting\Policies\AccountingPolicy;

/* Enterprise Modules */
use App\Domains\Purchases\Models\Purchase;
use App\Domains\Purchases\Policies\PurchasePolicy;
use App\Domains\ServiceContracts\Models\ServiceContract;
use App\Domains\ServiceContracts\Policies\ServiceContractPolicy;
use App\Domains\WorkOrders\Models\WorkOrder;
use App\Domains\WorkOrders\Policies\WorkOrderPolicy;
use App\Domains\TechnicianSchedules\Models\TechnicianSchedule;
use App\Domains\TechnicianSchedules\Policies\TechnicianSchedulePolicy;
use App\Domains\Analytics\Models\Analytic;
use App\Domains\Analytics\Policies\AnalyticPolicy;
use App\Domains\FileManager\Models\FileManager;
use App\Domains\FileManager\Policies\FileManagerPolicy;
use App\Domains\SystemSettings\Models\SystemSetting;
use App\Domains\SystemSettings\Policies\SystemSettingPolicy;
use App\Domains\HumanResources\Models\HumanResource;
use App\Domains\HumanResources\Policies\HumanResourcePolicy;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Policies\ProjectPolicy;
use App\Domains\Assets\Models\Asset;
use App\Domains\Assets\Policies\AssetPolicy;
use App\Domains\Procurement\Models\Procurement;
use App\Domains\Procurement\Policies\ProcurementPolicy;
use App\Domains\DocumentManagement\Models\ManagedDocument;
use App\Domains\DocumentManagement\Policies\ManagedDocumentPolicy;
use App\Domains\Compliance\Models\ComplianceRecord;
use App\Domains\Compliance\Policies\ComplianceRecordPolicy;
use App\Domains\BusinessIntelligence\Models\BusinessMetric;
use App\Domains\BusinessIntelligence\Policies\BusinessMetricPolicy;
use App\Domains\RolesPermissions\Policies\PermissionPolicy;
use App\Domains\RolesPermissions\Policies\RolePolicy;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [

        Company::class => CompanyPolicy::class,

        Plan::class => PlanPolicy::class,

        Subscription::class => SubscriptionPolicy::class,

        Billing::class => BillingPolicy::class,

        User::class => UserPolicy::class,
        Customer::class => CustomerPolicy::class,
        Device::class => DevicePolicy::class,
        Ticket::class => TicketPolicy::class,
        Diagnostic::class => DiagnosticPolicy::class,
        Repair::class => RepairPolicy::class,
        Quote::class => QuotePolicy::class,

        Product::class => ProductPolicy::class,

        InventoryMovement::class => InventoryPolicy::class,
        StockTransfer::class => StockTransferPolicy::class,

        Supplier::class => SupplierPolicy::class,

        PurchaseOrder::class => PurchaseOrderPolicy::class,

        GoodsReceipt::class => GoodsReceiptPolicy::class,

        AuditLog::class => AuditLogPolicy::class,

        Notification::class => NotificationPolicy::class,

        Report::class => ReportPolicy::class,

        Dashboard::class => DashboardPolicy::class,

        AIConversation::class => AIAssistantPolicy::class,

        KnowledgeArticle::class => KnowledgeBasePolicy::class,

        Lead::class => CRMPolicy::class,
        Opportunity::class => CRMPolicy::class,

        Account::class => AccountingPolicy::class,
        JournalEntry::class => AccountingPolicy::class,

        Purchase::class => PurchasePolicy::class,
        ServiceContract::class => ServiceContractPolicy::class,
        WorkOrder::class => WorkOrderPolicy::class,
        TechnicianSchedule::class => TechnicianSchedulePolicy::class,
        Analytic::class => AnalyticPolicy::class,
        FileManager::class => FileManagerPolicy::class,
        SystemSetting::class => SystemSettingPolicy::class,
        HumanResource::class => HumanResourcePolicy::class,
        Project::class => ProjectPolicy::class,
        Asset::class => AssetPolicy::class,
        Procurement::class => ProcurementPolicy::class,
        ManagedDocument::class => ManagedDocumentPolicy::class,
        ComplianceRecord::class => ComplianceRecordPolicy::class,
        BusinessMetric::class => BusinessMetricPolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, string $ability) {

            if ($user->hasRole('super_admin')) {
                return true;
            }

            return null;
        });
    }
}
