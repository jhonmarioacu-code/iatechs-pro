"use client";

import { useTheme } from "../hooks/use-theme";

export function ThemeToggle() {
  const { theme, isReady, toggleTheme } = useTheme();

  return (
    <button
      type="button"
      onClick={toggleTheme}
      className="rounded-lg border px-3 py-2 text-xs font-semibold uppercase tracking-[0.12em] transition hover:bg-[var(--bg-elevated-2)]"
      style={{ borderColor: "var(--border)" }}
      aria-label="Cambiar tema"
    >
      {isReady ? (theme === "dark" ? "Modo Claro" : "Modo Oscuro") : "Tema"}
    </button>
  );
}
