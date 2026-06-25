<?php

declare(strict_types=1);

namespace App\Domains\Payments\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Payments\Models\Payment;

class PaymentPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'payments.view'
        );
    }

    public function view(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.view')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'payments.create'
        );
    }

    public function update(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.update')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function delete(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.delete')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function complete(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.complete')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function refund(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.refund')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function cancel(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.cancel')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function restore(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.restore')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    public function forceDelete(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->can('payments.force_delete')
            &&
            $this->sameCompany(
                $user,
                $payment
            );
    }

    private function sameCompany(
        User $user,
        Payment $payment
    ): bool {

        return
            $user->company_id ===
            $payment->company_id;
    }
}