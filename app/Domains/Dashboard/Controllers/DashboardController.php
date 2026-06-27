<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Controllers;

use App\Http\Controllers\Controller;

use App\Domains\Dashboard\Models\Dashboard;
use App\Domains\Dashboard\Services\DashboardService;

use App\Domains\Dashboard\Resources\DashboardResource;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Dashboard::class);

        return DashboardResource::collection(
            $this->service->paginate()
        );
    }

    public function show(
        Dashboard $dashboard
    )
    {
        $this->authorize('view', $dashboard);

        return new DashboardResource(
            $dashboard->load(
                'widgets'
            )
        );
    }
}
