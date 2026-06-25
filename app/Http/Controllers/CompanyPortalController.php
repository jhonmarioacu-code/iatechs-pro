<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Branches\Models\Branch;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $approvedCount = Ticket::query()
            ->where('company_id', $user->company_id)
            ->where('status', 'APPROVED')
            ->count();

        $pendingCount = Ticket::query()
            ->where('company_id', $user->company_id)
            ->whereIn('status', ['OPEN', 'ASSIGNED', 'IN_DIAGNOSIS', 'WAITING_QUOTE', 'IN_REPAIR', 'READY_DELIVERY'])
            ->count();

        $finalizedCount = Ticket::query()
            ->where('company_id', $user->company_id)
            ->whereIn('status', ['DELIVERED', 'CLOSED'])
            ->count();

        $notCompletedCount = Ticket::query()
            ->where('company_id', $user->company_id)
            ->where('status', 'CANCELLED')
            ->count();

        $ticketBoard = [
            'Aprobada' => Ticket::query()
                ->where('company_id', $user->company_id)
                ->where('status', 'APPROVED')
                ->with(['customer', 'device', 'branch', 'technician'])
                ->latest()
                ->limit(20)
                ->get(),
            'Pendiente' => Ticket::query()
                ->where('company_id', $user->company_id)
                ->whereIn('status', ['OPEN', 'ASSIGNED', 'IN_DIAGNOSIS', 'WAITING_QUOTE', 'IN_REPAIR', 'READY_DELIVERY'])
                ->with(['customer', 'device', 'branch', 'technician'])
                ->latest()
                ->limit(20)
                ->get(),
            'Finalizada' => Ticket::query()
                ->where('company_id', $user->company_id)
                ->whereIn('status', ['DELIVERED', 'CLOSED'])
                ->with(['customer', 'device', 'branch', 'technician'])
                ->latest()
                ->limit(20)
                ->get(),
            'No Concretada' => Ticket::query()
                ->where('company_id', $user->company_id)
                ->where('status', 'CANCELLED')
                ->with(['customer', 'device', 'branch', 'technician'])
                ->latest()
                ->limit(20)
                ->get(),
        ];

        $branches = Branch::query()
            ->where('company_id', $user->company_id)
            ->orderBy('name')
            ->get();

        $personnel = User::query()
            ->where('company_id', $user->company_id)
            ->with(['roles', 'branch'])
            ->orderBy('name')
            ->get();

        $technicians = User::query()
            ->where('company_id', $user->company_id)
            ->whereHas('roles', static fn ($query) => $query->where('name', 'technician'))
            ->with(['roles', 'branch'])
            ->orderBy('name')
            ->get();

        return view('portals.company.dashboard', [
            'portalTitle' => 'Company Portal',
            'portalSubtitle' => 'Gestion de tickets, personal, roles, sucursales y formacion tecnica.',
            'kpis' => [
                ['label' => 'Tickets aprobada', 'value' => (string) $approvedCount, 'trend' => 'Flujo operativo'],
                ['label' => 'Tickets pendiente', 'value' => (string) $pendingCount, 'trend' => 'Flujo operativo'],
                ['label' => 'Tickets finalizada', 'value' => (string) $finalizedCount, 'trend' => 'Flujo operativo'],
                ['label' => 'Tickets no concretada', 'value' => (string) $notCompletedCount, 'trend' => 'Flujo operativo'],
            ],
            'ticketBoard' => $ticketBoard,
            'personnel' => $personnel,
            'branches' => $branches,
            'technicians' => $technicians,
        ]);
    }

    public function updateTechnicianTraining(
        Request $request,
        User $technician
    ): RedirectResponse {
        /** @var User $actor */
        $actor = $request->user();

        abort_if($technician->company_id !== $actor->company_id, 403);
        abort_if(!$technician->hasRole('technician'), 422, 'El usuario seleccionado no es tecnico.');
        abort_unless(
            $actor->hasAnyRole(['owner', 'administrator', 'manager', 'super_admin']) || $actor->can('users.update'),
            403
        );

        $validated = $request->validate([
            'technician_course_enabled' => ['nullable', 'boolean'],
            'technician_exam_enabled' => ['nullable', 'boolean'],
        ]);

        $technician->update([
            'technician_course_enabled' => (bool) ($validated['technician_course_enabled'] ?? false),
            'technician_exam_enabled' => (bool) ($validated['technician_exam_enabled'] ?? false),
        ]);

        return back()->with('status', 'Accesos de cursos y examenes actualizados para tecnico.');
    }
}
