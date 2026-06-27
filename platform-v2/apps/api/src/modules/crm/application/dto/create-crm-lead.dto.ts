import { ApiProperty, ApiPropertyOptional } from "@nestjs/swagger";
import { IsEmail, IsInt, IsOptional, IsString, MaxLength, Min } from "class-validator";

export class CreateCrmLeadDto {
  @ApiProperty({ example: "Mariana Torres" })
  @IsString()
  @MaxLength(120)
  fullName!: string;

  @ApiPropertyOptional({ example: "mariana@example.com" })
  @IsOptional()
  @IsEmail()
  email?: string;

  @ApiPropertyOptional({ example: "+57 300 123 4567" })
  @IsOptional()
  @IsString()
  @MaxLength(32)
  phone?: string;

  @ApiPropertyOptional({ example: "ACME Logistics" })
  @IsOptional()
  @IsString()
  @MaxLength(140)
  companyName?: string;

  @ApiPropertyOptional({ example: "landing-page" })
  @IsOptional()
  @IsString()
  @MaxLength(60)
  source?: string;

  @ApiPropertyOptional({ example: "Lead interesado en plan enterprise y SLA 24/7." })
  @IsOptional()
  @IsString()
  @MaxLength(2000)
  notes?: string;

  @ApiPropertyOptional({
    description: "Estimated monthly value in cents to avoid floating point issues.",
    example: 2500000
  })
  @IsOptional()
  @IsInt()
  @Min(0)
  estimatedMonthlyValue?: number;
}
