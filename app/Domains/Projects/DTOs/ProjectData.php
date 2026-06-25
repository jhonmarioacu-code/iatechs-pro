<?php

declare(strict_types=1);

namespace App\Domains\Projects\DTOs;

final readonly class ProjectData
{
    public function __construct(
        public int $companyId,
        public ?int $branchId,
        public string $name,
        public ?string $code,
        public ?string $description,
        public ?string $status,
        public array $metadata = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            companyId: (int) $data['company_id'],
            branchId: isset($data['branch_id']) ? (int) $data['branch_id'] : null,
            name: (string) $data['name'],
            code: $data['code'] ?? null,
            description: $data['description'] ?? null,
            status: $data['status'] ?? null,
            metadata: $data['metadata'] ?? []
        );
    }
}
