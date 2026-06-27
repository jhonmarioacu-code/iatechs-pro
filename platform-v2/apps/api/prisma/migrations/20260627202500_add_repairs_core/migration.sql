-- CreateTable
CREATE TABLE "RepairOrder" (
    "id" TEXT NOT NULL,
    "tenantId" TEXT NOT NULL,
    "customerId" TEXT,
    "crmLeadId" TEXT,
    "intakeChannel" TEXT,
    "deviceType" TEXT NOT NULL,
    "deviceBrand" TEXT,
    "deviceModel" TEXT,
    "serialNumber" TEXT,
    "issueSummary" TEXT NOT NULL,
    "diagnosisSummary" TEXT,
    "status" TEXT NOT NULL DEFAULT 'received',
    "priority" TEXT NOT NULL DEFAULT 'normal',
    "assignedTechnicianId" TEXT,
    "estimatedCompletionAt" TIMESTAMP(3),
    "intakeAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "completedAt" TIMESTAMP(3),
    "createdAt" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updatedAt" TIMESTAMP(3) NOT NULL,
    "deletedAt" TIMESTAMP(3),

    CONSTRAINT "RepairOrder_pkey" PRIMARY KEY ("id")
);

-- CreateIndex
CREATE INDEX "RepairOrder_tenantId_status_priority_createdAt_idx" ON "RepairOrder"("tenantId", "status", "priority", "createdAt");

-- CreateIndex
CREATE INDEX "RepairOrder_tenantId_assignedTechnicianId_idx" ON "RepairOrder"("tenantId", "assignedTechnicianId");

-- CreateIndex
CREATE INDEX "RepairOrder_tenantId_serialNumber_idx" ON "RepairOrder"("tenantId", "serialNumber");

-- CreateIndex
CREATE INDEX "RepairOrder_tenantId_customerId_idx" ON "RepairOrder"("tenantId", "customerId");

-- CreateIndex
CREATE INDEX "RepairOrder_tenantId_crmLeadId_idx" ON "RepairOrder"("tenantId", "crmLeadId");

-- AddForeignKey
ALTER TABLE "RepairOrder" ADD CONSTRAINT "RepairOrder_tenantId_fkey" FOREIGN KEY ("tenantId") REFERENCES "Tenant"("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "RepairOrder" ADD CONSTRAINT "RepairOrder_customerId_fkey" FOREIGN KEY ("customerId") REFERENCES "Customer"("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE "RepairOrder" ADD CONSTRAINT "RepairOrder_crmLeadId_fkey" FOREIGN KEY ("crmLeadId") REFERENCES "CrmLead"("id") ON DELETE SET NULL ON UPDATE CASCADE;
