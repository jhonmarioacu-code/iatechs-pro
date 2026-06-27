<?php

declare(strict_types=1);

namespace App\Domains\GoodsReceipts\Policies;

use App\Domains\Users\Models\User;
use App\Domains\GoodsReceipts\Models\GoodsReceipt;

class GoodsReceiptPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('goods-receipts.view');
    }

    public function view(
        User $user,
        GoodsReceipt $receipt
    ): bool {

        return $user->can('goods-receipts.view')
            && $user->company_id ===
                $receipt->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('goods-receipts.create');
    }

    public function update(User $user): bool
    {
        return $user->can('goods-receipts.update');
    }

    public function receive(User $user): bool
    {
        return $user->can('goods-receipts.update');
    }
}
