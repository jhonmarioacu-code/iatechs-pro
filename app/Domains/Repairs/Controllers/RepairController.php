<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Domains\Repairs\Models\Repair;
use App\Domains\Repairs\Services\RepairService;
use App\Domains\Repairs\Requests\StoreRepairRequest;
use App\Domains\Repairs\Requests\UpdateRepairRequest;
use App\Domains\Repairs\Resources\RepairResource;

class RepairController extends Controller
{
    public function __construct(
        private RepairService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Repair::class);

        return RepairResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreRepairRequest $request
    )
    {
        $this->authorize('create', Repair::class);

        return new RepairResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Repair $repair
    )
    {
        $this->authorize('view', $repair);

        return new RepairResource(
            $repair->load([
                'ticket',
                'diagnostic',
                'quote',
                'technician'
            ])
        );
    }

    public function update(
        UpdateRepairRequest $request,
        Repair $repair
    )
    {
        $this->authorize('update', $repair);

        return new RepairResource(
            $this->service->update(
                $repair,
                $request->validated()
            )
        );
    }

    public function destroy(
        Repair $repair
    )
    {
        $this->authorize('delete', $repair);

        $this->service->delete(
            $repair
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function assign(
        Request $request,
        Repair $repair
    )
    {
        $this->authorize('assign', $repair);

        $request->validate([
            'technician_id' => [
                'required',
                Rule::exists('users', 'id')->where(static function ($query) use ($repair) {
                    $query->where('company_id', $repair->company_id);
                }),
            ]
        ]);

        return new RepairResource(
            $this->service->assign(
                $repair,
                $request->technician_id
            )
        );
    }

    public function start(
        Repair $repair
    )
    {
        $this->authorize('start', $repair);

        return new RepairResource(
            $this->service->start(
                $repair
            )
        );
    }

    public function complete(
        Repair $repair
    )
    {
        $this->authorize('complete', $repair);

        return new RepairResource(
            $this->service->complete(
                $repair
            )
        );
    }

    public function deliver(
        Repair $repair
    )
    {
        $this->authorize('deliver', $repair);

        return new RepairResource(
            $this->service->deliver(
                $repair
            )
        );
    }

    public function cancel(
        Repair $repair
    )
    {
        $this->authorize('cancel', $repair);

        return new RepairResource(
            $this->service->cancel(
                $repair
            )
        );
    }
}
