<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Domains\Plans\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [

            [
                'name' => 'Free',
                'slug' => 'free',
                'monthly_price' => 0,
                'yearly_price' => 0,

                'max_users' => 1,
                'max_branches' => 1,
                'max_storage_gb' => 1,
                'max_tickets' => 20,

                'ai_requests_limit' => 50,

                'trial_days' => 0,

                'has_ai' => true,
                'has_inventory' => false,
                'has_reports' => false,

                'status' => 'active',
            ],

            [
                'name' => 'Starter',
                'slug' => 'starter',
                'monthly_price' => 19,
                'yearly_price' => 190,

                'max_users' => 3,
                'max_branches' => 1,
                'max_storage_gb' => 5,
                'max_tickets' => 100,

                'ai_requests_limit' => 500,

                'trial_days' => 14,

                'has_ai' => false,
                'has_inventory' => true,
                'has_reports' => false,

                'status' => 'active',
            ],

            [
                'name' => 'Professional',
                'slug' => 'professional',
                'monthly_price' => 49,
                'yearly_price' => 490,

                'max_users' => 10,
                'max_branches' => 3,
                'max_storage_gb' => 25,
                'max_tickets' => 1000,

                'ai_requests_limit' => 5000,

                'trial_days' => 14,

                'has_ai' => true,
                'has_inventory' => true,
                'has_reports' => true,

                'status' => 'active',
            ],

            [
                'name' => 'Business',
                'slug' => 'business',
                'monthly_price' => 99,
                'yearly_price' => 990,

                'max_users' => 50,
                'max_branches' => 10,
                'max_storage_gb' => 100,
                'max_tickets' => 10000,

                'ai_requests_limit' => 20000,

                'trial_days' => 30,

                'has_ai' => true,
                'has_inventory' => true,
                'has_reports' => true,

                'status' => 'active',
            ],

            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'monthly_price' => 299,
                'yearly_price' => 2990,

                'max_users' => 9999,
                'max_branches' => 9999,
                'max_storage_gb' => 1000,
                'max_tickets' => 999999,

                'ai_requests_limit' => 999999,

                'trial_days' => 30,

                'has_ai' => true,
                'has_inventory' => true,
                'has_reports' => true,

                'status' => 'active',
            ],
        ];

        foreach ($plans as $plan) {

            Plan::updateOrCreate(

                [
                    'slug' => $plan['slug']
                ],

                array_merge(
                    [
                        'uuid' => (string) Str::uuid()
                    ],
                    $plan
                )
            );
        }
    }
}