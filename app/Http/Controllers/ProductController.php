<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Policies\ProductPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Support\Facades\Gate;


#[UsePolicy(ProductPolicy::class)]

class ProductController extends Controller
{

    public function create()
    {
       

        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    public function index(Request $request)
    {
        $query = Product::with('categories')
            ->where('user_id', Auth::id())
            ->latest();


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', '%' . $search . '%')
                    ->orWhere('products.sell_price', 'like', '%' . $search . '%');
            });
        }


        if ($request->filled('category_id')) {
            $categoryIds = explode(',', $request->category_id);
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }


        $products = $query->paginate($request->per_page ?? 10)
            ->appends($request->query());

        return response()->json($products);
    }

    public function show(Product $product)
    {
        if (! Gate::allows('modify', $product)) {
            abort(403);
        }

        return response()->json($product->load('categories'), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'sell_price' => 'required|numeric|min:1|gt:0',
            'category_ids' => 'required|array'
        ]);

        $product = $request->user()->products()->create([
            'name' => $validated['name'],
            'sell_price' => $validated['sell_price'],
        ]);



        if (!empty($validated['category_ids'])) {
            $product->categories()->attach($validated['category_ids']);
        }

        return response()->json([
            'message' => 'Added Product Success',
            'data' => $product->load('categories'),
        ], 201);
    }

    public function update(Request $request, Product $product)
    {

        if (! Gate::allows('modify', $product)) {
            abort(403);
        }


        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'sell_price' => 'required|numeric|min:1|gt:0',
            'is_active'  => 'boolean',
            'category_ids' => 'required|array'
        ]);


        $product->update($validated);

        if (isset($validated['category_ids'])) {
            $product->categories()->sync($validated['category_ids']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('categories')
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        if (! Gate::allows('modify', $product)) {
            abort(403);
        }


        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
