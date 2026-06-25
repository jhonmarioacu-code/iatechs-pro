<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Warranties\Models\Warranty;
use App\Domains\Warranties\Services\WarrantyService;
use App\Domains\Warranties\Requests\StoreWarrantyRequest;
use App\Domains\Warranties\Requests\UpdateWarrantyRequest;
use App\Domains\Warranties\Resources\WarrantyResource;

class WarrantyController extends Controller
{
    public function __construct(
        private WarrantyService $service
    ) {}

    public function index()
    {
        return WarrantyResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreWarrantyRequest $request
    )
    {
        return new WarrantyResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Warranty $warranty
    )
    {
        return new WarrantyResource(
            $warranty->load([
                'customer',
                'device',
                'repair',
                'invoice'
            ])
        );
    }

    public function update(
        UpdateWarrantyRequest $request,
        Warranty $warranty
    )
    {
        return new WarrantyResource(
            $this->service->update(
                $warranty,
                $request->validated()
            )
        );
    }

    public function destroy(
        Warranty $warranty
    )
    {
        $warranty->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function claim(
        Warranty $warranty
    )
    {
        return new WarrantyResource(
            $this->service->claim(
                $warranty
            )
        );
    }

    public function expire(
        Warranty $warranty
    )
    {
        return new WarrantyResource(
            $this->service->expire(
                $warranty
            )
        );
    }

    public function void(
        Warranty $warranty
    )
    {
        return new WarrantyResource(
            $this->service->void(
                $warranty
            )
        );
    }
}