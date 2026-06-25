<?php

declare(strict_types=1);

namespace App\Domains\PurchaseOrders\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Domains\PurchaseOrders\Models\PurchaseOrder;
use App\Domains\PurchaseOrders\Models\PurchaseOrderItem;
use App\Domains\PurchaseOrders\Repositories\PurchaseOrderRepository;

class PurchaseOrderService
{
    public function __construct(
        private PurchaseOrderRepository $repository
    ) {}

    public function paginate()
    {
        return $this->repository
            ->paginate();
    }

    public function create(
        array $data
    ): PurchaseOrder {

        return DB::transaction(function () use ($data) {

            $subtotal = 0;

            foreach ($data['items'] as $item) {

                $subtotal +=
                    $item['quantity']
                    *
                    $item['unit_cost'];
            }

            $tax =
                $data['tax']
                ??
                0;

            $discount =
                $data['discount']
                ??
                0;

            $total =
                $subtotal +
                $tax -
                $discount;

            $purchaseOrder =
                $this->repository->create([

                    'uuid' =>
                        (string) Str::uuid(),

                    'company_id' =>
                        $data['company_id'],

                    'supplier_id' =>
                        $data['supplier_id'],

                    'created_by' =>
                        $data['created_by'],

                    'order_number' =>
                        $this->generateOrderNumber(),

                    'status' =>
                        'DRAFT',

                    'subtotal' =>
                        $subtotal,

                    'tax' =>
                        $tax,

                    'discount' =>
                        $discount,

                    'total' =>
                        $total,

                    'notes' =>
                        $data['notes']
                        ?? null,
                ]);

            foreach ($data['items'] as $item) {

                PurchaseOrderItem::create([

                    'purchase_order_id' =>
                        $purchaseOrder->id,

                    'product_id' =>
                        $item['product_id'],

                    'quantity' =>
                        $item['quantity'],

                    'unit_cost' =>
                        $item['unit_cost'],

                    'subtotal' =>
                        $item['quantity']
                        *
                        $item['unit_cost'],
                ]);
            }

            return $purchaseOrder
                ->load('items');
        });
    }

    public function approve(
        PurchaseOrder $purchaseOrder
    ): PurchaseOrder {

        return $this->repository
            ->update(
                $purchaseOrder,
                [
                    'status' => 'APPROVED',
                    'approved_at' => Carbon::now(),
                    'ordered_at' => Carbon::now(),
                ]
            );
    }

    public function markAsReceived(
        PurchaseOrder $purchaseOrder
    ): PurchaseOrder {

        return $this->repository
            ->update(
                $purchaseOrder,
                [
                    'status' => 'RECEIVED',
                    'received_at' => Carbon::now(),
                ]
            );
    }

    public function cancel(
        PurchaseOrder $purchaseOrder
    ): PurchaseOrder {

        return $this->repository
            ->update(
                $purchaseOrder,
                [
                    'status' => 'CANCELLED'
                ]
            );
    }

    private function generateOrderNumber(): string
    {
        return 'PO-' .
            now()->format('Ymd') .
            '-' .
            strtoupper(
                Str::random(6)
            );
    }
}