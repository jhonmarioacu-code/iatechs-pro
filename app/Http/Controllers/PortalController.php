<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use App\Support\PortalRedirector;

class PortalController extends Controller
{
    private const CRUD_MODULES = [
        'customers',
        'tickets',
        'invoices',
    ];

    public function public(): View
    {
        return view('public.home');
    }

    public function homeRedirect(
        Request $request
    ): RedirectResponse {
        /** @var \App\Domains\Users\Models\User $user */
        $user = $request->user();

        return redirect()->to(
            PortalRedirector::routeForUser($user)
        );
    }

    public function admin(): View
    {
        return view('portals.admin.dashboard', [
            'portalTitle' => 'Admin Portal',
            'portalSubtitle' => 'Control global, empresas y operación SaaS.',
            'kpis' => [
                ['label' => 'Empresas activas', 'value' => '128', 'trend' => '+8.3%'],
                ['label' => 'Suscripciones vigentes', 'value' => '412', 'trend' => '+3.1%'],
                ['label' => 'Ingresos MRR', 'value' => '$84.2K', 'trend' => '+5.6%'],
                ['label' => 'Incidentes críticos', 'value' => '2', 'trend' => '-22.0%'],
            ],
        ]);
    }

    public function company(): View
    {
        return view('portals.company.dashboard', [
            'portalTitle' => 'Company Portal',
            'portalSubtitle' => 'Operación empresarial, CRM, inventario y finanzas.',
            'kpis' => [
                ['label' => 'Tickets abiertos', 'value' => '67', 'trend' => '-4.2%'],
                ['label' => 'Equipos en reparación', 'value' => '54', 'trend' => '+2.1%'],
                ['label' => 'Facturación mensual', 'value' => '$23.8K', 'trend' => '+7.4%'],
                ['label' => 'Stock crítico', 'value' => '9', 'trend' => '-11.0%'],
            ],
        ]);
    }

    public function technician(): View
    {
        return view('portals.technician.dashboard', [
            'portalTitle' => 'Technician Portal',
            'portalSubtitle' => 'Trabajo técnico diario: tickets, diagnóstico y reparación.',
            'kpis' => [
                ['label' => 'OT asignadas', 'value' => '18', 'trend' => '+6.0%'],
                ['label' => 'Diagnósticos pendientes', 'value' => '7', 'trend' => '-9.5%'],
                ['label' => 'SLA en riesgo', 'value' => '3', 'trend' => '-14.0%'],
                ['label' => 'Eficiencia semanal', 'value' => '93%', 'trend' => '+1.8%'],
            ],
        ]);
    }

    public function customer(): View
    {
        return view('portals.customer.dashboard', [
            'portalTitle' => 'Customer Portal',
            'portalSubtitle' => 'Seguimiento de tickets, equipos, facturas y garantías.',
            'kpis' => [
                ['label' => 'Mis tickets', 'value' => '4', 'trend' => '+1'],
                ['label' => 'Mis equipos', 'value' => '6', 'trend' => '+0'],
                ['label' => 'Facturas pendientes', 'value' => '2', 'trend' => '-1'],
                ['label' => 'Garantías activas', 'value' => '3', 'trend' => '+1'],
            ],
        ]);
    }

    public function module(
        Request $request,
        string $portal,
        string $module
    ): View {
        $allowedPortals = ['admin', 'company', 'technician', 'customer'];

        abort_unless(in_array($portal, $allowedPortals, true), 404);

        $moduleLabel = str($module)->replace('-', ' ')->title()->toString();

        return view('portals.module', [
            'portal' => $portal,
            'module' => $module,
            'moduleLabel' => $moduleLabel,
            'query' => $request->query(),
        ]);
    }

    public function companyModuleIndex(
        string $module
    ): View {
        $this->ensureCrudModule($module);

        return view('portals.company.crud', [
            'portalTitle' => ucfirst($module),
            'portalSubtitle' => 'Listado y gestión conectada por API.',
            'kpis' => $this->companyCrudKpis($module),
            'module' => $module,
            'mode' => 'index',
            'recordId' => null,
        ]);
    }

    public function companyModuleCreate(
        string $module
    ): View {
        $this->ensureCrudModule($module);

        return view('portals.company.crud', [
            'portalTitle' => ucfirst($module),
            'portalSubtitle' => 'Crear registro usando API v1.',
            'kpis' => $this->companyCrudKpis($module),
            'module' => $module,
            'mode' => 'create',
            'recordId' => null,
        ]);
    }

    public function companyModuleShow(
        string $module,
        int $id
    ): View {
        $this->ensureCrudModule($module);

        return view('portals.company.crud', [
            'portalTitle' => ucfirst($module),
            'portalSubtitle' => 'Vista detalle conectada por API.',
            'kpis' => $this->companyCrudKpis($module),
            'module' => $module,
            'mode' => 'show',
            'recordId' => $id,
        ]);
    }

    public function companyModuleEdit(
        string $module,
        int $id
    ): View {
        $this->ensureCrudModule($module);

        return view('portals.company.crud', [
            'portalTitle' => ucfirst($module),
            'portalSubtitle' => 'Editar registro usando API v1.',
            'kpis' => $this->companyCrudKpis($module),
            'module' => $module,
            'mode' => 'edit',
            'recordId' => $id,
        ]);
    }

    private function ensureCrudModule(
        string $module
    ): void {
        abort_unless(
            in_array(
                $module,
                self::CRUD_MODULES,
                true
            ),
            404
        );
    }

    private function companyCrudKpis(
        string $module
    ): array {
        return match ($module) {
            'customers' => [
                ['label' => 'Nuevos hoy', 'value' => '12', 'trend' => '+2.3%'],
                ['label' => 'Activos', 'value' => '1,248', 'trend' => '+1.1%'],
                ['label' => 'Persona', 'value' => '73%', 'trend' => '+0.8%'],
                ['label' => 'Empresa', 'value' => '27%', 'trend' => '-0.8%'],
            ],
            'tickets' => [
                ['label' => 'Abiertos', 'value' => '67', 'trend' => '-4.2%'],
                ['label' => 'En diagnóstico', 'value' => '19', 'trend' => '+1.2%'],
                ['label' => 'En reparación', 'value' => '25', 'trend' => '+2.8%'],
                ['label' => 'SLA en riesgo', 'value' => '3', 'trend' => '-10.0%'],
            ],
            default => [
                ['label' => 'Borradores', 'value' => '18', 'trend' => '+5.0%'],
                ['label' => 'Emitidas', 'value' => '84', 'trend' => '+3.7%'],
                ['label' => 'Pagadas', 'value' => '61', 'trend' => '+4.9%'],
                ['label' => 'Vencidas', 'value' => '5', 'trend' => '-12.0%'],
            ],
        };
    }
}
