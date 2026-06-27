"use client";

import Link from "next/link";
import { FormEvent, useEffect, useMemo, useState } from "react";
import { getStoredSession } from "@/shared/lib/session-storage";
import { createInvoice, listInvoices, updateInvoiceStatus } from "@/shared/services/billing-service";
import type { AuthSession } from "@/shared/types/auth";
import type { Invoice, InvoiceStatus } from "@/shared/types/billing";

const INVOICE_STATUS_OPTIONS: InvoiceStatus[] = ["draft", "issued", "paid", "void"];

const STATUS_LABEL: Record<InvoiceStatus, string> = {
  draft: "Borrador",
  issued: "Emitida",
  paid: "Pagada",
  void: "Anulada"
};

interface InvoiceFormState {
  invoiceNumber: string;
  customerId: string;
  repairOrderId: string;
  currency: string;
  subtotalCents: string;
  taxCents: string;
  discountCents: string;
  dueAt: string;
  notes: string;
}

const INITIAL_FORM: InvoiceFormState = {
  invoiceNumber: "",
  customerId: "",
  repairOrderId: "",
  currency: "COP",
  subtotalCents: "0",
  taxCents: "0",
  discountCents: "0",
  dueAt: "",
  notes: ""
};

function parseNonNegativeInt(value: string): number {
  const parsed = Number.parseInt(value, 10);
  if (!Number.isFinite(parsed) || parsed < 0) {
    return 0;
  }

  return parsed;
}

function toIsoDate(value: string): string | undefined {
  if (!value.trim()) {
    return undefined;
  }

  const parsedDate = new Date(`${value}T00:00:00.000Z`);
  if (Number.isNaN(parsedDate.getTime())) {
    return undefined;
  }

  return parsedDate.toISOString();
}

function formatCurrency(amountCents: number, currency: string): string {
  try {
    return new Intl.NumberFormat("es-CO", {
      style: "currency",
      currency
    }).format(amountCents / 100);
  } catch {
    return `${currency} ${(amountCents / 100).toLocaleString("es-CO", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })}`;
  }
}

export default function BillingPage() {
  const [session, setSession] = useState<AuthSession | null>(null);
  const [invoices, setInvoices] = useState<Invoice[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState<InvoiceStatus | "all">("all");
  const [invoiceForm, setInvoiceForm] = useState<InvoiceFormState>(INITIAL_FORM);

  const canCreate = useMemo(() => {
    const subtotalCents = parseNonNegativeInt(invoiceForm.subtotalCents);
    return subtotalCents > 0 && invoiceForm.currency.trim().length === 3;
  }, [invoiceForm.currency, invoiceForm.subtotalCents]);

  useEffect(() => {
    setSession(getStoredSession());
  }, []);

  async function loadInvoices(activeSession: AuthSession) {
    setIsLoading(true);
    setErrorMessage(null);

    try {
      const response = await listInvoices({
        session: activeSession,
        search: search.trim() || undefined,
        status: statusFilter === "all" ? undefined : statusFilter,
        page: 1,
        pageSize: 50
      });
      setInvoices(response.items);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible cargar facturas.");
    } finally {
      setIsLoading(false);
    }
  }

  useEffect(() => {
    if (!session) {
      return;
    }
    void loadInvoices(session);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [session, search, statusFilter]);

  async function onCreateInvoice(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!session) {
      setErrorMessage("No hay sesion activa.");
      return;
    }

    setIsSubmitting(true);
    setErrorMessage(null);

    try {
      await createInvoice({
        session,
        invoiceNumber: invoiceForm.invoiceNumber.trim() || undefined,
        customerId: invoiceForm.customerId.trim() || undefined,
        repairOrderId: invoiceForm.repairOrderId.trim() || undefined,
        currency: invoiceForm.currency.trim().toUpperCase(),
        subtotalCents: parseNonNegativeInt(invoiceForm.subtotalCents),
        taxCents: parseNonNegativeInt(invoiceForm.taxCents),
        discountCents: parseNonNegativeInt(invoiceForm.discountCents),
        dueAt: toIsoDate(invoiceForm.dueAt),
        notes: invoiceForm.notes.trim() || undefined
      });

      setInvoiceForm(INITIAL_FORM);
      await loadInvoices(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible crear la factura.");
    } finally {
      setIsSubmitting(false);
    }
  }

  async function onChangeStatus(invoiceId: string, status: InvoiceStatus) {
    if (!session) {
      return;
    }

    try {
      await updateInvoiceStatus({
        session,
        invoiceId,
        status,
        paidAt: status === "paid" ? new Date().toISOString() : undefined
      });
      await loadInvoices(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible actualizar el estado de la factura.");
    }
  }

  if (!session) {
    return (
      <section className="space-y-4">
        <h1 className="text-3xl font-bold">Facturacion</h1>
        <p className="text-sm text-[var(--muted)]">
          Debes iniciar sesion para operar el modulo Billing. <Link href={"/auth/login" as never}>Ir al login</Link>.
        </p>
      </section>
    );
  }

  return (
    <section className="space-y-6">
      <header>
        <p className="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--muted)]">Billing</p>
        <h1 className="mt-2 text-3xl font-bold">Facturacion y Ciclo de Cobro</h1>
      </header>

      <div className="grid gap-4 xl:grid-cols-[420px_1fr]">
        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <h2 className="text-lg font-semibold">Nueva factura</h2>
          <form className="mt-4 space-y-3" onSubmit={onCreateInvoice}>
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Numero factura (opcional)"
              value={invoiceForm.invoiceNumber}
              onChange={(event) => setInvoiceForm((prev) => ({ ...prev, invoiceNumber: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Customer ID (opcional)"
              value={invoiceForm.customerId}
              onChange={(event) => setInvoiceForm((prev) => ({ ...prev, customerId: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Repair Order ID (opcional)"
              value={invoiceForm.repairOrderId}
              onChange={(event) => setInvoiceForm((prev) => ({ ...prev, repairOrderId: event.target.value }))}
            />
            <div className="grid grid-cols-2 gap-2">
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Moneda (COP)"
                value={invoiceForm.currency}
                maxLength={3}
                onChange={(event) => setInvoiceForm((prev) => ({ ...prev, currency: event.target.value }))}
              />
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                type="date"
                value={invoiceForm.dueAt}
                onChange={(event) => setInvoiceForm((prev) => ({ ...prev, dueAt: event.target.value }))}
              />
            </div>
            <div className="grid grid-cols-3 gap-2">
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Subtotal"
                type="number"
                min={0}
                value={invoiceForm.subtotalCents}
                onChange={(event) => setInvoiceForm((prev) => ({ ...prev, subtotalCents: event.target.value }))}
              />
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Impuestos"
                type="number"
                min={0}
                value={invoiceForm.taxCents}
                onChange={(event) => setInvoiceForm((prev) => ({ ...prev, taxCents: event.target.value }))}
              />
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Descuento"
                type="number"
                min={0}
                value={invoiceForm.discountCents}
                onChange={(event) => setInvoiceForm((prev) => ({ ...prev, discountCents: event.target.value }))}
              />
            </div>
            <textarea
              className="h-24 w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Notas"
              value={invoiceForm.notes}
              onChange={(event) => setInvoiceForm((prev) => ({ ...prev, notes: event.target.value }))}
            />
            <button
              type="submit"
              disabled={!canCreate || isSubmitting}
              className="w-full rounded-lg bg-[var(--accent)] px-3 py-2 text-sm font-semibold text-white disabled:opacity-60"
            >
              {isSubmitting ? "Guardando..." : "Crear factura"}
            </button>
          </form>
        </article>

        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <div className="flex flex-wrap items-center gap-2">
            <input
              className="min-w-[220px] flex-1 rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Buscar por numero o notas"
              value={search}
              onChange={(event) => setSearch(event.target.value)}
            />
            <select
              className="rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={statusFilter}
              onChange={(event) => setStatusFilter(event.target.value as InvoiceStatus | "all")}
            >
              <option value="all">Todos</option>
              {INVOICE_STATUS_OPTIONS.map((status) => (
                <option key={status} value={status}>
                  {STATUS_LABEL[status]}
                </option>
              ))}
            </select>
          </div>

          {errorMessage ? (
            <p className="mt-3 rounded-lg border border-[var(--danger)]/50 bg-[var(--danger)]/10 px-3 py-2 text-xs text-[var(--danger)]">
              {errorMessage}
            </p>
          ) : null}

          <div className="mt-4 overflow-x-auto">
            <table className="w-full min-w-[980px] border-separate border-spacing-y-2 text-sm">
              <thead className="text-left text-xs uppercase tracking-[0.12em] text-[var(--muted)]">
                <tr>
                  <th className="px-3 py-2">Factura</th>
                  <th className="px-3 py-2">Fechas</th>
                  <th className="px-3 py-2">Total</th>
                  <th className="px-3 py-2">Estado</th>
                  <th className="px-3 py-2">Notas</th>
                </tr>
              </thead>
              <tbody>
                {isLoading ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      Cargando facturas...
                    </td>
                  </tr>
                ) : invoices.length === 0 ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      No hay facturas para los filtros actuales.
                    </td>
                  </tr>
                ) : (
                  invoices.map((invoice) => (
                    <tr key={invoice.id} className="rounded-xl border" style={{ borderColor: "var(--border)" }}>
                      <td className="px-3 py-3">
                        <p className="font-semibold">{invoice.invoiceNumber}</p>
                        <p className="text-xs text-[var(--muted)]">{invoice.id.slice(0, 8).toUpperCase()}</p>
                      </td>
                      <td className="px-3 py-3">
                        <p className="text-xs text-[var(--muted)]">
                          Emision:{" "}
                          {invoice.issuedAt ? new Date(invoice.issuedAt).toLocaleDateString("es-CO") : "Pendiente"}
                        </p>
                        <p className="text-xs text-[var(--muted)]">
                          Vence: {invoice.dueAt ? new Date(invoice.dueAt).toLocaleDateString("es-CO") : "Sin fecha"}
                        </p>
                        <p className="text-xs text-[var(--muted)]">
                          Pago: {invoice.paidAt ? new Date(invoice.paidAt).toLocaleDateString("es-CO") : "Pendiente"}
                        </p>
                      </td>
                      <td className="px-3 py-3 font-semibold">{formatCurrency(invoice.totalCents, invoice.currency)}</td>
                      <td className="px-3 py-3">
                        <select
                          className="rounded-lg border bg-transparent px-2 py-1 text-xs"
                          style={{ borderColor: "var(--border)" }}
                          value={invoice.status}
                          onChange={(event) => void onChangeStatus(invoice.id, event.target.value as InvoiceStatus)}
                        >
                          {INVOICE_STATUS_OPTIONS.map((status) => (
                            <option key={status} value={status}>
                              {STATUS_LABEL[status]}
                            </option>
                          ))}
                        </select>
                      </td>
                      <td className="px-3 py-3 text-xs text-[var(--muted)]">{invoice.notes ?? "-"}</td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </article>
      </div>
    </section>
  );
}
