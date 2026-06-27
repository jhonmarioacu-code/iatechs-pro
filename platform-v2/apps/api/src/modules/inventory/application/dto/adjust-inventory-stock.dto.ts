import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsInt, IsOptional, IsString, MaxLength, Min, NotEquals } from "class-validator";

export class AdjustInventoryStockDto {
  @ApiProperty({ description: "Positive to add stock, negative to subtract.", example: -2 })
  @IsInt()
  @NotEquals(0)
  @Min(-100000)
  delta!: number;

  @ApiPropertyOptional({ example: "Consumed in repair order #RO-2026-0182." })
  @IsOptional()
  @IsString()
  @MaxLength(300)
  reason?: string;
}
