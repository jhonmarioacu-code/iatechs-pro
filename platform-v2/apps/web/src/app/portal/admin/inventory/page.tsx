"use client";

import Link from "next/link";
import { FormEvent, useEffect, useMemo, useState } from "react";
import { getStoredSession } from "@/shared/lib/session-storage";
import { adjustInventoryStock, createInventoryItem, listInventoryItems } from "@/shared/services/inventory-service";
import type { AuthSession } from "@/shared/types/auth";
import type { InventoryItem, InventoryItemStatus } from "@/shared/types/inventory";

const INVENTORY_STATUS_OPTIONS: InventoryItemStatus[] = ["active", "inactive"];

const STATUS_LABEL: Record<InventoryItemStatus, string> = {
  active: "Activo",
  inactive: "Inactivo"
};

interface InventoryFormState {
  sku: string;
  name: string;
  description: string;
  locationCode: string;
  quantityOnHand: string;
  reorderPoint: string;
  unitCostCents: string;
  status: InventoryItemStatus;
}

const INITIAL_FORM: InventoryFormState = {
  sku: "",
  name: "",
  description: "",
  locationCode: "",
  quantityOnHand: "0",
  reorderPoint: "0",
  unitCostCents: "",
  status: "active"
};

export default function InventoryPage() {
  const [session, setSession] = useState<AuthSession | null>(null);
  const [items, setItems] = useState<InventoryItem[]>([]);
  const [isLoading, setIsLoading] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState<InventoryItemStatus | "all">("all");
  const [itemForm, setItemForm] = useState<InventoryFormState>(INITIAL_FORM);
  const [stockDeltaInput, setStockDeltaInput] = useState<Record<string, string>>({});
  const [stockReasonInput, setStockReasonInput] = useState<Record<string, string>>({});

  const canCreate = useMemo(
    () => itemForm.sku.trim().length > 2 && itemForm.name.trim().length > 2,
    [itemForm.sku, itemForm.name]
  );

  useEffect(() => {
    setSession(getStoredSession());
  }, []);

  async function loadInventory(activeSession: AuthSession) {
    setIsLoading(true);
    setErrorMessage(null);

    try {
      const response = await listInventoryItems({
        session: activeSession,
        search: search.trim() || undefined,
        status: statusFilter === "all" ? undefined : statusFilter,
        page: 1,
        pageSize: 50
      });
      setItems(response.items);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible cargar inventario.");
    } finally {
      setIsLoading(false);
    }
  }

  useEffect(() => {
    if (!session) {
      return;
    }
    void loadInventory(session);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [session, search, statusFilter]);

  async function onCreateInventoryItem(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!session) {
      setErrorMessage("No hay sesion activa.");
      return;
    }

    setIsSubmitting(true);
    setErrorMessage(null);

    try {
      await createInventoryItem({
        session,
        sku: itemForm.sku.trim(),
        name: itemForm.name.trim(),
        description: itemForm.description.trim() || undefined,
        locationCode: itemForm.locationCode.trim() || undefined,
        quantityOnHand: Number.parseInt(itemForm.quantityOnHand, 10),
        reorderPoint: Number.parseInt(itemForm.reorderPoint, 10),
        unitCostCents: itemForm.unitCostCents ? Number.parseInt(itemForm.unitCostCents, 10) : undefined,
        status: itemForm.status
      });

      setItemForm(INITIAL_FORM);
      await loadInventory(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible crear el item.");
    } finally {
      setIsSubmitting(false);
    }
  }

  async function onAdjustStock(itemId: string) {
    if (!session) {
      return;
    }

    const rawDelta = stockDeltaInput[itemId] ?? "0";
    const delta = Number.parseInt(rawDelta, 10);
    if (!Number.isFinite(delta) || delta === 0) {
      setErrorMessage("El ajuste debe ser un numero distinto de cero.");
      return;
    }

    try {
      await adjustInventoryStock({
        session,
        itemId,
        delta,
        reason: stockReasonInput[itemId]?.trim() || undefined
      });

      setStockDeltaInput((prev) => ({ ...prev, [itemId]: "" }));
      setStockReasonInput((prev) => ({ ...prev, [itemId]: "" }));
      await loadInventory(session);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible ajustar stock.");
    }
  }

  if (!session) {
    return (
      <section className="space-y-4">
        <h1 className="text-3xl font-bold">Inventario</h1>
        <p className="text-sm text-[var(--muted)]">
          Debes iniciar sesion para operar el modulo Inventory. <Link href={"/auth/login" as never}>Ir al login</Link>.
        </p>
      </section>
    );
  }

  return (
    <section className="space-y-6">
      <header>
        <p className="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--muted)]">Inventory</p>
        <h1 className="mt-2 text-3xl font-bold">Gestion de Inventario</h1>
      </header>

      <div className="grid gap-4 xl:grid-cols-[420px_1fr]">
        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <h2 className="text-lg font-semibold">Nuevo item</h2>
          <form className="mt-4 space-y-3" onSubmit={onCreateInventoryItem}>
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="SKU"
              value={itemForm.sku}
              onChange={(event) => setItemForm((prev) => ({ ...prev, sku: event.target.value }))}
              required
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Nombre del item"
              value={itemForm.name}
              onChange={(event) => setItemForm((prev) => ({ ...prev, name: event.target.value }))}
              required
            />
            <input
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Ubicacion"
              value={itemForm.locationCode}
              onChange={(event) => setItemForm((prev) => ({ ...prev, locationCode: event.target.value }))}
            />
            <textarea
              className="h-20 w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Descripcion"
              value={itemForm.description}
              onChange={(event) => setItemForm((prev) => ({ ...prev, description: event.target.value }))}
            />
            <div className="grid grid-cols-3 gap-2">
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Stock"
                type="number"
                min={0}
                value={itemForm.quantityOnHand}
                onChange={(event) => setItemForm((prev) => ({ ...prev, quantityOnHand: event.target.value }))}
              />
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Reorden"
                type="number"
                min={0}
                value={itemForm.reorderPoint}
                onChange={(event) => setItemForm((prev) => ({ ...prev, reorderPoint: event.target.value }))}
              />
              <input
                className="rounded-lg border bg-transparent px-3 py-2 text-sm"
                style={{ borderColor: "var(--border)" }}
                placeholder="Costo (cent)"
                type="number"
                min={0}
                value={itemForm.unitCostCents}
                onChange={(event) => setItemForm((prev) => ({ ...prev, unitCostCents: event.target.value }))}
              />
            </div>
            <select
              className="w-full rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={itemForm.status}
              onChange={(event) => setItemForm((prev) => ({ ...prev, status: event.target.value as InventoryItemStatus }))}
            >
              {INVENTORY_STATUS_OPTIONS.map((status) => (
                <option key={status} value={status}>
                  Estado: {STATUS_LABEL[status]}
                </option>
              ))}
            </select>
            <button
              type="submit"
              disabled={!canCreate || isSubmitting}
              className="w-full rounded-lg bg-[var(--accent)] px-3 py-2 text-sm font-semibold text-white disabled:opacity-60"
            >
              {isSubmitting ? "Guardando..." : "Crear item"}
            </button>
          </form>
        </article>

        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <div className="flex flex-wrap items-center gap-2">
            <input
              className="min-w-[220px] flex-1 rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              placeholder="Buscar por SKU, nombre o ubicacion"
              value={search}
              onChange={(event) => setSearch(event.target.value)}
            />
            <select
              className="rounded-lg border bg-transparent px-3 py-2 text-sm"
              style={{ borderColor: "var(--border)" }}
              value={statusFilter}
              onChange={(event) => setStatusFilter(event.target.value as InventoryItemStatus | "all")}
            >
              <option value="all">Todos</option>
              {INVENTORY_STATUS_OPTIONS.map((status) => (
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
                  <th className="px-3 py-2">Item</th>
                  <th className="px-3 py-2">Ubicacion</th>
                  <th className="px-3 py-2">Stock</th>
                  <th className="px-3 py-2">Reorden</th>
                  <th className="px-3 py-2">Ajuste</th>
                </tr>
              </thead>
              <tbody>
                {isLoading ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      Cargando inventario...
                    </td>
                  </tr>
                ) : items.length === 0 ? (
                  <tr>
                    <td className="px-3 py-4 text-[var(--muted)]" colSpan={5}>
                      No hay items para los filtros actuales.
                    </td>
                  </tr>
                ) : (
                  items.map((item) => (
                    <tr key={item.id} className="rounded-xl border" style={{ borderColor: "var(--border)" }}>
                      <td className="px-3 py-3">
                        <p className="font-semibold">{item.name}</p>
                        <p className="text-xs text-[var(--muted)]">{item.sku}</p>
                      </td>
                      <td className="px-3 py-3">{item.locationCode ?? "-"}</td>
                      <td className="px-3 py-3 font-semibold">{item.quantityOnHand.toLocaleString("es-CO")}</td>
                      <td className="px-3 py-3">{item.reorderPoint.toLocaleString("es-CO")}</td>
                      <td className="px-3 py-3">
                        <div className="grid gap-2">
                          <input
                            className="rounded-lg border bg-transparent px-2 py-1 text-xs"
                            style={{ borderColor: "var(--border)" }}
                            type="number"
                            placeholder="+/-"
                            value={stockDeltaInput[item.id] ?? ""}
                            onChange={(event) =>
                              setStockDeltaInput((prev) => ({ ...prev, [item.id]: event.target.value }))
                            }
                          />
                          <input
                            className="rounded-lg border bg-transparent px-2 py-1 text-xs"
                            style={{ borderColor: "var(--border)" }}
                            placeholder="Motivo"
                            value={stockReasonInput[item.id] ?? ""}
                            onChange={(event) =>
                              setStockReasonInput((prev) => ({ ...prev, [item.id]: event.target.value }))
                            }
                          />
                          <button
                            type="button"
                            className="rounded-lg bg-[var(--accent)] px-2 py-1 text-xs font-semibold text-white"
                            onClick={() => void onAdjustStock(item.id)}
                          >
                            Aplicar
                          </button>
                        </div>
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
