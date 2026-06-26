<?php

declare(strict_types=1);

namespace App\Domains\Products\Policies;

use App\Domains\Users\Models\User;
use App\Domains\Products\Models\Product;

class ProductPolicy
{
    public function viewAny(
        User $user
    ): bool {

        return $user->can(
            'products.view'
        );
    }

    public function view(
        User $user,
        Product $product
    ): bool {

        return
            $user->can('products.view')
            &&
            $user->company_id ===
            $product->company_id;
    }

    public function create(
        User $user
    ): bool {

        return $user->can(
            'products.create'
        );
    }

    public function update(
        User $user,
        Product $product
    ): bool {

        return
            $user->can('products.update')
            &&
            $user->company_id ===
            $product->company_id;
    }

    public function delete(
        User $user,
        Product $product
    ): bool {

        return
            $user->can('products.delete')
            &&
            $user->company_id ===
            $product->company_id;
    }

    public function activate(
        User $user,
        Product $product
    ): bool {

        return
            $user->can('products.update')
            &&
            $user->company_id ===
            $product->company_id;
    }

    public function deactivate(
        User $user,
        Product $product
    ): bool {

        return
            $user->can('products.update')
            &&
            $user->company_id ===
            $product->company_id;
    }
}
