import type { ReactNode } from "react";
import { PortalShell } from "@/shared/ui/portal-shell";

export default function AdminPortalLayout({ children }: { children: ReactNode }) {
  return <PortalShell>{children}</PortalShell>;
}
