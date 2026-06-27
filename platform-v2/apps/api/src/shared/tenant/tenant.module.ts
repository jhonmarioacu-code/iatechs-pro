import { MiddlewareConsumer, Module, NestModule } from "@nestjs/common";
import { TenantResolverMiddleware } from "./tenant.resolver.middleware";

@Module({})
export class TenantModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
    consumer.apply(TenantResolverMiddleware).forRoutes("*");
  }
}
