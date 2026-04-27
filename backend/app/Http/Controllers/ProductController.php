<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }
    //Get single product

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }
    //Create new product

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|string|url',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product = Product::create($request->only(['name', 'description', 'price', 'stock', 'image_url', 'category_id']));
        return response()->json($product, 201);
    }
    //Update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image_url' => 'nullable|string|url',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product->update($request->only(['name', 'description', 'price', 'stock', 'image_url', 'category_id']));
        return response()->json($product);
    }
    //Delete product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404); 
        }          
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

}  

