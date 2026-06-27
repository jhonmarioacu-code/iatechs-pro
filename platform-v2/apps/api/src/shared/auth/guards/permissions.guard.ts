import { CanActivate, ExecutionContext, ForbiddenException, Injectable, UnauthorizedException } from "@nestjs/common";
import { Reflector } from "@nestjs/core";
import { PERMISSIONS_KEY } from "../decorators/permissions.decorator";
import type { RequestUser } from "../interfaces/request-user.interface";

@Injectable()
export class PermissionsGuard implements CanActivate {
  constructor(private readonly reflector: Reflector) {}

  canActivate(context: ExecutionContext): boolean {
    const requiredPermissions = this.reflector.getAllAndOverride<string[]>(PERMISSIONS_KEY, [
      context.getHandler(),
      context.getClass()
    ]);

    if (!requiredPermissions || requiredPermissions.length === 0) {
      return true;
    }

    const request = context.switchToHttp().getRequest<{ user?: RequestUser }>();
    const currentUser = request.user;

    if (!currentUser) {
      throw new UnauthorizedException("User context is missing");
    }

    if (currentUser.role === "super_admin") {
      return true;
    }

    const granted = new Set(currentUser.permissions ?? []);
    const hasAllPermissions = requiredPermissions.every((permission) => granted.has(permission));

    if (!hasAllPermissions) {
      throw new ForbiddenException("Insufficient permissions");
    }

    return true;
  }
}

