<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $legacy = 'App\\Domains\\Users\\Models\\User';
        $canonical = 'App\\Models\\User';

        DB::table('model_has_roles as legacy')
            ->where('legacy.model_type', $legacy)
            ->whereExists(function ($query) use ($canonical) {
                $query->selectRaw('1')
                    ->from('model_has_roles as canonical')
                    ->whereColumn('canonical.role_id', 'legacy.role_id')
                    ->whereColumn('canonical.model_id', 'legacy.model_id')
                    ->where('canonical.model_type', $canonical);
            })
            ->delete();

        DB::table('model_has_roles')
            ->where('model_type', $legacy)
            ->update(['model_type' => $canonical]);

        DB::table('model_has_permissions as legacy')
            ->where('legacy.model_type', $legacy)
            ->whereExists(function ($query) use ($canonical) {
                $query->selectRaw('1')
                    ->from('model_has_permissions as canonical')
                    ->whereColumn('canonical.permission_id', 'legacy.permission_id')
                    ->whereColumn('canonical.model_id', 'legacy.model_id')
                    ->where('canonical.model_type', $canonical);
            })
            ->delete();

        DB::table('model_has_permissions')
            ->where('model_type', $legacy)
            ->update(['model_type' => $canonical]);
    }

    public function down(): void
    {
        $legacy = 'App\\Domains\\Users\\Models\\User';
        $canonical = 'App\\Models\\User';

        DB::table('model_has_roles')
            ->where('model_type', $canonical)
            ->update(['model_type' => $legacy]);

        DB::table('model_has_permissions')
            ->where('model_type', $canonical)
            ->update(['model_type' => $legacy]);
    }
};
