<?php

declare(strict_types=1);

namespace App\Domains\Diagnostics\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Domains\Diagnostics\Models\Diagnostic;
use App\Domains\Diagnostics\Services\DiagnosticService;
use App\Domains\Diagnostics\Requests\StoreDiagnosticRequest;
use App\Domains\Diagnostics\Requests\UpdateDiagnosticRequest;
use App\Domains\Diagnostics\Resources\DiagnosticResource;

class DiagnosticController extends Controller
{
    public function __construct(
        private DiagnosticService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Diagnostic::class);

        return DiagnosticResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreDiagnosticRequest $request
    )
    {
        $this->authorize('create', Diagnostic::class);

        return new DiagnosticResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Diagnostic $diagnostic
    )
    {
        $this->authorize('view', $diagnostic);

        return new DiagnosticResource(
            $diagnostic->load([
                'company',
                'branch',
                'ticket',
                'technician'
            ])
        );
    }

    public function update(
        UpdateDiagnosticRequest $request,
        Diagnostic $diagnostic
    )
    {
        $this->authorize('update', $diagnostic);

        return new DiagnosticResource(
            $this->service->update(
                $diagnostic,
                $request->validated()
            )
        );
    }

    public function destroy(
        Diagnostic $diagnostic
    ): JsonResponse
    {
        $this->authorize('delete', $diagnostic);

        $this->service->delete(
            $diagnostic
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function start(
        Diagnostic $diagnostic
    )
    {
        $this->authorize('start', $diagnostic);

        return new DiagnosticResource(
            $this->service->start(
                $diagnostic
            )
        );
    }

    public function complete(
        Request $request,
        Diagnostic $diagnostic
    )
    {
        $this->authorize('complete', $diagnostic);

        $request->validate([
            'diagnostic_result' => ['required'],
            'recommended_solution' => ['required'],
            'estimated_cost' => ['required','numeric'],
            'estimated_hours' => ['required','integer'],
        ]);

        return new DiagnosticResource(
            $this->service->complete(
                $diagnostic,
                $request->all()
            )
        );
    }

    public function cancel(
        Diagnostic $diagnostic
    )
    {
        $this->authorize('cancel', $diagnostic);

        return new DiagnosticResource(
            $this->service->cancel(
                $diagnostic
            )
        );
    }
}
