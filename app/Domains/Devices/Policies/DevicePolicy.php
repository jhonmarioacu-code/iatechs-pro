<?php

declare(strict_types=1);

namespace App\Domains\Devices\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Devices\Models\Device;

class DevicePolicy
{
    /**
     * Ver listado
     */
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'devices.view'
        );
    }

    /**
     * Ver dispositivo
     */
    public function view(
        User $user,
        Device $device
    ): bool {

        return
            $user->can('devices.view')
            &&
            $user->company_id === $device->company_id;
    }

    /**
     * Crear dispositivo
     */
    public function create(
        User $user
    ): bool {

        return $user->can(
            'devices.create'
        );
    }

    /**
     * Actualizar dispositivo
     */
    public function update(
        User $user,
        Device $device
    ): bool {

        return
            $user->can('devices.update')
            &&
            $user->company_id === $device->company_id;
    }

    /**
     * Eliminar dispositivo
     */
    public function delete(
        User $user,
        Device $device
    ): bool {

        return
            $user->can('devices.delete')
            &&
            $user->company_id === $device->company_id;
    }

    /**
     * Restaurar dispositivo
     */
    public function restore(
        User $user,
        Device $device
    ): bool {

        return
            $user->can('devices.update')
            &&
            $user->company_id === $device->company_id;
    }

    /**
     * Eliminación permanente
     */
    public function forceDelete(
        User $user,
        Device $device
    ): bool {

        return false;
    }
}