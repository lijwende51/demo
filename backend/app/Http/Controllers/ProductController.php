<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $products = [
        ['id' => 1, 'name' => 'Product 1', 'price' => 10.00],
        ['id' => 2, 'name' => 'Product 2', 'price' => 20.00],
        ['id' => 3, 'name' => 'Product 3', 'price' => 30.00],
    ];

    public function index()
    {
        return response()->json($this->products);
    }

    public function show($id)
    {
        $product = collect($this->products)->firstWhere('id', $id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $product = $request->only(['name', 'price']);
        $product['id'] = count($this->products) + 1;
        $this->products[] = $product;
        return response()->json($product, 201);
    }
        

}   

