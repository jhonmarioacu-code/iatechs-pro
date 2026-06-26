<?php

declare(strict_types=1);

namespace App\Domains\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Products\Models\Product;
use App\Domains\Products\Services\ProductService;
use App\Domains\Products\Requests\StoreProductRequest;
use App\Domains\Products\Requests\UpdateProductRequest;
use App\Domains\Products\Resources\ProductResource;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Product::class);

        return ProductResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreProductRequest $request
    )
    {
        $this->authorize('create', Product::class);

        return new ProductResource(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function show(
        Product $product
    )
    {
        $this->authorize('view', $product);

        return new ProductResource(
            $product
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    )
    {
        $this->authorize('update', $product);

        return new ProductResource(
            $this->service->update(
                $product,
                $request->validated()
            )
        );
    }

    public function destroy(
        Product $product
    )
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function activate(
        Product $product
    )
    {
        $this->authorize('activate', $product);

        return new ProductResource(
            $this->service->activate(
                $product
            )
        );
    }

    public function deactivate(
        Product $product
    )
    {
        $this->authorize('deactivate', $product);

        return new ProductResource(
            $this->service->deactivate(
                $product
            )
        );
    }
}
