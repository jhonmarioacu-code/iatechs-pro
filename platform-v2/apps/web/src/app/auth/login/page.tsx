"use client";

import { useRouter } from "next/navigation";
import { FormEvent, useState } from "react";
import { storeSession } from "@/shared/lib/session-storage";
import { login } from "@/shared/services/auth-service";
import type { LoginResponse } from "@/shared/types/auth";

export default function LoginPage() {
  const router = useRouter();
  const [tenantId, setTenantId] = useState("acme");
  const [email, setEmail] = useState("admin@acme.test");
  const [password, setPassword] = useState("ChangeMe123!");
  const [mfaChallengeId, setMfaChallengeId] = useState<string | undefined>();
  const [mfaCode, setMfaCode] = useState("");
  const [developmentCode, setDevelopmentCode] = useState<string | undefined>();
  const [isLoading, setIsLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setErrorMessage(null);
    setIsLoading(true);

    try {
      const response: LoginResponse = await login({
        tenantId,
        email,
        password,
        mfaChallengeId,
        mfaCode: mfaCode.trim() || undefined
      });

      if (response.mfaRequired) {
        setMfaChallengeId(response.mfaChallengeId);
        setDevelopmentCode(response.developmentCode);
        setErrorMessage("Se requiere codigo MFA para continuar.");
        return;
      }

      storeSession({
        tenantId,
        tokenType: response.tokenType,
        accessToken: response.accessToken,
        refreshToken: response.refreshToken,
        expiresIn: response.expiresIn,
        user: response.user
      });

      router.replace("/portal/admin" as never);
    } catch (error) {
      setErrorMessage(error instanceof Error ? error.message : "No fue posible iniciar sesion.");
    } finally {
      setIsLoading(false);
    }
  }

  return (
    <main className="mx-auto flex min-h-screen w-full max-w-xl items-center px-6 py-12">
      <section className="glass w-full rounded-3xl p-8 shadow-sm">
        <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--accent)]">Acceso seguro</p>
        <h1 className="mt-2 text-3xl font-bold">Iniciar sesion en IAtechs Pro V2</h1>
        <p className="mt-3 text-sm text-[var(--muted)]">
          Plataforma enterprise multi-tenant con autenticacion JWT, MFA y permisos granulares.
        </p>

        <form className="mt-7 space-y-4" onSubmit={onSubmit}>
          <div>
            <label className="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--muted)]" htmlFor="tenantId">
              Tenant
            </label>
            <input
              id="tenantId"
              value={tenantId}
              onChange={(event) => setTenantId(event.target.value)}
              className="mt-1 w-full rounded-xl border bg-transparent px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[var(--accent)]"
              style={{ borderColor: "var(--border)" }}
              required
            />
          </div>

          <div>
            <label className="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--muted)]" htmlFor="email">
              Correo
            </label>
            <input
              id="email"
              type="email"
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              className="mt-1 w-full rounded-xl border bg-transparent px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[var(--accent)]"
              style={{ borderColor: "var(--border)" }}
              required
            />
          </div>

          <div>
            <label className="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--muted)]" htmlFor="password">
              Contrasena
            </label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(event) => setPassword(event.target.value)}
              className="mt-1 w-full rounded-xl border bg-transparent px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[var(--accent)]"
              style={{ borderColor: "var(--border)" }}
              required
            />
          </div>

          {mfaChallengeId ? (
            <div>
              <label className="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--muted)]" htmlFor="mfaCode">
                Codigo MFA
              </label>
              <input
                id="mfaCode"
                value={mfaCode}
                onChange={(event) => setMfaCode(event.target.value)}
                className="mt-1 w-full rounded-xl border bg-transparent px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[var(--accent)]"
                style={{ borderColor: "var(--border)" }}
                placeholder="000000"
                required
              />
              {developmentCode ? (
                <p className="mt-2 text-xs text-[var(--muted)]">Codigo de desarrollo: {developmentCode}</p>
              ) : null}
            </div>
          ) : null}

          {errorMessage ? (
            <p className="rounded-xl border border-[var(--danger)]/40 bg-[var(--danger)]/10 px-3 py-2 text-xs text-[var(--danger)]">
              {errorMessage}
            </p>
          ) : null}

          <button
            type="submit"
            disabled={isLoading}
            className="w-full rounded-xl bg-[var(--accent)] px-4 py-3 text-sm font-semibold text-white transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-65"
          >
            {isLoading ? "Validando..." : "Entrar al Portal"}
          </button>
        </form>
      </section>
    </main>
  );
}
