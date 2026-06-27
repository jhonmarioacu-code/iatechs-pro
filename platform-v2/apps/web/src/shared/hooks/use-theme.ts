"use client";

import { useEffect, useState } from "react";

type ThemeMode = "light" | "dark";

export function useTheme() {
  const [theme, setTheme] = useState<ThemeMode>("light");
  const [isReady, setIsReady] = useState(false);

  useEffect(() => {
    const root = window.document.documentElement;
    const savedTheme = window.localStorage.getItem("iatechs-theme");
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    const nextTheme: ThemeMode = savedTheme === "dark" || (!savedTheme && prefersDark) ? "dark" : "light";
    setTheme(nextTheme);
    root.classList.toggle("dark", nextTheme === "dark");
    setIsReady(true);
  }, []);

  function toggleTheme() {
    const root = window.document.documentElement;
    const nextTheme: ThemeMode = theme === "dark" ? "light" : "dark";
    setTheme(nextTheme);
    root.classList.toggle("dark", nextTheme === "dark");
    window.localStorage.setItem("iatechs-theme", nextTheme);
  }

  return {
    theme,
    isReady,
    toggleTheme
  };
}
