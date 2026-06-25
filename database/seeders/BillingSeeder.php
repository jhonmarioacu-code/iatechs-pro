<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Domains\Billing\Models\Billing;
use App\Domains\Subscriptions\Models\Subscription;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {

            Billing::updateOrCreate(

                [
                    'reference' => 'BILL-' . $subscription->id
                ],

                [
                    'uuid' => (string) Str::uuid(),

                    'company_id' => $subscription->company_id,

                    'subscription_id' => $subscription->id,

                    'amount' => $subscription->amount,

                    'currency' => 'USD',

                    'billing_date' => Carbon::today(),

                    'due_date' => Carbon::today()
                        ->addDays(7),

                    'status' => 'pending',

                    'payment_provider' => null,

                    'external_payment_id' => null,

                    'notes' => 'Initial billing generated from subscription',
                ]
            );
        }
    }
}