import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsDateString, IsEnum, IsOptional } from "class-validator";
import { InvoiceStatus } from "../../domain/invoice-status.enum";

export class UpdateInvoiceStatusDto {
  @ApiProperty({ enum: InvoiceStatus })
  @IsEnum(InvoiceStatus)
  status!: InvoiceStatus;

  @ApiPropertyOptional({ example: "2026-07-08T12:45:00.000Z" })
  @IsOptional()
  @IsDateString()
  paidAt?: string;
}
