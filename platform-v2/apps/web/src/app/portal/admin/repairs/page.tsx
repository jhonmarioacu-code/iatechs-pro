"use client";

import Link from "next/link";
import { FormEvent, useEffect, useMemo, useState } from "react";
import { getStoredSession } from "@/shared/lib/session-storage";
import { createRepairOrder, listRepairOrders, updateRepairOrderStatus } from "@/shared/services/repairs-service";
import type { AuthSession } from "@/shared/types/auth";
import type { RepairOrder, RepairOrderStatus, RepairPriority } from "@/shared/types/repairs";

const ORDER_STATUS_OPTIONS: RepairOrderStatus[] = [
  "received",
  "diagnosing",
  "waiting_parts",
  "in_repair",
  "ready_delivery",
  "delivered",
  "canceled"
];

const PRIORITY_OPTIONS: RepairPriority[] = ["low", "normal", "high", "critical"];

const STATUS_LABEL: Record<RepairOrderStatus, string> = {
  received: "Recibida",
  diagnosing: "En diagnostico",
  waiting_parts: "Esperando repuestos",
  in_repair: "En reparacion",
  ready_delivery: "Lista para entrega",
  delivered: "Entregada",
  canceled: "Cancelada"
};

const PRIORITY_LABEL: Record<RepairPriority, string> = {
  low: "Baja",
  normal: "Normal",
  high: "Alta",
  critical: "Critica"
};

interface RepairFormState {
  deviceType: string;
  deviceBrand: string;
  deviceModel: string;
  serialNumber: string;
  issueSummary: string;
  priority: RepairPriority;
}

const INITIAL_FORM: RepairFormState = {
  deviceType: "laptop",
  deviceBrand: "",
  deviceModel: "",
  serialNumber: "",
  issueSummary: "",
  priority: "normal"
};

export default function RepairsPage() {
  const [session, setSession] = useState<AuthSession | null>(null);
  const [orders, setOrders] = useState<RepairOrder[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState<RepairOrderStatus | "all">("all");
  const [priorityFilter, setPriorityFilter] = useState<RepairPriority | "all">("all");
  const [repairForm, setRepairForm] = useState<RepairFormState>(INITIAL_FORM);

  const canCreate = useMemo(
    () => repairForm.deviceType.trim().length > 1 && repairForm.issueSummary.trim().length > 6,
    [repairForm.deviceType, repairForm.issueSummary]
  );

  useEffect(() => {
    setSession(getStoredSession());
  }, []);

  async function loadRepairOrders(activeSession: AuthSession) {
    setIsLoading(true);
    setErrorMessage(null);

    try {
      const response = await listRepairOrders({
        session: activeSession,
        search: search.trim() || undefined,
        status: statusFilter === "all" ? undefined : statusFilter,
        priority: priorityFilter === "all" ? undefined : priorityFilter,
        page: 1,
        pageSize: 50
      });
      setOrders(response.items);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible cargar las ordenes.");
    } finally {
      setIsLoading(false);
    }
  }

  useEffect(() => {
    if (!session) {
      return;
    }

    void loadRepairOrders(session);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [session, search, statusFilter, priorityFilter]);

  async function onCreateRepairOrder(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!session) {
      setErrorMessage("No hay sesion activa.");
      return;
    }

    setIsSubmitting(true);
    setErrorMessage(null);

    try {
      await createRepairOrder({
        session,
        deviceType: repairForm.deviceType.trim(),
        deviceBrand: repairForm.deviceBrand.trim() || undefined,
        deviceModel: repairForm.deviceModel.trim() || undefined,
        serialNumber: repairForm.serialNumber.trim() || undefined,
        issueSummary: repairForm.issueSummary.trim(),
        priority: repairForm.priority
      });

      setRepairForm(INITIAL_FORM);
      await loadRepairOrders(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible crear la orden de reparacion.");
    } finally {
      setIsSubmitting(false);
    }
  }

  async function onChangeStatus(orderId: string, status: RepairOrderStatus) {
    if (!session) {
      return;
    }

    try {
      await updateRepairOrderStatus(session, orderId, status);
      await loadRepairOrders(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible actualizar el estado.");
    }
  }

  if (!session) {
    return (
      <section className="space-y-4">
        <h1 className="text-3xl font-bold">Reparaciones</h1>
        <p className="text-sm text-[var(--muted)]">
          Debes iniciar sesion para operar el modulo Repairs. <Link href={"/auth/login" as never}>Ir al login</Link>.
        </p>
      </section>
    );
  }

  return (
    <section className="space-y-6">
      <header>
        <p className="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--muted)]">Repairs</p>
        <h1 className="mt-2 text-3xl font-bold">Ordenes de Reparacion</h1>
      </header>

      <div className="grid gap-4 xl:grid-cols-[420px_1fr]">
        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <h2 className="text-lg font-semibold">Nueva orden</h2>
          <form className="mt-4 space-y-3" onSubmit={onCreateRepairOrder}>
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Tipo de dispositivo (ej: laptop)"
              value={repairForm.deviceType}
              onChange={(event) => setRepairForm((prev) => ({ ...prev, deviceType: event.target.value }))}
              required
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Marca"
              value={repairForm.deviceBrand}
              onChange={(event) => setRepairForm((prev) => ({ ...prev, deviceBrand: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Modelo"
              value={repairForm.deviceModel}
              onChange={(event) => setRepairForm((prev) => ({ ...prev, deviceModel: event.target.value }))}
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Serial"
              value={repairForm.serialNumber}
              onChange={(event) => setRepairForm((prev) => ({ ...prev, serialNumber: event.target.value }))}
            />
            <select
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={repairForm.priority}
              onChange={(event) => setRepairForm((prev) => ({ ...prev, priority: event.target.value as RepairPriority }))}
            >
              {PRIORITY_OPTIONS.map((priority) => (
                <option key={priority} value={priority}>
                  Prioridad: {PRIORITY_LABEL[priority]}
                </option>
              ))}
            </select>
            <textarea
              className="h-24 w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Resumen de la falla"
              value={repairForm.issueSummary}
              onChange={(event) => setRepairForm((prev) => ({ ...prev, issueSummary: event.target.value }))}
              required
            />
            <button
              type="submit"
              disabled={!canCreate || isSubmitting}
              className="w-full rounded-lg bg-[var(--accent)] px-3 py-2 text-sm font-semibold text-white disabled:opacity-60"
            >
              {isSubmitting ? "Guardando..." : "Crear orden"}
            </button>
          </form>
        </article>

        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <div className="flex flex-wrap items-center gap-2">
            <input
              className="min-w-[200px] flex-1 rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Buscar por equipo, serial o falla"
              value={search}
              onChange={(event) => setSearch(event.target.value)}
            />
            <select
              className="rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={statusFilter}
              onChange={(event) => setStatusFilter(event.target.value as RepairOrderStatus | "all")}
            >
              <option value="all">Todos los estados</option>
              {ORDER_STATUS_OPTIONS.map((status) => (
                <option key={status} value={status}>
                  {STATUS_LABEL[status]}
                </option>
              ))}
            </select>
            <select
              className="rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={priorityFilter}
              onChange={(event) => setPriorityFilter(event.target.value as RepairPriority | "all")}
            >
              <option value="all">Toda prioridad</option>
              {PRIORITY_OPTIONS.map((priority) => (
                <option key={priority} value={priority}>
                  {PRIORITY_LABEL[priority]}
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
            <table className="w-full min-w-[860px] border-separate border-spacing-y-2 text-sm">
              <thead className="text-left text-xs uppercase tracking-[0.12em] text-[var(--muted)]">
                <tr>
                  <th className="px-3 py-2">Orden</th>
                  <th className="px-3 py-2">Equipo</th>
                  <th className="px-3 py-2">Falla</th>
                  <th className="px-3 py-2">Prioridad</th>
                  <th className="px-3 py-2">Estado</th>
                </tr>
              </thead>
              <tbody>
                {isLoading ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      Cargando ordenes...
                    </td>
                  </tr>
                ) : orders.length === 0 ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      No hay ordenes para los filtros actuales.
                    </td>
                  </tr>
                ) : (
                  orders.map((order) => (
                    <tr key={order.id} className="rounded-xl border" style={{ borderColor: "var(--border)" }}>
                      <td className="px-3 py-3">
                        <p className="font-semibold">{order.id.slice(0, 8).toUpperCase()}</p>
                        <p className="text-xs text-[var(--muted)]">{new Date(order.createdAt).toLocaleString("es-CO")}</p>
                      </td>
                      <td className="px-3 py-3">
                        <p>{order.deviceType}</p>
                        <p className="text-xs text-[var(--muted)]">
                          {[order.deviceBrand, order.deviceModel, order.serialNumber].filter(Boolean).join(" | ") || "-"}
                        </p>
                      </td>
                      <td className="px-3 py-3">{order.issueSummary}</td>
                      <td className="px-3 py-3">{PRIORITY_LABEL[order.priority]}</td>
                      <td className="px-3 py-3">
                        <select
                          className="rounded-lg border bg-transparent px-2 py-1 text-xs"
                          style={{ borderColor: "var(--border)" }}
                          value={order.status}
                          onChange={(event) => void onChangeStatus(order.id, event.target.value as RepairOrderStatus)}
                        >
                          {ORDER_STATUS_OPTIONS.map((status) => (
                            <option key={status} value={status}>
                              {STATUS_LABEL[status]}
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
