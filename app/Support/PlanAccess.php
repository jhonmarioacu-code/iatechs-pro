<?php

declare(strict_types=1);

namespace App\Support;

use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Users\Models\User;

final class PlanAccess
{
    /**
     * @return array<int, string>
     */
    private const INVENTORY_MODULES = [
        'inventory',
        'products',
        'suppliers',
        'purchases',
        'purchase-orders',
        'goods-receipts',
        'stock-transfers',
        'procurement',
        'assets',
    ];

    /**
     * @return array<int, string>
     */
    private const REPORT_MODULES = [
        'reports',
        'analytics',
        'business-intelligence',
    ];

    public static function hasValidSubscription(User $user): bool
    {
        $subscription = self::resolveActiveSubscription($user);

        if (!$subscription) {
            return false;
        }

        if ($subscription->status === 'trial') {
            if ($subscription->trial_ends_at !== null && $subscription->trial_ends_at->isPast()) {
                return false;
            }
        }

        return true;
    }

    public static function canUseCompanyModule(User $user, string $module): bool
    {
        $isInventoryModule = in_array($module, self::INVENTORY_MODULES, true);
        $isReportModule = in_array($module, self::REPORT_MODULES, true);
        $isAiModule = $module === 'ai-assistant';

        // Core modules stay accessible even without an active subscription so users
        // can still operate baseline flows (for example dashboard/customers/tickets).
        if (!$isInventoryModule && !$isReportModule && !$isAiModule) {
            return true;
        }

        $plan = self::resolvePlan($user);

        if (!$plan) {
            return false;
        }

        if ($isInventoryModule) {
            return $plan->hasInventory();
        }

        if ($isReportModule) {
            return $plan->hasReports();
        }

        return $plan->hasAI();
    }

    public static function hasModuleAccessByPlan(User $user, string $portal, string $module): bool
    {
        if ($portal !== 'company') {
            return true;
        }

        return self::canUseCompanyModule($user, $module);
    }

    private static function resolvePlan(User $user): ?Plan
    {
        $subscription = self::resolveActiveSubscription($user);

        if (!$subscription) {
            return null;
        }

        return Plan::query()->find($subscription->plan_id);
    }

    private static function resolveActiveSubscription(User $user): ?Subscription
    {
        if ($user->hasRole('super_admin') || !$user->company_id) {
            return null;
        }

        return Subscription::query()
            ->with('plan')
            ->where('company_id', $user->company_id)
            ->whereIn('status', ['trial', 'active'])
            ->whereDate('ends_at', '>=', today())
            ->latest('id')
            ->first();
    }
}
