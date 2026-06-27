import { ApiProperty } from "@nestjs/swagger";
import { IsEnum } from "class-validator";
import { RepairOrderStatus } from "../../domain/repair-order-status.enum";

export class UpdateRepairOrderStatusDto {
  @ApiProperty({ enum: RepairOrderStatus })
  @IsEnum(RepairOrderStatus)
  status!: RepairOrderStatus;
}
