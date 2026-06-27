import "./globals.css";
import type { ReactNode } from "react";

export const metadata = {
  title: "IAtechs Pro V2",
  description: "Enterprise SaaS platform - Phase A foundation"
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en">
      <body className="min-h-screen antialiased">{children}</body>
    </html>
  );
}
