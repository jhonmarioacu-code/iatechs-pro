import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsDateString, IsInt, IsOptional, IsString, Length, MaxLength, Min } from "class-validator";

export class CreateInvoiceDto {
  @ApiPropertyOptional({ example: "INV-20260627-0001" })
  @IsOptional()
  @IsString()
  @MaxLength(80)
  invoiceNumber?: string;

  @ApiPropertyOptional({ description: "Customer UUID." })
  @IsOptional()
  @IsString()
  customerId?: string;

  @ApiPropertyOptional({ description: "Repair order UUID linked to invoice." })
  @IsOptional()
  @IsString()
  repairOrderId?: string;

  @ApiPropertyOptional({ example: "COP", default: "COP" })
  @IsOptional()
  @IsString()
  @Length(3, 3)
  currency?: string;

  @ApiProperty({ example: 4500000, description: "Subtotal in cents." })
  @IsInt()
  @Min(0)
  subtotalCents!: number;

  @ApiPropertyOptional({ example: 855000, description: "Tax in cents." })
  @IsOptional()
  @IsInt()
  @Min(0)
  taxCents?: number;

  @ApiPropertyOptional({ example: 100000, description: "Discount in cents." })
  @IsOptional()
  @IsInt()
  @Min(0)
  discountCents?: number;

  @ApiPropertyOptional({ example: "2026-07-15T00:00:00.000Z" })
  @IsOptional()
  @IsDateString()
  dueAt?: string;

  @ApiPropertyOptional({ example: "Factura por servicios de reparacion premium." })
  @IsOptional()
  @IsString()
  @MaxLength(2000)
  notes?: string;
}
