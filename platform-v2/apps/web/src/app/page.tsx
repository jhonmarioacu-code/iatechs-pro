export default function HomePage() {
  return (
    <main className="mx-auto flex min-h-screen w-full max-w-6xl flex-col gap-8 px-6 py-12">
      <section className="rounded-3xl border border-slate-200 bg-white/80 p-8 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/70">
        <p className="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Phase A</p>
        <h1 className="mt-3 text-4xl font-bold">IAtechs Pro V2 Foundation</h1>
        <p className="mt-3 max-w-2xl text-slate-600 dark:text-slate-300">
          Next.js frontend, NestJS backend, Prisma/PostgreSQL, Redis, RabbitMQ, and cloud-ready DevOps baseline.
        </p>
      </section>

      <section className="grid gap-4 md:grid-cols-3">
        {[
          "DDD + Clean Architecture",
          "Multi-tenant by default",
          "CQRS + Event-driven ready"
        ].map((item) => (
          <article key={item} className="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
            <h2 className="text-sm font-semibold">{item}</h2>
          </article>
        ))}
      </section>
    </main>
  );
}
