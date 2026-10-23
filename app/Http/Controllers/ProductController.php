<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Marketplace;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index(Marketplace $marketplace)
    {
        Gate::authorize('viewAny', [$marketplace]);

        $products = $marketplace->products()->with('seller')->get();

        return inertia('Products/Index', [
            'marketplace' => $marketplace,
            'products' => $products,
        ]);
    }

    public function create(Marketplace $marketplace)
    {
        Gate::authorize('create', [$marketplace]);

        return inertia('Products/Create', ['marketplace' => $marketplace]);
    }

    public function store(StoreProductRequest $request, Marketplace $marketplace)
    {
        Gate::authorize('create', [$marketplace]);

        $marketplace->products()->create([
            'seller_id' => auth()->id(),
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'price' => $request->validated('price'),
            'status' => 'active',
        ]);

        return redirect()->route('marketplaces.products.index', $marketplace);
    }

    public function show(Marketplace $marketplace, Product $product)
    {
        abort_unless($product->marketplace_id === $marketplace->id, 404);
        Gate::authorize('view', $product);

        return inertia('Products/Show', ['marketplace' => $marketplace, 'product' => $product->load('seller')]);
    }

    public function edit(Marketplace $marketplace, Product $product)
    {
        abort_unless($product->marketplace_id === $marketplace->id, 404);
        Gate::authorize('update', $product);

        return inertia('Products/Edit', ['marketplace' => $marketplace, 'product' => $product]);
    }

    public function update(UpdateProductRequest $request, Marketplace $marketplace, Product $product)
    {
        abort_unless($product->marketplace_id === $marketplace->id, 404);
        Gate::authorize('update', $product);

        $product->update($request->validated());

        return redirect()->route('marketplaces.products.index', $marketplace);
    }

    public function deactivate(Marketplace $marketplace, Product $product)
    {
        abort_unless($product->marketplace_id === $marketplace->id, 404);
        Gate::authorize('deactivate', $product);

        $product->update(['status' => 'inactive']);

        return redirect()->route('marketplaces.products.index', $marketplace);
    }
}
