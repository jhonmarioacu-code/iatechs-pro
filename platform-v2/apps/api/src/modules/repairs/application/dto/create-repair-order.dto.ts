import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsDateString, IsEnum, IsOptional, IsString, MaxLength } from "class-validator";
import { RepairPriority } from "../../domain/repair-priority.enum";

export class CreateRepairOrderDto {
  @ApiProperty({ example: "laptop" })
  @IsString()
  @MaxLength(60)
  deviceType!: string;

  @ApiPropertyOptional({ example: "Dell" })
  @IsOptional()
  @IsString()
  @MaxLength(80)
  deviceBrand?: string;

  @ApiPropertyOptional({ example: "XPS 15 9530" })
  @IsOptional()
  @IsString()
  @MaxLength(100)
  deviceModel?: string;

  @ApiPropertyOptional({ example: "SN-AX45-9921" })
  @IsOptional()
  @IsString()
  @MaxLength(120)
  serialNumber?: string;

  @ApiProperty({ example: "No enciende y presenta olor a quemado." })
  @IsString()
  @MaxLength(2000)
  issueSummary!: string;

  @ApiPropertyOptional({ enum: RepairPriority, default: RepairPriority.NORMAL })
  @IsOptional()
  @IsEnum(RepairPriority)
  priority?: RepairPriority;

  @ApiPropertyOptional({ example: "counter" })
  @IsOptional()
  @IsString()
  @MaxLength(40)
  intakeChannel?: string;

  @ApiPropertyOptional({ description: "Customer UUID when known." })
  @IsOptional()
  @IsString()
  customerId?: string;

  @ApiPropertyOptional({ description: "Lead UUID when it comes from CRM pipeline." })
  @IsOptional()
  @IsString()
  crmLeadId?: string;

  @ApiPropertyOptional({ description: "Estimated completion in ISO format." })
  @IsOptional()
  @IsDateString()
  estimatedCompletionAt?: string;
}
