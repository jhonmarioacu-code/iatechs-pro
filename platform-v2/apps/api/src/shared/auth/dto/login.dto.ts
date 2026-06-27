import { IsEmail, IsOptional, IsString, MaxLength, MinLength } from "class-validator";

export class LoginDto {
  @IsEmail()
  email!: string;

  @IsString()
  @MinLength(8)
  @MaxLength(128)
  password!: string;

  @IsOptional()
  @IsString()
  mfaChallengeId?: string;

  @IsOptional()
  @IsString()
  @MinLength(6)
  @MaxLength(12)
  mfaCode?: string;
}

