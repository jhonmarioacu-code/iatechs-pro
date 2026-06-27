import "./globals.css";
import type { ReactNode } from "react";
import { Manrope, Space_Grotesk } from "next/font/google";
import { ThemeBootScript } from "@/shared/ui/theme-boot-script";

const manrope = Manrope({
  subsets: ["latin"],
  variable: "--font-manrope"
});

const spaceGrotesk = Space_Grotesk({
  subsets: ["latin"],
  variable: "--font-space-grotesk"
});

export const metadata = {
  title: "IAtechs Pro V2",
  description: "Enterprise SaaS platform - CRM, ERP, Repairs and AI"
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="es">
      <body className={`${manrope.variable} ${spaceGrotesk.variable} min-h-screen bg-[var(--bg)] font-sans antialiased`}>
        <ThemeBootScript />
        {children}
      </body>
    </html>
  );
}
