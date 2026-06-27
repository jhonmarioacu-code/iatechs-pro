# Phase A Foundation Scope

Deliverables initialized:

1. Monorepo and workspace tooling
2. Next.js app baseline
3. NestJS API baseline with global validation and error filter
4. Prisma schema baseline (tenant, user, audit)
5. Worker baseline
6. Local dependencies via Docker Compose
7. Cloud/infra folder contracts
8. CI workflow baseline

## Phase A.1 - IAM Core (implemented)

1. Tenant-aware authentication module in API (`x-tenant-id`).
2. Access and refresh JWT token pair with refresh rotation.
3. Refresh token persistence with hash-at-rest and replay detection.
4. MFA challenge flow with expiring and single-use challenge records.
5. RBAC base with permissions decorator + guard.
6. Prisma schema extended for IAM (`RefreshToken`, `MfaChallenge`, user role/permissions/mfa flags).
7. Seed script for tenant and admin bootstrap (`apps/api/prisma/seed.ts`).
8. Initial Prisma migration for Phase A.1 IAM baseline.

## Phase B.1 - CRM Vertical Slice (in progress)

1. CRM module in API with clean layers:
   - `domain/`
   - `application/`
   - `infrastructure/`
   - `presentation/`
2. Multi-tenant lead endpoints protected with JWT + permissions.
3. CRM data model in Prisma:
   - `Customer`
   - `CrmLead`
   - `CrmInteraction`
4. Frontend enterprise shell with secured portal and CRM Leads page.

## Phase B.2 - Repairs Vertical Slice (in progress)

1. Repairs module in API with clean layers:
   - `domain/`
   - `application/`
   - `infrastructure/`
   - `presentation/`
2. Repairs data model in Prisma:
   - `RepairOrder`
3. Multi-tenant repair order endpoints protected with JWT + permissions.
4. Frontend admin module for repair order intake, tracking and status workflow.

## Phase B.3 - Inventory Vertical Slice (in progress)

1. Inventory module in API with clean layers:
   - `domain/`
   - `application/`
   - `infrastructure/`
   - `presentation/`
2. Inventory data model in Prisma:
   - `InventoryItem`
3. Multi-tenant inventory endpoints protected with JWT + permissions.
4. Frontend admin module for inventory creation, filtering and stock adjustments.

## Phase B.4 - Billing Vertical Slice (in progress)

1. Billing module in API with clean layers:
   - `domain/`
   - `application/`
   - `infrastructure/`
   - `presentation/`
2. Billing data model in Prisma:
   - `Invoice`
3. Multi-tenant billing endpoints protected with JWT + permissions.
4. Frontend admin module for invoice creation, lifecycle transitions and filtering.
