<?php

declare(strict_types=1);

namespace App\Domains\Warranties\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Warranties\Models\Warranty;
use App\Domains\Warranties\Repositories\WarrantyRepository;

class WarrantyService
{
    public function __construct(
        private WarrantyRepository $repository
    ) {}

    public function paginate(
        int $perPage = 20
    )
    {
        return $this->repository
            ->paginate($perPage);
    }

    public function create(
        array $data
    ): Warranty {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['warranty_number'] =
                $this->generateNumber();

            $data['status'] =
                $data['status']
                ??
                'ACTIVE';

            return $this->repository
                ->create($data);
        });
    }

    public function update(
        Warranty $warranty,
        array $data
    ): Warranty {

        return $this->repository
            ->update(
                $warranty,
                $data
            );
    }

    public function claim(
        Warranty $warranty
    ): Warranty {

        return $this->repository
            ->update(
                $warranty,
                [
                    'status' => 'CLAIMED'
                ]
            );
    }

    public function expire(
        Warranty $warranty
    ): Warranty {

        return $this->repository
            ->update(
                $warranty,
                [
                    'status' => 'EXPIRED'
                ]
            );
    }

    public function void(
        Warranty $warranty
    ): Warranty {

        return $this->repository
            ->update(
                $warranty,
                [
                    'status' => 'VOID'
                ]
            );
    }

    private function generateNumber(): string
    {
        do {

            $number =
                'WAR-' .
                date('Y') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            $this->repository
                ->existsWarrantyNumber(
                    $number
                )
        );

        return $number;
    }
}