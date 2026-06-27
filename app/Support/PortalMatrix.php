<?php

declare(strict_types=1);

namespace App\Support;

use App\Domains\Users\Models\User;
use Throwable;

final class PortalMatrix
{
    /**
     * @return array<string, mixed>
     */
    public static function moduleDefinition(string $portal, string $module): array
    {
        $modules = self::modulesForPortal($portal);

        return is_array($modules[$module] ?? null) ? $modules[$module] : [];
    }

    public static function canAccessPortal(User $user, string $portal): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        $roles = self::portalRoles($portal);

        return $roles !== [] && $user->hasAnyRole($roles);
    }

    public static function canAccessModule(User $user, string $portal, string $module): bool
    {
        if (!self::canAccessPortal($user, $portal)) {
            return false;
        }

        $definition = self::moduleDefinition($portal, $module);

        if ($definition === []) {
            return false;
        }

        if (($definition['state'] ?? 'active') !== 'active') {
            return false;
        }

        if ($portal === 'company' && !$user->hasRole('super_admin') && !PlanAccess::canUseCompanyModule($user, $module)) {
            return false;
        }

        $permission = (string) ($definition['permission'] ?? '');

        if ($permission === '' || $user->hasRole('super_admin')) {
            return true;
        }

        return $user->can($permission);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public static function menuForPortal(User $user, string $portal): array
    {
        $menu = [];

        foreach (self::modulesForPortal($portal) as $slug => $definition) {
            if (!is_array($definition)) {
                continue;
            }

            if (!self::canAccessModule($user, $portal, $slug)) {
                continue;
            }

            $menu[] = [
                'slug' => $slug,
                'label' => (string) ($definition['label'] ?? str($slug)->replace('-', ' ')->title()->toString()),
                'permission' => (string) ($definition['permission'] ?? ''),
                'icon' => (string) ($definition['icon'] ?? 'MD'),
                'href' => self::routeForModule($portal, $slug, $definition),
            ];
        }

        return $menu;
    }

    /**
     * @return array<int, string>
     */
    private static function portalRoles(string $portal): array
    {
        $portals = config('portal_matrix.portals', []);
        $roles = $portals[$portal]['roles'] ?? [];

        return is_array($roles) ? array_values(array_filter($roles, 'is_string')) : [];
    }

    /**
     * @return array<string, mixed>
     */
    private static function modulesForPortal(string $portal): array
    {
        $portals = config('portal_matrix.portals', []);
        $modules = $portals[$portal]['modules'] ?? [];

        return is_array($modules) ? $modules : [];
    }

    /**
     * @param array<string, mixed> $definition
     */
    private static function routeForModule(string $portal, string $module, array $definition): string
    {
        $routeName = (string) ($definition['route_name'] ?? 'portal.module');
        $routeName = str_replace(['{portal}', '{module}'], [$portal, $module], $routeName);

        $params = [];
        $rawParams = $definition['route_params'] ?? [];

        if (is_array($rawParams)) {
            foreach ($rawParams as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    continue;
                }

                $params[$key] = str_replace(['{portal}', '{module}'], [$portal, $module], $value);
            }
        }

        if ($params === [] && $routeName === 'portal.module') {
            $params = ['portal' => $portal, 'module' => $module];
        }

        try {
            return route($routeName, $params);
        } catch (Throwable) {
            return '/portal/'.$portal.'/'.$module;
        }
    }
}
