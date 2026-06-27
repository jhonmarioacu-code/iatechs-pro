interface ModulePlaceholderPageProps {
  params: Promise<{
    module: string;
  }>;
}

function toTitle(value: string): string {
  return value
    .split("-")
    .map((segment) => segment.charAt(0).toUpperCase() + segment.slice(1))
    .join(" ");
}

export default async function ModulePlaceholderPage({ params }: ModulePlaceholderPageProps) {
  const resolvedParams = await params;

  return (
    <section className="space-y-4">
      <p className="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--muted)]">Modulo</p>
      <h1 className="text-3xl font-bold">{toTitle(resolvedParams.module)}</h1>
      <p className="max-w-2xl text-sm text-[var(--muted)]">
        Esta area ya tiene espacio reservado en la arquitectura Enterprise V2. El cierre funcional completo de este
        modulo se implementa en las siguientes iteraciones de desarrollo.
      </p>
      <div className="status-pill inline-flex">
        Estado actual: Base visual lista, pendiente logica de negocio.
      </div>
    </section>
  );
}
