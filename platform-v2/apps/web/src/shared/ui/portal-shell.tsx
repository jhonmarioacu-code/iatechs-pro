"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import type { ReactNode } from "react";
import { useEffect, useMemo, useState } from "react";
import { clearSession, getStoredSession } from "../lib/session-storage";
import type { AuthSession } from "../types/auth";
import { ThemeToggle } from "./theme-toggle";

interface PortalShellProps {
  children: ReactNode;
}

interface NavigationLink {
  label: string;
  href: string;
}

const CORE_LINKS: NavigationLink[] = [
  { label: "Dashboard", href: "/portal/admin" },
  { label: "CRM Leads", href: "/portal/admin/crm/leads" },
  { label: "Reparaciones", href: "/portal/admin/repairs" },
  { label: "Inventario", href: "/portal/admin/inventory" },
  { label: "Facturacion", href: "/portal/admin/billing" },
  { label: "Usuarios", href: "/portal/admin/users" },
  { label: "IA", href: "/portal/admin/ai" },
  { label: "Reportes", href: "/portal/admin/reports" },
  { label: "Notificaciones", href: "/portal/admin/notifications" },
  { label: "Configuracion", href: "/portal/admin/settings" }
];

export function PortalShell({ children }: PortalShellProps) {
  const router = useRouter();
  const pathname = usePathname();
  const [session, setSession] = useState<AuthSession | null>(null);
  const [isReady, setIsReady] = useState(false);

  useEffect(() => {
    const activeSession = getStoredSession();
    setSession(activeSession);
    setIsReady(true);

    if (!activeSession) {
      router.replace("/auth/login" as never);
    }
  }, [router]);

  const userLabel = useMemo(() => {
    if (!session) {
      return "Sin sesion";
    }

    return `${session.user.fullName} (${session.user.role})`;
  }, [session]);

  if (!isReady) {
    return <main className="min-h-screen" />;
  }

  if (!session) {
    return null;
  }

  return (
    <div className="min-h-screen">
      <div className="mx-auto grid min-h-screen max-w-[1440px] grid-cols-1 gap-4 p-4 lg:grid-cols-[280px_1fr]">
        <aside className="glass rounded-2xl p-5">
          <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--muted)]">IAtechs Pro V2</p>
          <h1 className="mt-2 text-2xl font-bold">Control Center</h1>
          <p className="mt-3 text-xs text-[var(--muted)]">{session.tenantId}</p>

          <nav className="mt-6 flex flex-col gap-2">
            {CORE_LINKS.map((item) => {
              const isActive = pathname === item.href;
              return (
                <Link
                  key={item.href}
                  href={item.href as never}
                  className={`rounded-xl px-3 py-2 text-sm font-medium transition ${
                    isActive ? "bg-[var(--accent)] text-white" : "hover:bg-[var(--bg-elevated-2)]"
                  }`}
                >
                  {item.label}
                </Link>
              );
            })}
          </nav>
        </aside>

        <div className="flex min-h-full flex-col gap-4">
          <header className="glass flex flex-wrap items-center justify-between gap-3 rounded-2xl px-5 py-4">
            <div>
              <p className="text-xs uppercase tracking-[0.12em] text-[var(--muted)]">Sesion activa</p>
              <p className="text-sm font-semibold">{userLabel}</p>
            </div>

            <div className="flex items-center gap-2">
              <ThemeToggle />
              <button
                type="button"
                className="rounded-lg border border-[var(--border)] px-3 py-2 text-xs font-semibold uppercase tracking-[0.1em] text-[var(--danger)] transition hover:bg-[var(--bg-elevated-2)]"
                onClick={() => {
                  clearSession();
                  router.replace("/auth/login" as never);
                }}
              >
                Cerrar Sesion
              </button>
            </div>
          </header>

          <main className="glass flex-1 rounded-2xl p-5">{children}</main>
        </div>
      </div>
    </div>
  );
}
