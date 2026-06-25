<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use App\Domains\Users\Models\User;
use App\Domains\Tickets\Models\Ticket;
use App\Domains\Repairs\Models\Repair;
use App\Domains\Products\Models\Product;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Payments\Models\Payment;
use App\Domains\Customers\Models\Customer;
use App\Domains\ServiceContracts\Models\ServiceContract;
use App\Domains\Payments\Services\PaymentService;

class CustomerPortalController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    public function dashboard(Request $request): View
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.view');

        $customer = $this->resolveCustomer($request);

        $ticketCount = Ticket::query()
            ->where('customer_id', $customer->id)
            ->count();

        $activeRepairCount = Repair::query()
            ->whereHas('ticket', fn ($query) => $query->where('customer_id', $customer->id))
            ->whereIn('status', ['ASSIGNED', 'IN_PROGRESS', 'WAITING_PARTS'])
            ->count();

        $pendingInvoiceCount = Invoice::query()
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['issued', 'partially_paid', 'overdue'])
            ->count();

        $latestTickets = Ticket::query()
            ->with(['device', 'technician'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->limit(5)
            ->get();

        $marketplaceProducts = Product::query()
            ->where('status', 'ACTIVE')
            ->latest()
            ->limit(8)
            ->get();

        $marketplaceServices = ServiceContract::query()
            ->whereIn('status', ['active', 'ACTIVE'])
            ->latest()
            ->limit(8)
            ->get();

        return view('portals.customer.dashboard', [
            'portalTitle' => 'Customer Portal',
            'portalSubtitle' => 'Seguimiento de tus tickets, reparaciones, facturas y pagos.',
            'kpis' => [
                ['label' => 'Mis tickets', 'value' => (string) $ticketCount, 'trend' => 'En tiempo real'],
                ['label' => 'Reparaciones activas', 'value' => (string) $activeRepairCount, 'trend' => 'En tiempo real'],
                ['label' => 'Facturas pendientes', 'value' => (string) $pendingInvoiceCount, 'trend' => 'En tiempo real'],
                ['label' => 'Marketplace disponible', 'value' => (string) ($marketplaceProducts->count() + $marketplaceServices->count()), 'trend' => 'Productos y servicios'],
            ],
            'customer' => $customer,
            'latestTickets' => $latestTickets,
            'marketplaceProducts' => $marketplaceProducts,
            'marketplaceServices' => $marketplaceServices,
        ]);
    }

    public function tickets(Request $request): View
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.tickets.view');

        $customer = $this->resolveCustomer($request);

        $tickets = Ticket::query()
            ->with(['device', 'technician'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(20);

        return view('portals.customer.tickets', [
            'portalTitle' => 'Mis Tickets',
            'portalSubtitle' => 'Estado actualizado de tus solicitudes tecnicas.',
            'kpis' => [],
            'tickets' => $tickets,
        ]);
    }

    public function showTicket(Request $request, Ticket $ticket): View
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.tickets.view');

        $customer = $this->resolveCustomer($request);
        abort_if($ticket->customer_id !== $customer->id, 403);

        $ticket->load(['device', 'technician', 'diagnostics', 'repairs']);

        return view('portals.customer.ticket', [
            'portalTitle' => 'Detalle Ticket '.$ticket->ticket_number,
            'portalSubtitle' => 'Seguimiento completo de diagnostico y reparacion.',
            'kpis' => [],
            'ticket' => $ticket,
        ]);
    }

    public function invoices(Request $request): View
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.invoices.view');

        $customer = $this->resolveCustomer($request);

        $invoices = Invoice::query()
            ->with(['ticket', 'repair'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(20);

        return view('portals.customer.invoices', [
            'portalTitle' => 'Mis Facturas',
            'portalSubtitle' => 'Facturas, pagos y saldos pendientes.',
            'kpis' => [],
            'invoices' => $invoices,
        ]);
    }

    public function showInvoice(Request $request, Invoice $invoice): View
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.invoices.view');

        $customer = $this->resolveCustomer($request);
        abort_if($invoice->customer_id !== $customer->id, 403);

        $invoice->load(['ticket', 'repair', 'items', 'payments']);

        $completedAmount = (float) $invoice->payments
            ->where('status', Payment::COMPLETED)
            ->sum('amount');

        $remainingAmount = max((float) $invoice->total - $completedAmount, 0.0);

        return view('portals.customer.invoice', [
            'portalTitle' => 'Factura '.$invoice->invoice_number,
            'portalSubtitle' => 'Detalle de cobro y opciones de pago.',
            'kpis' => [],
            'invoice' => $invoice,
            'completedAmount' => $completedAmount,
            'remainingAmount' => $remainingAmount,
        ]);
    }

    public function downloadInvoice(Request $request, Invoice $invoice): Response
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.invoices.view');

        $customer = $this->resolveCustomer($request);
        abort_if($invoice->customer_id !== $customer->id, 403);

        $invoice->load(['items', 'payments', 'ticket', 'repair']);

        $lines = [
            'IAtechs Pro - Factura',
            'Numero: '.$invoice->invoice_number,
            'Estado: '.$invoice->status,
            'Moneda: '.$invoice->currency,
            'Subtotal: '.$invoice->subtotal,
            'Impuestos: '.$invoice->tax,
            'Descuento: '.$invoice->discount,
            'Total: '.$invoice->total,
            'Emitida: '.optional($invoice->issued_at)->toDateTimeString(),
            'Vence: '.optional($invoice->due_date)->toDateString(),
            'Ticket: '.($invoice->ticket?->ticket_number ?? 'N/A'),
            'Reparacion: '.($invoice->repair?->repair_number ?? 'N/A'),
            '',
            'Items:',
        ];

        foreach ($invoice->items as $item) {
            $lines[] = '- '.$item->description.' | Cantidad: '.$item->quantity.' | Unitario: '.$item->unit_price.' | Total: '.$item->total;
        }

        $content = implode(PHP_EOL, $lines).PHP_EOL;

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="invoice-'.$invoice->invoice_number.'.txt"',
        ]);
    }

    public function payInvoice(Request $request, Invoice $invoice): RedirectResponse
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.pay');

        $customer = $this->resolveCustomer($request);
        abort_if($invoice->customer_id !== $customer->id, 403);

        $invoice->load('payments');

        $validated = $request->validate([
            'payment_method' => ['required', 'in:CARD,BANK_TRANSFER,PSE,NEQUI,DAVIPLATA,PAYPAL,STRIPE,MERCADOPAGO,OTHER'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $completedAmount = (float) $invoice->payments
            ->where('status', Payment::COMPLETED)
            ->sum('amount');
        $remainingAmount = max((float) $invoice->total - $completedAmount, 0.0);

        if ($remainingAmount <= 0.0) {
            return back()->withErrors(['amount' => 'La factura ya se encuentra pagada.']);
        }

        if ((float) $validated['amount'] > $remainingAmount) {
            return back()->withErrors(['amount' => 'El monto supera el saldo pendiente de la factura.']);
        }

        /** @var User $user */
        $user = $request->user();

        $payment = $this->paymentService->create([
            'branch_id' => $invoice->branch_id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'processed_by' => $user->id,
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['reference'] ?? null,
            'currency' => $invoice->currency,
            'amount' => $validated['amount'],
            'is_partial' => (float) $validated['amount'] < $remainingAmount,
            'status' => Payment::PENDING,
            'external_transaction_id' => 'SIM-'.Str::upper(Str::random(10)),
            'notes' => 'Pago generado desde portal cliente.',
        ]);

        $payment = $this->paymentService->complete($payment);

        return redirect()
            ->route('portal.customer.payments.receipt', $payment)
            ->with('status', 'Pago aplicado correctamente.');
    }

    public function paymentReceipt(Request $request, Payment $payment): Response
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.pay');

        $customer = $this->resolveCustomer($request);
        abort_if($payment->customer_id !== $customer->id, 403);

        $payment->load('invoice');

        $content = implode(PHP_EOL, [
            'IAtechs Pro - Comprobante de Pago',
            'Numero comprobante: '.$payment->payment_number,
            'Factura: '.($payment->invoice?->invoice_number ?? 'N/A'),
            'Estado pago: '.$payment->status,
            'Metodo: '.$payment->payment_method,
            'Monto: '.$payment->amount.' '.$payment->currency,
            'Referencia: '.($payment->reference ?? 'N/A'),
            'Transaccion: '.($payment->external_transaction_id ?? 'N/A'),
            'Fecha pago: '.optional($payment->paid_at)->toDateTimeString(),
        ]).PHP_EOL;

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="receipt-'.$payment->payment_number.'.txt"',
        ]);
    }

    public function marketplace(Request $request): View
    {
        $this->authorizeCustomerPermission($request, 'customer.portal.marketplace.view');

        $products = Product::query()
            ->where('status', 'ACTIVE')
            ->latest()
            ->paginate(16);

        $services = ServiceContract::query()
            ->whereIn('status', ['active', 'ACTIVE'])
            ->latest()
            ->limit(16)
            ->get();

        return view('portals.customer.marketplace', [
            'portalTitle' => 'Marketplace',
            'portalSubtitle' => 'Productos y servicios disponibles de la empresa.',
            'kpis' => [],
            'products' => $products,
            'services' => $services,
        ]);
    }

    private function authorizeCustomerPermission(Request $request, string $permission): void
    {
        /** @var User $user */
        $user = $request->user();
        abort_if(!$user->can($permission), 403);
    }

    private function resolveCustomer(Request $request): Customer
    {
        /** @var User $user */
        $user = $request->user();

        $customer = Customer::query()
            ->where('company_id', $user->company_id)
            ->where('email', $user->email)
            ->first();

        abort_if(!$customer, 403, 'No existe un perfil de cliente vinculado al usuario actual.');

        return $customer;
    }
}
