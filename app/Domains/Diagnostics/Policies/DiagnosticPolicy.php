<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Diagnostics\Models\Diagnostic;

class DiagnosticPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'diagnostics.view'
        );
    }

    public function view(
        User $user,
        Diagnostic $diagnostic
    ): bool {

        return
            $user->can('diagnostics.view')
            &&
            $user->company_id ===
            $diagnostic->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'diagnostics.create'
        );
    }

    public function update(
        User $user,
        Diagnostic $diagnostic
    ): bool {

        return
            $user->can('diagnostics.update')
            &&
            $user->company_id ===
            $diagnostic->company_id;
    }

    public function delete(
        User $user,
        Diagnostic $diagnostic
    ): bool {

        return
            $user->can('diagnostics.delete')
            &&
            $user->company_id ===
            $diagnostic->company_id;
    }

    public function start(
        User $user,
        Diagnostic $diagnostic
    ): bool {

        return
            $user->can('diagnostics.start')
            &&
            $user->company_id ===
            $diagnostic->company_id;
    }

    public function complete(
        User $user,
        Diagnostic $diagnostic
    ): bool {

        return
            $user->can('diagnostics.complete')
            &&
            $user->company_id ===
            $diagnostic->company_id;
    }

    public function cancel(
        User $user,
        Diagnostic $diagnostic
    ): bool {

        return
            $user->can('diagnostics.cancel')
            &&
            $user->company_id ===
            $diagnostic->company_id;
    }
}