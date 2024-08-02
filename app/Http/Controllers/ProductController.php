<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Product::with('category', 'stocks')->paginate(4);
    }

    public function new($id)
    {
        //
        return Product::with('category', 'stocks')
            ->where('category_id', $id)
            ->orderBy('id', 'desc')
            ->paginate(4);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $name = time().'_' . $photo->getClientOriginalName();
                $photo->move('img', $name);
                $data[] = $name;
            }
        }

        $product = Product::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'photo' => json_encode($data),
            'brand' => $request->brand,
            'name' => $request->name,
            'description' => $request->description,
            'details' => $request->details,
            'price' => $request->price,
        ]);

        Stock::create([
            'product_id' => $product->id, // corrected 'product_id'
            'size' => $request->size,
            'color' => $request->color,
            'quantity' => $request->quantity, // corrected 'quantity'
        ]);
        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category', 'stocks')->findOrFail($id);

        if ($product->reviews()->exists()) {
            $product['review'] = $product->reviews()->avg('rating');
            $product['num_reviews'] = $product->reviews()->count();
        }

        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = [];
        // Find the product by ID
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $name = time().'_'. $photo->getClientOriginalName();
                $photo->move('img', $name);
                $data[] = $name;
            }

            $product->update([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'photo' => json_encode($data),
                'brand' => $request->brand,
                'name' => $request->name,
                'description' => $request->description,
                'details' => $request->details,
                'price' => $request->price,
            ]);
        }else{
            $product->update([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'brand' => $request->brand,
                'name' => $request->name,
                'description' => $request->description,
                'details' => $request->details,
                'price' => $request->price,
            ]);
        }

        $stock = $product->stocks()->first();
        if ($stock) {
            $stock->update([
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
            ]);
        } else {
            Stock::create([
                'product_id' => $product->id,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
            ]);
        }
        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $product = Product::findOrFail($id);

        $product->delete();
    }

    public function search(Request $request)
    {
        $category = $request->input('category');
        $term = $request->input('term');

        $query = Product::query();

        if ($category && $category !== 'all') {
            $query->where('category_id', $category);
        }

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('brand', 'like', "%{$term}%");
            });
        }

        $results = $query->get();

        return response()->json($results);
    }
}
