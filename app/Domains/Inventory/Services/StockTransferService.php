<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Products\Models\Product;
use App\Domains\Inventory\Models\StockTransfer;
use App\Domains\Inventory\Repositories\StockTransferRepository;

class StockTransferService
{
    public function __construct(
        private StockTransferRepository $repository,
        private InventoryService $inventoryService
    ) {}

    public function paginate()
    {
        return $this->repository
            ->paginate();
    }

    public function create(
        array $data
    ): StockTransfer {

        return DB::transaction(function () use ($data) {

            $data['uuid'] =
                (string) Str::uuid();

            $data['transfer_number'] =
                $this->generateTransferNumber();

            $data['status'] =
                'PENDING';

            return $this->repository
                ->create($data);
        });
    }

    public function approve(
        StockTransfer $transfer,
        int $approvedBy
    ): StockTransfer {

        return $this->repository->update(
            $transfer,
            [
                'status' => 'IN_TRANSIT',
                'approved_by' => $approvedBy,
                'approved_at' => Carbon::now(),
            ]
        );
    }

    public function complete(
        StockTransfer $transfer
    ): StockTransfer {

        DB::transaction(function () use ($transfer) {

            $product = Product::findOrFail(
                $transfer->product_id
            );

            $this->inventoryService->create([

                'company_id' =>
                    $transfer->company_id,

                'branch_id' =>
                    $transfer->from_branch_id,

                'product_id' =>
                    $product->id,

                'user_id' =>
                    $transfer->requested_by,

                'type' =>
                    'TRANSFER_OUT',

                'quantity' =>
                    $transfer->quantity,

                'reference' =>
                    $transfer->transfer_number,

                'reason' =>
                    'Transferencia entre sucursales',
            ]);

            $this->inventoryService->create([

                'company_id' =>
                    $transfer->company_id,

                'branch_id' =>
                    $transfer->to_branch_id,

                'product_id' =>
                    $product->id,

                'user_id' =>
                    $transfer->requested_by,

                'type' =>
                    'TRANSFER_IN',

                'quantity' =>
                    $transfer->quantity,

                'reference' =>
                    $transfer->transfer_number,

                'reason' =>
                    'Recepción transferencia',
            ]);

            $this->repository->update(
                $transfer,
                [
                    'status' => 'COMPLETED',
                    'completed_at' => Carbon::now(),
                ]
            );
        });

        return $transfer->refresh();
    }

    public function cancel(
        StockTransfer $transfer
    ): StockTransfer {

        return $this->repository->update(
            $transfer,
            [
                'status' => 'CANCELLED'
            ]
        );
    }

    private function generateTransferNumber(): string
    {
        return 'TRF-' .
            now()->format('Ymd') .
            '-' .
            strtoupper(
                Str::random(6)
            );
    }
}