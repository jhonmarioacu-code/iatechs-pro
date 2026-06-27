import Link from "next/link";

export default function HomePage() {
  return (
    <main className="mx-auto flex min-h-screen w-full max-w-6xl flex-col justify-center gap-8 px-6 py-12">
      <section className="glass rounded-3xl p-8 shadow-sm md:p-10">
        <p className="text-xs font-extrabold uppercase tracking-[0.24em] text-[var(--accent)]">IAtechs Pro V2</p>
        <h1 className="mt-3 text-4xl font-bold md:text-5xl">Enterprise SaaS Platform</h1>
        <p className="mt-4 max-w-3xl text-sm text-[var(--muted)] md:text-base">
          CRM, ERP, Reparaciones, Facturacion e IA en una arquitectura multi-tenant, segura y preparada para escalar.
        </p>

        <div className="mt-8 flex flex-wrap gap-3">
          <Link
            href={"/auth/login" as never}
            className="rounded-xl bg-[var(--accent)] px-5 py-3 text-sm font-semibold text-white transition hover:brightness-110"
          >
            Iniciar Sesion
          </Link>
          <Link
            href={"/portal/admin" as never}
            className="rounded-xl border px-5 py-3 text-sm font-semibold transition hover:bg-[var(--bg-elevated-2)]"
            style={{ borderColor: "var(--border)" }}
          >
            Abrir Dashboard
          </Link>
        </div>
      </section>

      <section className="grid gap-4 md:grid-cols-3">
        {[
          {
            title: "Arquitectura",
            value: "DDD + Clean + CQRS",
            detail: "Modular monolith listo para evolucionar a microservicios."
          },
          {
            title: "Seguridad",
            value: "JWT + MFA + RBAC",
            detail: "Control granular por tenant, rol y permisos."
          },
          {
            title: "Operacion",
            value: "Observabilidad integral",
            detail: "Prometheus/Grafana, auditoria y runbooks de despliegue."
          }
        ].map((card) => (
          <article key={card.title} className="glass rounded-2xl p-5">
            <p className="text-xs font-semibold uppercase tracking-[0.16em] text-[var(--muted)]">{card.title}</p>
            <h2 className="mt-2 text-lg font-semibold">{card.value}</h2>
            <p className="mt-2 text-sm text-[var(--muted)]">{card.detail}</p>
          </article>
        ))}
      </section>
    </main>
  );
}
