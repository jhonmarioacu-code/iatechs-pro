import { ApiProperty } from "@nestjs/swagger";
import { IsEnum } from "class-validator";
import { LeadStatus } from "../../domain/lead-status.enum";

export class UpdateCrmLeadStatusDto {
  @ApiProperty({ enum: LeadStatus })
  @IsEnum(LeadStatus)
  status!: LeadStatus;
}
