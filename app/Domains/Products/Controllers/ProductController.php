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
        return ProductResource::collection(
            $this->service->paginate()
        );
    }

    public function store(
        StoreProductRequest $request
    )
    {
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
        return new ProductResource(
            $product
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    )
    {
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
        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function activate(
        Product $product
    )
    {
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
        return new ProductResource(
            $this->service->deactivate(
                $product
            )
        );
    }
}