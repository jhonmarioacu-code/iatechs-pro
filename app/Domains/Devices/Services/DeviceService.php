<?php

declare(strict_types=1);

namespace App\Domains\Devices\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Domains\Devices\Models\Device;
use App\Domains\Devices\Repositories\DeviceRepository;

class DeviceService
{
    public function __construct(
        private DeviceRepository $repository
    ) {}

    /**
     * Listado paginado
     */
    public function paginate(
        ?int $companyId = null,
        int $perPage = 20
    ) {
        return $this->repository
            ->paginate(
                $companyId,
                $perPage
            );
    }

    /**
     * Crear dispositivo
     */
    public function createDevice(
        array $data
    ): Device {

        return DB::transaction(function () use ($data) {

            if (
                !empty($data['serial_number'])
                &&
                $this->repository->existsSerial(
                    $data['serial_number']
                )
            ) {
                throw new Exception(
                    'El número serial ya se encuentra registrado.'
                );
            }

            if (
                !empty($data['imei'])
                &&
                $this->repository->existsImei(
                    $data['imei']
                )
            ) {
                throw new Exception(
                    'El IMEI ya se encuentra registrado.'
                );
            }

            $data['uuid'] = (string) Str::uuid();

            $data['is_active'] =
                $data['is_active'] ?? true;

            return $this->repository
                ->create($data);
        });
    }

    /**
     * Actualizar dispositivo
     */
    public function updateDevice(
        Device $device,
        array $data
    ): Device {

        return DB::transaction(function () use (
            $device,
            $data
        ) {

            if (
                isset($data['serial_number'])
                &&
                $data['serial_number'] !==
                $device->serial_number
            ) {

                $existingDevice = $this->repository
                    ->findBySerialNumber(
                        $data['serial_number']
                    );

                if (
                    $existingDevice
                    &&
                    $existingDevice->id !== $device->id
                ) {
                    throw new Exception(
                        'El número serial ya existe.'
                    );
                }
            }

            if (
                isset($data['imei'])
                &&
                $data['imei'] !==
                $device->imei
            ) {

                $existingDevice = $this->repository
                    ->findByImei(
                        $data['imei']
                    );

                if (
                    $existingDevice
                    &&
                    $existingDevice->id !== $device->id
                ) {
                    throw new Exception(
                        'El IMEI ya existe.'
                    );
                }
            }

            return $this->repository
                ->update(
                    $device,
                    $data
                );
        });
    }

    /**
     * Eliminar dispositivo
     */
    public function deleteDevice(
        Device $device
    ): bool {

        /**
         * Futuro:
         * Validar tickets abiertos
         * Validar reparaciones activas
         */

        return $this->repository
            ->delete($device);
    }

    /**
     * Activar dispositivo
     */
    public function activateDevice(
        Device $device
    ): Device {

        return $this->repository
            ->update(
                $device,
                [
                    'is_active' => true
                ]
            );
    }

    /**
     * Desactivar dispositivo
     */
    public function deactivateDevice(
        Device $device
    ): Device {

        return $this->repository
            ->update(
                $device,
                [
                    'is_active' => false
                ]
            );
    }

    /**
     * Buscar dispositivo
     */
    public function find(
        int $id
    ): ?Device {

        return $this->repository
            ->find($id);
    }

    /**
     * Buscar por UUID
     */
    public function findByUuid(
        string $uuid
    ): ?Device {

        return $this->repository
            ->findByUuid($uuid);
    }

    /**
     * Dispositivos por empresa
     */
    public function getByCompany(
        int $companyId
    ) {

        return $this->repository
            ->getByCompany(
                $companyId
            );
    }

    /**
     * Dispositivos por sucursal
     */
    public function getByBranch(
        int $branchId
    ) {

        return $this->repository
            ->getByBranch(
                $branchId
            );
    }

    /**
     * Dispositivos por cliente
     */
    public function getByCustomer(
        int $customerId
    ) {

        return $this->repository
            ->getByCustomer(
                $customerId
            );
    }
}
