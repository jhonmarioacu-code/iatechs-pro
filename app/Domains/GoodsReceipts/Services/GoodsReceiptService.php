<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\Inventory\Services\InventoryService;
use App\Domains\GoodsReceipts\Models\GoodsReceipt;
use App\Domains\GoodsReceipts\Models\GoodsReceiptItem;
use App\Domains\GoodsReceipts\Repositories\GoodsReceiptRepository;

class GoodsReceiptService
{
    public function __construct(
        private GoodsReceiptRepository $repository,
        private InventoryService $inventoryService
    ) {}

    public function paginate()
    {
        return $this->repository->paginate();
    }

    public function create(
        array $data
    ): GoodsReceipt {

        return DB::transaction(function () use ($data) {

            $subtotal = 0;

            foreach ($data['items'] as $item) {

                $subtotal += $item['subtotal'];
            }

            $receipt = $this->repository->create([

                'uuid' => (string) Str::uuid(),

                'company_id' => $data['company_id'],

                'purchase_order_id' =>
                    $data['purchase_order_id'],

                'supplier_id' =>
                    $data['supplier_id'],

                'received_by' =>
                    $data['received_by'],

                'receipt_number' =>
                    $this->generateReceiptNumber(),

                'status' => 'DRAFT',

                'subtotal' => $subtotal,

                'tax' => 0,

                'discount' => 0,

                'total' => $subtotal,

                'notes' =>
                    $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {

                GoodsReceiptItem::create([

                    'goods_receipt_id' =>
                        $receipt->id,

                    'purchase_order_item_id' =>
                        $item['purchase_order_item_id'] ?? null,

                    'product_id' =>
                        $item['product_id'],

                    'ordered_quantity' =>
                        $item['ordered_quantity'],

                    'received_quantity' =>
                        $item['received_quantity'],

                    'pending_quantity' =>
                        $item['pending_quantity'],

                    'unit_cost' =>
                        $item['unit_cost'],

                    'subtotal' =>
                        $item['subtotal'],
                ]);
            }

            return $receipt->load('items');
        });
    }

    public function receive(
        GoodsReceipt $receipt,
        int $branchId
    ): GoodsReceipt {

        DB::transaction(function () use ($receipt, $branchId) {

            foreach (
                $receipt->items()
                    ->get([
                        'product_id',
                        'received_quantity'
                    ])
                    ->toArray() as $item
            ) {

                $this->inventoryService->create([

                    'company_id' =>
                        $receipt->company_id,

                    'branch_id' =>
                        $branchId,

                    'product_id' =>
                        $item['product_id'],

                    'user_id' =>
                        $receipt->received_by,

                    'type' =>
                        'IN',

                    'quantity' =>
                        $item['received_quantity'],

                    'reference' =>
                        $receipt->receipt_number,

                    'reason' =>
                        'Goods Receipt',
                ]);
            }

            $this->repository->update(
                $receipt,
                [
                    'status' => 'RECEIVED',
                    'received_at' => Carbon::now(),
                ]
            );
        });

        return $receipt->refresh();
    }

    private function generateReceiptNumber(): string
    {
        return 'GR-' .
            now()->format('Ymd') .
            '-' .
            strtoupper(
                Str::random(6)
            );
    }
}
