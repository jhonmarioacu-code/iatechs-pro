<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Companies\Models\Company;
use App\Domains\KnowledgeBase\Models\KnowledgeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'iatechs-demo')->first();

        if (!$company) {
            return;
        }

        foreach (['Soporte Tecnico', 'Operaciones', 'Facturacion'] as $name) {
            KnowledgeCategory::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'slug' => Str::slug($name),
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $name,
                    'description' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
