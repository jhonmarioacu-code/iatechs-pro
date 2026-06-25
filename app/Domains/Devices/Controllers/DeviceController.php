<?php

declare(strict_types=1);

namespace App\Domains\Devices\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Domains\Devices\Models\Device;
use App\Domains\Devices\Services\DeviceService;
use App\Domains\Devices\Requests\StoreDeviceRequest;
use App\Domains\Devices\Requests\UpdateDeviceRequest;
use App\Domains\Devices\Resources\DeviceResource;

class DeviceController extends Controller
{
    public function __construct(
        private DeviceService $service
    ) {}

    /**
     * Listar dispositivos
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Device::class);

        $devices = $this->service->paginate(
            $this->tenantCompanyId($request)
        );

        return DeviceResource::collection(
            $devices
        );
    }

    /**
     * Crear dispositivo
     */
    public function store(
        StoreDeviceRequest $request
    ): DeviceResource {

        $this->authorize('create', Device::class);

        $device = $this->service
            ->createDevice(
                $request->validated()
            );

        return new DeviceResource(
            $device
        );
    }

    /**
     * Ver dispositivo
     */
    public function show(
        Device $device
    ): DeviceResource {

        $this->authorize('view', $device);

        return new DeviceResource(
            $device->load([
                'company',
                'branch',
                'customer'
            ])
        );
    }

    /**
     * Actualizar dispositivo
     */
    public function update(
        UpdateDeviceRequest $request,
        Device $device
    ): DeviceResource {

        $this->authorize('update', $device);

        $device = $this->service
            ->updateDevice(
                $device,
                $request->validated()
            );

        return new DeviceResource(
            $device
        );
    }

    /**
     * Eliminar dispositivo
     */
    public function destroy(
        Device $device
    ): JsonResponse {

        $this->authorize('delete', $device);

        $this->service
            ->deleteDevice(
                $device
            );

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo eliminado correctamente.'
        ]);
    }

    /**
     * Activar dispositivo
     */
    public function activate(
        Device $device
    ): DeviceResource {

        $this->authorize('update', $device);

        $device = $this->service
            ->activateDevice(
                $device
            );

        return new DeviceResource(
            $device
        );
    }

    /**
     * Desactivar dispositivo
     */
    public function deactivate(
        Device $device
    ): DeviceResource {

        $this->authorize('update', $device);

        $device = $this->service
            ->deactivateDevice(
                $device
            );

        return new DeviceResource(
            $device
        );
    }

    private function tenantCompanyId(Request $request): ?int
    {
        $user = $request->user();

        return $user->hasRole('super_admin')
            ? null
            : $user->company_id;
    }
}
