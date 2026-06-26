<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Companies
            |--------------------------------------------------------------------------
            */
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.delete',
            'companies.activate',
            'companies.suspend',
            'companies.restore',
            'companies.force-delete',

            /*
            |--------------------------------------------------------------------------
            | Branches
            |--------------------------------------------------------------------------
            */
            'branches.view',
            'branches.create',
            'branches.update',
            'branches.delete',

            /*
            |--------------------------------------------------------------------------
            | Plans
            |--------------------------------------------------------------------------
            */
            'plans.view',
            'plans.create',
            'plans.update',
            'plans.delete',
            'plans.activate',
            'plans.deactivate',

            /*
            |--------------------------------------------------------------------------
            | Subscriptions
            |--------------------------------------------------------------------------
            */
            'subscriptions.view',
            'subscriptions.create',
            'subscriptions.update',
            'subscriptions.cancel',
            'subscriptions.delete',
            'subscriptions.activate',
            'subscriptions.renew',
            'subscriptions.change-plan',

            /*
            |--------------------------------------------------------------------------
            | Billings
            |--------------------------------------------------------------------------
            */
            'billings.view',
            'billings.create',
            'billings.update',
            'billings.delete',
            'billings.mark-paid',
            'billings.mark-failed',
            'billings.cancel',
            'billings.refund',

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            /*
            |--------------------------------------------------------------------------
            | Roles & Permissions
            |--------------------------------------------------------------------------
            */
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            /*
            |--------------------------------------------------------------------------
            | CRM
            |--------------------------------------------------------------------------
            */
            'crm.view',
            'crm.create',
            'crm.update',
            'crm.delete',
            'crm.convert',
            'crm.win',
            'crm.lose',

            /*
            |--------------------------------------------------------------------------
            | Customers
            |--------------------------------------------------------------------------
            */
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',

            /*
            |--------------------------------------------------------------------------
            | Devices
            |--------------------------------------------------------------------------
            */
            'devices.view',
            'devices.create',
            'devices.update',
            'devices.delete',

            /*
            |--------------------------------------------------------------------------
            | Tickets
            |--------------------------------------------------------------------------
            */
            'tickets.view',
            'tickets.create',
            'tickets.update',
            'tickets.delete',
            'tickets.assign',
            'tickets.close',
            'tickets.cancel',

            /*
            |--------------------------------------------------------------------------
            | Diagnostics
            |--------------------------------------------------------------------------
            */
            'diagnostics.view',
            'diagnostics.create',
            'diagnostics.update',
            'diagnostics.delete',
            'diagnostics.start',
            'diagnostics.complete',
            'diagnostics.cancel',

            /*
            |--------------------------------------------------------------------------
            | Quotes
            |--------------------------------------------------------------------------
            */
            'quotes.view',
            'quotes.create',
            'quotes.update',
            'quotes.delete',
            'quotes.approve',
            'quotes.reject',
            'quotes.cancel',

            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */
            'notifications.view',
            'notifications.create',
            'notifications.update',
            'notifications.delete',
            'notifications.read',

            /*
            |--------------------------------------------------------------------------
            | Repairs
            |--------------------------------------------------------------------------
            */
            'repairs.view',
            'repairs.create',
            'repairs.update',
            'repairs.delete',
            'repairs.assign',
            'repairs.start',
            'repairs.complete',
            'repairs.deliver',
            'repairs.cancel',

            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */
            'inventory.view',
            'inventory.create',
            'inventory.update',
            'inventory.delete',
            'inventory.approve',
            'inventory.complete',
            'inventory.cancel',

            /*
            |--------------------------------------------------------------------------
            | Suppliers
            |--------------------------------------------------------------------------
            */
            'suppliers.view',
            'suppliers.create',
            'suppliers.update',
            'suppliers.delete',

            /*
            |--------------------------------------------------------------------------
            | Purchase Orders
            |--------------------------------------------------------------------------
            */
            'purchase-orders.view',
            'purchase-orders.create',
            'purchase-orders.update',
            'purchase-orders.approve',
            'purchase-orders.cancel',

            /*
            |--------------------------------------------------------------------------
            | Goods Receipts
            |--------------------------------------------------------------------------
            */
            'goods-receipts.view',
            'goods-receipts.create',
            'goods-receipts.update',

            /*
            |--------------------------------------------------------------------------
            | Accounting
            |--------------------------------------------------------------------------
            */
            'accounting.view',
            'accounting.create',
            'accounting.update',
            'accounting.post',
            'accounting.cancel',

            /*
            |--------------------------------------------------------------------------
            | Invoices
            |--------------------------------------------------------------------------
            */
            'invoices.view',
            'invoices.create',
            'invoices.update',
            'invoices.delete',
            'invoices.issue',
            'invoices.mark_paid',
            'invoices.mark_overdue',
            'invoices.cancel',
            'invoices.refund',
            'invoices.restore',
            'invoices.force_delete',

            /*
            |--------------------------------------------------------------------------
            | Payments
            |--------------------------------------------------------------------------
            */
            'payments.view',
            'payments.create',
            'payments.update',
            'payments.delete',
            'payments.complete',
            'payments.cancel',
            'payments.refund',
            'payments.restore',
            'payments.force_delete',

            /*
            |--------------------------------------------------------------------------
            | Warranties
            |--------------------------------------------------------------------------
            */
            'warranties.view',
            'warranties.create',
            'warranties.update',
            'warranties.delete',
            'warranties.claim',
            'warranties.expire',
            'warranties.void',

            /*
            |--------------------------------------------------------------------------
            | Customer Portal
            |--------------------------------------------------------------------------
            */
            'customer.portal.view',
            'customer.portal.tickets.view',
            'customer.portal.invoices.view',
            'customer.portal.pay',
            'customer.portal.marketplace.view',

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            'reports.view',
            'reports.export',

            /*
            |--------------------------------------------------------------------------
            | Dashboards
            |--------------------------------------------------------------------------
            */
            'dashboards.view',
            'dashboards.create',
            'dashboards.update',
            'dashboards.delete',

            /*
            |--------------------------------------------------------------------------
            | Analytics
            |--------------------------------------------------------------------------
            */
            'analytics.view',

            /*
            |--------------------------------------------------------------------------
            | Audit Logs
            |--------------------------------------------------------------------------
            */
            'auditlogs.view',

            /*
            |--------------------------------------------------------------------------
            | File Manager
            |--------------------------------------------------------------------------
            */
            'files.view',
            'files.upload',
            'files.delete',

            /*
            |--------------------------------------------------------------------------
            | AI Assistant
            |--------------------------------------------------------------------------
            */
            'ai.view',
            'ai.use',

            /*
            |--------------------------------------------------------------------------
            | Knowledge Base
            |--------------------------------------------------------------------------
            */
            'knowledge.view',
            'knowledge.create',
            'knowledge.update',
            'knowledge.delete',

            /*
            |--------------------------------------------------------------------------
            | Enterprise Modules
            |--------------------------------------------------------------------------
            */
            'purchases.view',
            'purchases.create',
            'purchases.update',
            'purchases.delete',

            'service-contracts.view',
            'service-contracts.create',
            'service-contracts.update',
            'service-contracts.delete',

            'work-orders.view',
            'work-orders.create',
            'work-orders.update',
            'work-orders.delete',

            'technician-schedules.view',
            'technician-schedules.create',
            'technician-schedules.update',
            'technician-schedules.delete',

            'analytics.create',
            'analytics.update',
            'analytics.delete',

            'file-manager.view',
            'file-manager.create',
            'file-manager.update',
            'file-manager.delete',

            'system-settings.view',
            'system-settings.create',
            'system-settings.update',
            'system-settings.delete',

            'human-resources.view',
            'human-resources.create',
            'human-resources.update',
            'human-resources.delete',

            'projects.view',
            'projects.create',
            'projects.update',
            'projects.delete',

            'assets.view',
            'assets.create',
            'assets.update',
            'assets.delete',

            'procurement.view',
            'procurement.create',
            'procurement.update',
            'procurement.delete',

            'document-management.view',
            'document-management.create',
            'document-management.update',
            'document-management.delete',

            'compliance.view',
            'compliance.create',
            'compliance.update',
            'compliance.delete',

            'business-intelligence.view',
            'business-intelligence.create',
            'business-intelligence.update',
            'business-intelligence.delete',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
