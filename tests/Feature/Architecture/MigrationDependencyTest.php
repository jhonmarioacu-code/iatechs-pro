<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function iatechsMigrationReferencedTable(string $column, ?string $explicitTable): string
{
    if ($explicitTable !== null && $explicitTable !== '') {
        return $explicitTable;
    }

    $known = [
        'ai_provider_id' => 'ai_providers',
        'article_id' => 'knowledge_articles',
        'billing_id' => 'billings',
        'branch_id' => 'branches',
        'category_id' => 'knowledge_categories',
        'company_id' => 'companies',
        'conversation_id' => 'ai_conversations',
        'customer_id' => 'customers',
        'dashboard_id' => 'dashboards',
        'device_id' => 'devices',
        'diagnostic_id' => 'diagnostics',
        'goods_receipt_id' => 'goods_receipts',
        'invoice_id' => 'invoices',
        'lead_id' => 'crm_leads',
        'opportunity_id' => 'crm_opportunities',
        'parent_id' => 'accounts',
        'payment_id' => 'payments',
        'plan_id' => 'plans',
        'product_id' => 'products',
        'purchase_order_id' => 'purchase_orders',
        'purchase_order_item_id' => 'purchase_order_items',
        'quote_id' => 'quotes',
        'repair_id' => 'repairs',
        'report_id' => 'reports',
        'role_id' => 'roles',
        'subscription_id' => 'subscriptions',
        'supplier_id' => 'suppliers',
        'technician_id' => 'users',
        'ticket_id' => 'tickets',
        'user_id' => 'users',
    ];

    return $known[$column] ?? Str::plural(Str::beforeLast($column, '_id'));
}

it('creates referenced tables before inline foreign keys are declared', function () {
    $createdTables = [];
    $missing = [];

    $migrations = collect(File::files(database_path('migrations')))
        ->sortBy(fn (SplFileInfo $file) => $file->getFilename())
        ->values();

    foreach ($migrations as $migration) {
        $contents = File::get($migration->getPathname());

        preg_match_all("/Schema::create\\('([^']+)'/", $contents, $createMatches);
        $tablesCreatedHere = $createMatches[1];

        preg_match_all(
            "/foreignId\\('([^']+)'\\)(?:(?!foreignId\\().)*?->constrained\\((?:'([^']+)')?\\)/s",
            $contents,
            $foreignMatches,
            PREG_SET_ORDER
        );

        foreach ($foreignMatches as $match) {
            $referencedTable = iatechsMigrationReferencedTable(
                $match[1],
                $match[2] ?? null
            );

            if (! in_array($referencedTable, $createdTables, true)
                && ! in_array($referencedTable, $tablesCreatedHere, true)) {
                $missing[] = sprintf(
                    '%s references %s before it is created',
                    $migration->getFilename(),
                    $referencedTable
                );
            }
        }

        foreach ($tablesCreatedHere as $table) {
            $createdTables[] = $table;
        }
    }

    expect($missing)->toBe([]);
});
