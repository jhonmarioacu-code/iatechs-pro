import { Body, Controller, Get, HttpCode, Post, Req, UnauthorizedException, UseGuards } from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";
import type { Request } from "express";
import { CurrentUser } from "./decorators/current-user.decorator";
import { LoginDto } from "./dto/login.dto";
import { LogoutDto } from "./dto/logout.dto";
import { RefreshTokenDto } from "./dto/refresh-token.dto";
import { JwtAuthGuard } from "./guards/jwt-auth.guard";
import type { RequestUser } from "./interfaces/request-user.interface";
import { AuthService } from "./auth.service";

@ApiTags("auth")
@Controller("api/v1/auth")
export class AuthController {
  constructor(private readonly authService: AuthService) {}

  @Post("login")
  @HttpCode(200)
  login(@Body() dto: LoginDto, @Req() request: Request) {
    return this.authService.login(dto, request.tenantId);
  }

  @Post("refresh")
  @HttpCode(200)
  refresh(@Body() dto: RefreshTokenDto, @Req() request: Request) {
    return this.authService.refresh(dto, request.tenantId);
  }

  @ApiBearerAuth()
  @UseGuards(JwtAuthGuard)
  @Post("logout")
  @HttpCode(200)
  logout(@CurrentUser() currentUser: RequestUser | undefined, @Body() dto: LogoutDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.authService.logout(currentUser, dto);
  }

  @ApiBearerAuth()
  @UseGuards(JwtAuthGuard)
  @Get("me")
  me(@CurrentUser() currentUser: RequestUser | undefined) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.authService.me(currentUser);
  }
}
