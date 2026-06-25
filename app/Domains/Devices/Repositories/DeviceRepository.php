<?php

declare(strict_types=1);

namespace App\Domains\Devices\Repositories;


use App\Support\Repositories\Concerns\ProvidesRepositoryDefaults;
use App\Domains\Devices\Models\Device;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DeviceRepository
{
    use ProvidesRepositoryDefaults;

    /**
     * Listado paginado
     */
    public function paginate(
        ?int $companyId = null,
        int $perPage = 20
    ): LengthAwarePaginator {

        $query = Device::query()
            ->with([
                'company',
                'branch',
                'customer'
            ])
            ->latest();

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        return $query->paginate($perPage);
    }

    /**
     * Obtener todos
     */
    public function all(): Collection
    {
        return Device::query()
            ->with([
                'company',
                'branch',
                'customer'
            ])
            ->latest()
            ->get();
    }

    /**
     * Buscar por ID
     */
    public function find(
        int $id
    ): ?Device {

        return Device::query()
            ->with([
                'company',
                'branch',
                'customer'
            ])
            ->find($id);
    }

    /**
     * Buscar por UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Device {

        return Device::query()
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * Buscar por serial
     */
    public function findBySerialNumber(
        string $serialNumber
    ): ?Device {

        return Device::query()
            ->where(
                'serial_number',
                $serialNumber
            )
            ->first();
    }

    /**
     * Buscar por IMEI
     */
    public function findByImei(
        string $imei
    ): ?Device {

        return Device::query()
            ->where('imei', $imei)
            ->first();
    }

    /**
     * Dispositivos por empresa
     */
    public function getByCompany(
        int $companyId
    ): Collection {

        return Device::query()
            ->where('company_id', $companyId)
            ->latest()
            ->get();
    }

    /**
     * Dispositivos por sucursal
     */
    public function getByBranch(
        int $branchId
    ): Collection {

        return Device::query()
            ->where('branch_id', $branchId)
            ->latest()
            ->get();
    }

    /**
     * Dispositivos por cliente
     */
    public function getByCustomer(
        int $customerId
    ): Collection {

        return Device::query()
            ->where('customer_id', $customerId)
            ->latest()
            ->get();
    }

    /**
     * Crear dispositivo
     */
    public function create(
        array $data
    ): Device {

        return Device::create($data);
    }

    /**
     * Actualizar dispositivo
     */
    public function update(
        Device $device,
        array $data
    ): Device {

        $device->update($data);

        return $device->refresh();
    }

    /**
     * Eliminar dispositivo
     */
    public function delete(
        Device $device
    ): bool {

        return (bool) $device->delete();
    }

    /**
     * Verificar serial existente
     */
    public function existsSerial(
        string $serialNumber
    ): bool {

        return Device::query()
            ->where(
                'serial_number',
                $serialNumber
            )
            ->exists();
    }

    /**
     * Verificar IMEI existente
     */
    public function existsImei(
        string $imei
    ): bool {

        return Device::query()
            ->where('imei', $imei)
            ->exists();
    }

    /**
     * Contar dispositivos por empresa
     */
    public function countByCompany(
        int $companyId
    ): int {

        return Device::query()
            ->where('company_id', $companyId)
            ->count();
    }

    /**
     * Contar dispositivos por sucursal
     */
    public function countByBranch(
        int $branchId
    ): int {

        return Device::query()
            ->where('branch_id', $branchId)
            ->count();
    }

    /**
     * Contar dispositivos por cliente
     */
    public function countByCustomer(
        int $customerId
    ): int {

        return Device::query()
            ->where('customer_id', $customerId)
            ->count();
    }
}
