<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Companies\Models\Company;
use App\Domains\CRM\Models\Activity;
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\Opportunity;
use App\Domains\Customers\Models\Customer;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Products\Models\Product;
use App\Domains\ServiceContracts\Models\ServiceContract;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Users\Models\User;
use App\Support\PortalRedirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function homeRedirect(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        return redirect()->to(
            PortalRedirector::routeForUser($user)
        );
    }

    public function admin(): View
    {
        $companiesCount = Company::query()->count();
        $subscriptionsCount = Subscription::query()->count();
        $mrr = (float) Subscription::query()
            ->whereIn('status', ['trial', 'active'])
            ->sum('amount');
        $criticalTickets = Ticket::query()
            ->whereIn('priority', ['HIGH', 'URGENT'])
            ->whereNotIn('status', ['CLOSED', 'DELIVERED'])
            ->count();

        return view('portals.admin.dashboard', [
            'portalTitle' => 'Admin Portal',
            'portalSubtitle' => 'Control global, empresas y operacion SaaS.',
            'kpis' => [
                ['label' => 'Empresas', 'value' => (string) $companiesCount, 'trend' => 'Global'],
                ['label' => 'Suscripciones', 'value' => (string) $subscriptionsCount, 'trend' => 'Global'],
                ['label' => 'MRR aproximado', 'value' => '$'.number_format($mrr, 2), 'trend' => 'Finanzas'],
                ['label' => 'Tickets criticos', 'value' => (string) $criticalTickets, 'trend' => 'Operacion'],
            ],
        ]);
    }

    public function company(): View
    {
        return view('portals.company.dashboard', [
            'portalTitle' => 'Company Portal',
            'portalSubtitle' => 'Operacion empresarial, CRM, inventario y finanzas.',
            'kpis' => [
                ['label' => 'Tickets abiertos', 'value' => '67', 'trend' => '-4.2%'],
                ['label' => 'Equipos en reparacion', 'value' => '54', 'trend' => '+2.1%'],
                ['label' => 'Facturacion mensual', 'value' => '$23.8K', 'trend' => '+7.4%'],
                ['label' => 'Stock critico', 'value' => '9', 'trend' => '-11.0%'],
            ],
        ]);
    }

    public function technician(): View
    {
        return view('portals.technician.dashboard', [
            'portalTitle' => 'Technician Portal',
            'portalSubtitle' => 'Trabajo tecnico diario: tickets, diagnostico y reparacion.',
            'kpis' => [
                ['label' => 'OT asignadas', 'value' => '18', 'trend' => '+6.0%'],
                ['label' => 'Diagnosticos pendientes', 'value' => '7', 'trend' => '-9.5%'],
                ['label' => 'SLA en riesgo', 'value' => '3', 'trend' => '-14.0%'],
                ['label' => 'Eficiencia semanal', 'value' => '93%', 'trend' => '+1.8%'],
            ],
        ]);
    }

    public function customer(): View
    {
        return view('portals.customer.dashboard', [
            'portalTitle' => 'Customer Portal',
            'portalSubtitle' => 'Seguimiento de tickets, equipos, facturas y garantias.',
            'kpis' => [
                ['label' => 'Mis tickets', 'value' => '4', 'trend' => '+1'],
                ['label' => 'Mis equipos', 'value' => '6', 'trend' => '+0'],
                ['label' => 'Facturas pendientes', 'value' => '2', 'trend' => '-1'],
                ['label' => 'Garantias activas', 'value' => '3', 'trend' => '+1'],
            ],
        ]);
    }

    public function module(Request $request, string $portal, string $module): View
    {
        $allowedPortals = ['admin', 'company', 'technician', 'customer'];

        abort_unless(in_array($portal, $allowedPortals, true), 404);

        $moduleLabel = str($module)->replace('-', ' ')->title()->toString();

        return view('portals.module', [
            'portal' => $portal,
            'module' => $module,
            'moduleLabel' => $moduleLabel,
            'query' => $request->query(),
            'moduleData' => $this->resolveModuleData($portal, $module),
        ]);
    }

    public function companyModuleIndex(string $module): View
    {
        $this->ensureCrudModule($module);

        return view('portals.company.crud', [
            'portalTitle' => ucfirst($module),
            'portalSubtitle' => 'Listado y gestion conectada por API.',
            'kpis' => $this->companyCrudKpis($module),
            'module' => $module,
            'mode' => 'index',
            'recordId' => null,
        ]);
    }

    public function companyModuleCreate(string $module): View
    {
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

    public function companyModuleShow(string $module, int $id): View
    {
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

    public function companyModuleEdit(string $module, int $id): View
    {
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

    private function ensureCrudModule(string $module): void
    {
        abort_unless(
            in_array(
                $module,
                self::CRUD_MODULES,
                true
            ),
            404
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveModuleData(string $portal, string $module): array
    {
        if ($portal !== 'admin') {
            return [];
        }

        return match ($module) {
            'dashboards' => [
                'stats' => [
                    ['label' => 'Empresas', 'value' => (string) Company::query()->count()],
                    ['label' => 'Usuarios', 'value' => (string) User::query()->count()],
                    ['label' => 'Clientes', 'value' => (string) Customer::query()->count()],
                    ['label' => 'Tickets', 'value' => (string) Ticket::query()->count()],
                    ['label' => 'Facturas', 'value' => (string) Invoice::query()->count()],
                    ['label' => 'Suscripciones activas', 'value' => (string) Subscription::query()->whereIn('status', ['trial', 'active'])->count()],
                ],
            ],
            'customers' => [
                'rows' => Customer::query()
                    ->with(['company', 'branch'])
                    ->latest('id')
                    ->limit(20)
                    ->get(),
            ],
            'crm' => [
                'stats' => [
                    ['label' => 'Leads', 'value' => (string) Lead::query()->count()],
                    ['label' => 'Opportunities', 'value' => (string) Opportunity::query()->count()],
                    ['label' => 'Activities', 'value' => (string) Activity::query()->count()],
                    ['label' => 'Pipeline', 'value' => '$'.number_format((float) Opportunity::query()->sum('amount'), 2)],
                ],
                'leads' => Lead::query()->latest('id')->limit(10)->get(),
                'opportunities' => Opportunity::query()->latest('id')->limit(10)->get(),
            ],
            'marketplace' => [
                'products' => Product::query()
                    ->where('status', 'ACTIVE')
                    ->latest('id')
                    ->limit(16)
                    ->get(),
                'services' => ServiceContract::query()
                    ->whereIn('status', ['active', 'ACTIVE'])
                    ->latest('id')
                    ->limit(16)
                    ->get(),
            ],
            default => [],
        };
    }

    private function companyCrudKpis(string $module): array
    {
        return match ($module) {
            'customers' => [
                ['label' => 'Nuevos hoy', 'value' => '12', 'trend' => '+2.3%'],
                ['label' => 'Activos', 'value' => '1,248', 'trend' => '+1.1%'],
                ['label' => 'Persona', 'value' => '73%', 'trend' => '+0.8%'],
                ['label' => 'Empresa', 'value' => '27%', 'trend' => '-0.8%'],
            ],
            'tickets' => [
                ['label' => 'Abiertos', 'value' => '67', 'trend' => '-4.2%'],
                ['label' => 'En diagnostico', 'value' => '19', 'trend' => '+1.2%'],
                ['label' => 'En reparacion', 'value' => '25', 'trend' => '+2.8%'],
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
