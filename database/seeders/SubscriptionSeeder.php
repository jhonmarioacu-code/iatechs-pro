<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Domains\Companies\Models\Company;
use App\Domains\Plans\Models\Plan;
use App\Domains\Subscriptions\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $plan = Plan::where(
            'slug',
            'professional'
        )->first();

        if (!$plan) {
            return;
        }

        $companies = Company::all();

        foreach ($companies as $company) {

            Subscription::updateOrCreate(

                [
                    'company_id' => $company->id
                ],

                [
                    'uuid' => (string) Str::uuid(),

                    'plan_id' => $plan->id,

                    'billing_cycle' => 'monthly',

                    'amount' => $plan->monthly_price,

                    'starts_at' => Carbon::today(),

                    'ends_at' => Carbon::today()
                        ->addMonth(),

                    'trial_ends_at' => null,

                    'status' => 'active',

                    'payment_provider' => null,

                    'external_subscription_id' => null,

                    'cancelled_at' => null,
                ]
            );
        }
    }
}