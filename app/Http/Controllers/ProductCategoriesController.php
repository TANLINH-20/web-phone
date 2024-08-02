<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductCategoriesController extends Controller
{
    //

    public function index()
    {
        return Category::all();
    }
    public function index1()
    {
        return Category::paginate(4);
    }
    public function new($id)
    {
        $products = Product::with('category', 'stocks')
            ->where('category_id', $id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        foreach ($products as $product) {
            if ($product->reviews()->exists()) {
                $product['review'] = $product->reviews()->avg('rating');
            }
        }

        return $products;
    }

    public function topSelling($id)
    {
        $products = Product::with('category')
            ->where('category_id', $id)
            ->take(6)
            ->get();

        foreach ($products as $product) {
            if ($product->reviews()->exists()) {
                $product['review'] = $product->reviews()->avg('rating');
            }

            if ($product->stocks()->exists()) {
                $numOrders = 0;
                $stocks = $product->stocks()->get();
                foreach ($stocks as $stock) {
                    $numOrders += $stock->orders()->count();
                }
                $product['num_orders'] = $numOrders;
            } else {
                $product['num_orders'] = 0;
            }
        }

        return $products->sortByDesc('num_orders')->values()->all();
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $products = Product::with('category', 'stocks')
            ->where('category_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $category->delete();
        foreach ($products as $product) {
            $product->delete();
        }
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($validatedData);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the category
        $category = Category::findOrFail($id);

        // Update the category
        $category->update($validatedData);

        // Return the updated category
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ], 200);
    }
}
