"use client";

import Link from "next/link";
import { FormEvent, useEffect, useMemo, useState } from "react";
import { getStoredSession } from "@/shared/lib/session-storage";
import type { AuthSession } from "@/shared/types/auth";
import type { CrmLead, LeadStatus } from "@/shared/types/crm";
import { createCrmLead, listCrmLeads, updateCrmLeadStatus } from "@/shared/services/crm-service";

const LEAD_STATUS_OPTIONS: LeadStatus[] = ["new", "contacted", "qualified", "proposal_sent", "won", "lost"];

const STATUS_LABEL: Record<LeadStatus, string> = {
  new: "Nuevo",
  contacted: "Contactado",
  qualified: "Calificado",
  proposal_sent: "Propuesta enviada",
  won: "Ganado",
  lost: "Perdido"
};

interface LeadFormState {
  fullName: string;
  email: string;
  phone: string;
  companyName: string;
  source: string;
  notes: string;
  estimatedMonthlyValue: string;
}

const INITIAL_FORM: LeadFormState = {
  fullName: "",
  email: "",
  phone: "",
  companyName: "",
  source: "portal-admin",
  notes: "",
  estimatedMonthlyValue: ""
};

export default function CrmLeadsPage() {
  const [session, setSession] = useState<AuthSession | null>(null);
  const [leads, setLeads] = useState<CrmLead[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState<LeadStatus | "all">("all");
  const [leadForm, setLeadForm] = useState<LeadFormState>(INITIAL_FORM);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const canCreate = useMemo(() => leadForm.fullName.trim().length > 2, [leadForm.fullName]);

  useEffect(() => {
    const activeSession = getStoredSession();
    setSession(activeSession);
  }, []);

  async function loadLeads(activeSession: AuthSession) {
    setIsLoading(true);
    setErrorMessage(null);

    try {
      const response = await listCrmLeads({
        session: activeSession,
        search: search.trim() || undefined,
        status: status === "all" ? undefined : status,
        page: 1,
        pageSize: 50
      });
      setLeads(response.items);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible cargar los leads.");
    } finally {
      setIsLoading(false);
    }
  }

  useEffect(() => {
    if (!session) {
      return;
    }

    void loadLeads(session);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [session, search, status]);

  async function onCreateLead(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!session) {
      setErrorMessage("No hay sesion activa.");
      return;
    }

    setIsSubmitting(true);
    setErrorMessage(null);

    try {
      await createCrmLead({
        session,
        fullName: leadForm.fullName.trim(),
        email: leadForm.email.trim() || undefined,
        phone: leadForm.phone.trim() || undefined,
        companyName: leadForm.companyName.trim() || undefined,
        source: leadForm.source.trim() || undefined,
        notes: leadForm.notes.trim() || undefined,
        estimatedMonthlyValue: leadForm.estimatedMonthlyValue
          ? Number.parseInt(leadForm.estimatedMonthlyValue, 10)
          : undefined
      });

      setLeadForm(INITIAL_FORM);
      await loadLeads(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible crear el lead.");
    } finally {
      setIsSubmitting(false);
    }
  }

  async function onChangeStatus(leadId: string, nextStatus: LeadStatus) {
    if (!session) {
      return;
    }

    try {
      await updateCrmLeadStatus(session, leadId, nextStatus);
      await loadLeads(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible actualizar el estado.");
    }
  }

  if (!session) {
    return (
      <section className="space-y-4">
        <h1 className="text-3xl font-bold">CRM Leads</h1>
        <p className="text-sm text-[var(--muted)]">
          Debes iniciar sesion para operar el modulo CRM. <Link href={"/auth/login" as never}>Ir al login</Link>.
        </p>
      </section>
    );
  }

  return (
    <section className="space-y-6">
      <header className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <p className="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--muted)]">CRM</p>
          <h1 className="mt-2 text-3xl font-bold">Pipeline de Leads</h1>
        </div>
      </header>

      <div className="grid gap-4 xl:grid-cols-[420px_1fr]">
        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <h2 className="text-lg font-semibold">Nuevo lead</h2>
          <form className="mt-4 space-y-3" onSubmit={onCreateLead}>
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Nombre completo"
              value={leadForm.fullName}
              onChange={(event) => setLeadForm((prev) => ({ ...prev, fullName: event.target.value }))}
              required
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Correo"
              type="email"
              value={leadForm.email}
              onChange={(event) => setLeadForm((prev) => ({ ...prev, email: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Telefono"
              value={leadForm.phone}
              onChange={(event) => setLeadForm((prev) => ({ ...prev, phone: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Empresa"
              value={leadForm.companyName}
              onChange={(event) => setLeadForm((prev) => ({ ...prev, companyName: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Valor mensual estimado (centavos)"
              value={leadForm.estimatedMonthlyValue}
              onChange={(event) => setLeadForm((prev) => ({ ...prev, estimatedMonthlyValue: event.target.value }))}
            />
            <textarea
              className="h-24 w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Notas"
              value={leadForm.notes}
              onChange={(event) => setLeadForm((prev) => ({ ...prev, notes: event.target.value }))}
            />
            <button
              type="submit"
              disabled={!canCreate || isSubmitting}
              className="w-full rounded-lg bg-[var(--accent)] px-3 py-2 text-sm font-semibold text-white disabled:opacity-60"
            >
              {isSubmitting ? "Guardando..." : "Crear lead"}
            </button>
          </form>
        </article>

        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <div className="flex flex-wrap items-center gap-2">
            <input
              className="min-w-[220px] flex-1 rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Buscar por nombre, email o empresa"
              value={search}
              onChange={(event) => setSearch(event.target.value)}
            />

            <select
              className="rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={status}
              onChange={(event) => setStatus(event.target.value as LeadStatus | "all")}
            >
              <option value="all">Todos</option>
              {LEAD_STATUS_OPTIONS.map((statusValue) => (
                <option key={statusValue} value={statusValue}>
                  {STATUS_LABEL[statusValue]}
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
            <table className="w-full min-w-[760px] border-separate border-spacing-y-2 text-sm">
              <thead className="text-left text-xs uppercase tracking-[0.12em] text-[var(--muted)]">
                <tr>
                  <th className="px-3 py-2">Lead</th>
                  <th className="px-3 py-2">Empresa</th>
                  <th className="px-3 py-2">Contacto</th>
                  <th className="px-3 py-2">Valor</th>
                  <th className="px-3 py-2">Estado</th>
                </tr>
              </thead>
              <tbody>
                {isLoading ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      Cargando leads...
                    </td>
                  </tr>
                ) : leads.length === 0 ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      No hay leads para los filtros actuales.
                    </td>
                  </tr>
                ) : (
                  leads.map((lead) => (
                    <tr key={lead.id} className="rounded-xl border" style={{ borderColor: "var(--border)" }}>
                      <td className="px-3 py-3">
                        <p className="font-semibold">{lead.fullName}</p>
                        <p className="text-xs text-[var(--muted)]">{lead.source ?? "sin fuente"}</p>
                      </td>
                      <td className="px-3 py-3">{lead.companyName ?? "-"}</td>
                      <td className="px-3 py-3">
                        <p>{lead.email ?? "-"}</p>
                        <p className="text-xs text-[var(--muted)]">{lead.phone ?? ""}</p>
                      </td>
                      <td className="px-3 py-3">
                        {lead.estimatedMonthlyValue ? lead.estimatedMonthlyValue.toLocaleString("es-CO") : "-"}
                      </td>
                      <td className="px-3 py-3">
                        <select
                          className="rounded-lg border bg-transparent px-2 py-1 text-xs"
                          style={{ borderColor: "var(--border)" }}
                          value={lead.status}
                          onChange={(event) => void onChangeStatus(lead.id, event.target.value as LeadStatus)}
                        >
                          {LEAD_STATUS_OPTIONS.map((statusValue) => (
                            <option key={statusValue} value={statusValue}>
                              {STATUS_LABEL[statusValue]}
                            </option>
                          ))}
                        </select>
                      </td>
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
