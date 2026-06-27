import Link from "next/link";

export default function AdminDashboardPage() {
  const metrics = [
    {
      label: "Leads activos",
      value: "128",
      detail: "Calificados esta semana: 27"
    },
    {
      label: "Ordenes de reparacion",
      value: "42",
      detail: "En proceso: 19"
    },
    {
      label: "Facturacion mensual",
      value: "$ 58.400.000",
      detail: "Crecimiento MoM: +12.8%"
    },
    {
      label: "SLA soporte",
      value: "98.7%",
      detail: "Objetivo enterprise: 99.0%"
    }
  ];

  return (
    <section className="space-y-6">
      <header>
        <p className="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--muted)]">Dashboard</p>
        <h1 className="mt-2 text-3xl font-bold">Panel Ejecutivo</h1>
        <p className="mt-2 max-w-3xl text-sm text-[var(--muted)]">
          Vista central de operaciones para CRM, ERP, reparaciones, facturacion, IA y observabilidad de negocio.
        </p>
      </header>

      <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        {metrics.map((metric) => (
          <article key={metric.label} className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
            <p className="text-xs uppercase tracking-[0.14em] text-[var(--muted)]">{metric.label}</p>
            <h2 className="mt-2 text-2xl font-bold">{metric.value}</h2>
            <p className="mt-2 text-sm text-[var(--muted)]">{metric.detail}</p>
          </article>
        ))}
      </div>

      <section className="grid gap-4 xl:grid-cols-3">
        <article className="rounded-2xl border p-4 xl:col-span-2" style={{ borderColor: "var(--border)" }}>
          <h3 className="text-lg font-semibold">Estado de modulos</h3>
          <div className="mt-3 grid gap-2 sm:grid-cols-2">
            {[
              "CRM",
              "Reparaciones",
              "Inventario",
              "Facturacion",
              "Usuarios y Permisos",
              "Notificaciones",
              "Reportes",
              "IA"
            ].map((moduleName) => (
              <div key={moduleName} className="status-pill flex items-center justify-between">
                <span>{moduleName}</span>
                <span className="text-[var(--accent)]">En progreso</span>
              </div>
            ))}
          </div>
        </article>

        <article className="rounded-2xl border p-4" style={{ borderColor: "var(--border)" }}>
          <h3 className="text-lg font-semibold">Accesos rapidos</h3>
          <ul className="mt-3 space-y-2 text-sm text-[var(--muted)]">
            <li>
              <Link className="hover:text-[var(--fg)]" href={"/portal/admin/crm/leads" as never}>
                Gestionar leads CRM
              </Link>
            </li>
            <li>
              <Link className="hover:text-[var(--fg)]" href={"/portal/admin/reports" as never}>
                Exportar reportes
              </Link>
            </li>
            <li>
              <Link className="hover:text-[var(--fg)]" href={"/portal/admin/settings" as never}>
                Configuracion global
              </Link>
            </li>
          </ul>
        </article>
      </section>
    </section>
  );
}
