import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsEnum, IsInt, IsOptional, IsString, MaxLength, Min } from "class-validator";
import { InventoryItemStatus } from "../../domain/inventory-item-status.enum";

export class CreateInventoryItemDto {
  @ApiProperty({ example: "INV-KEYBOARD-001" })
  @IsString()
  @MaxLength(80)
  sku!: string;

  @ApiProperty({ example: "Mechanical Keyboard RGB" })
  @IsString()
  @MaxLength(140)
  name!: string;

  @ApiPropertyOptional({ example: "Switches red, layout ES." })
  @IsOptional()
  @IsString()
  @MaxLength(2000)
  description?: string;

  @ApiPropertyOptional({ example: "BOD-A1-RACK-04" })
  @IsOptional()
  @IsString()
  @MaxLength(80)
  locationCode?: string;

  @ApiPropertyOptional({ default: 0 })
  @IsOptional()
  @IsInt()
  @Min(0)
  quantityOnHand?: number;

  @ApiPropertyOptional({ default: 0 })
  @IsOptional()
  @IsInt()
  @Min(0)
  reorderPoint?: number;

  @ApiPropertyOptional({ description: "Unit cost in cents.", example: 159900 })
  @IsOptional()
  @IsInt()
  @Min(0)
  unitCostCents?: number;

  @ApiPropertyOptional({ enum: InventoryItemStatus, default: InventoryItemStatus.ACTIVE })
  @IsOptional()
  @IsEnum(InventoryItemStatus)
  status?: InventoryItemStatus;
}
