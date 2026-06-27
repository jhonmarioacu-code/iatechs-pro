import { PrismaClient } from "@prisma/client";
import { hash } from "bcryptjs";

const prisma = new PrismaClient();

function parsePermissions(value: string | undefined): string[] {
  if (!value) {
    return ["dashboard:read", "repairs:read", "crm:read"];
  }

  return value
    .split(",")
    .map((item) => item.trim())
    .filter((item) => item.length > 0);
}

async function main(): Promise<void> {
  const tenantSlug = (process.env.SEED_TENANT_SLUG ?? "acme").trim().toLowerCase();
  const tenantName = (process.env.SEED_TENANT_NAME ?? "Acme Corp").trim();
  const userEmail = (process.env.SEED_ADMIN_EMAIL ?? "admin@acme.test").trim().toLowerCase();
  const userPassword = process.env.SEED_ADMIN_PASSWORD ?? "ChangeMe123!";
  const userFullName = (process.env.SEED_ADMIN_NAME ?? "Platform Administrator").trim();
  const userRole = (process.env.SEED_ADMIN_ROLE ?? "super_admin").trim();
  const permissions = parsePermissions(process.env.SEED_ADMIN_PERMISSIONS);
  const mfaEnabled = process.env.SEED_ADMIN_MFA === "true";

  const tenant = await prisma.tenant.upsert({
    where: { slug: tenantSlug },
    update: {
      name: tenantName,
      status: "active",
      deletedAt: null
    },
    create: {
      slug: tenantSlug,
      name: tenantName,
      status: "active"
    }
  });

  const passwordHash = await hash(userPassword, 12);

  const user = await prisma.user.upsert({
    where: {
      tenantId_email: {
        tenantId: tenant.id,
        email: userEmail
      }
    },
    update: {
      fullName: userFullName,
      role: userRole,
      permissions,
      passwordHash,
      mfaEnabled,
      isActive: true,
      deletedAt: null
    },
    create: {
      tenantId: tenant.id,
      email: userEmail,
      fullName: userFullName,
      passwordHash,
      role: userRole,
      permissions,
      mfaEnabled,
      isActive: true
    }
  });

  console.log("[seed] Tenant ready:", {
    id: tenant.id,
    slug: tenant.slug,
    name: tenant.name
  });

  console.log("[seed] User ready:", {
    id: user.id,
    tenantId: user.tenantId,
    email: user.email,
    role: user.role,
    permissions: user.permissions,
    mfaEnabled: user.mfaEnabled
  });
}

void main()
  .catch((error) => {
    console.error("[seed] Failed:", error);
    process.exitCode = 1;
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
