<?php

declare(strict_types=1);

namespace App\Domains\Repairs\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Repairs\Models\Repair;
use App\Domains\Repairs\Repositories\RepairRepository;

class RepairService
{
    public function __construct(
        private RepairRepository $repository
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
    ): Repair {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['repair_number'] =
                $this->generateNumber();

            $data['status'] =
                'PENDING';

            $data['labor_cost'] =
                $data['labor_cost'] ?? 0;

            $data['parts_cost'] =
                $data['parts_cost'] ?? 0;

            $data['total_cost'] =
                $data['labor_cost']
                +
                $data['parts_cost'];

            return $this->repository
                ->create($data);
        });
    }

    public function update(
        Repair $repair,
        array $data
    ): Repair {

        if (
            isset($data['labor_cost']) ||
            isset($data['parts_cost'])
        ) {

            $labor =
                $data['labor_cost']
                ??
                $repair->labor_cost;

            $parts =
                $data['parts_cost']
                ??
                $repair->parts_cost;

            $data['total_cost'] =
                $labor + $parts;
        }

        return $this->repository
            ->update(
                $repair,
                $data
            );
    }

    public function assign(
        Repair $repair,
        int $technicianId
    ): Repair {

        return $this->repository
            ->update(
                $repair,
                [
                    'technician_id' => $technicianId,
                    'status' => 'ASSIGNED'
                ]
            );
    }

    public function start(
        Repair $repair
    ): Repair {

        return $this->repository
            ->update(
                $repair,
                [
                    'status' => 'IN_PROGRESS',
                    'started_at' => now()
                ]
            );
    }

    public function complete(
        Repair $repair
    ): Repair {

        return $this->repository
            ->update(
                $repair,
                [
                    'status' => 'COMPLETED',
                    'completed_at' => now()
                ]
            );
    }

    public function deliver(
        Repair $repair
    ): Repair {

        return $this->repository
            ->update(
                $repair,
                [
                    'status' => 'DELIVERED',
                    'delivered_at' => now()
                ]
            );
    }

    public function cancel(
        Repair $repair
    ): Repair {

        return $this->repository
            ->update(
                $repair,
                [
                    'status' => 'CANCELLED'
                ]
            );
    }

    public function delete(
        Repair $repair
    ): bool {

        return $this->repository
            ->delete($repair);
    }

    private function generateNumber(): string
    {
        do {

            $number =
                'RP-' .
                date('Y') .
                '-' .
                strtoupper(
                    Str::random(8)
                );

        } while (
            $this->repository
                ->existsRepairNumber(
                    $number
                )
        );

        return $number;
    }
}