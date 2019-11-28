<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdutoCollection;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $user = User::find($request->get('user_id'));

        $product = $user->products()->create([
            'name' => $request->get('name'),
            'price' => $request->get('price')
        ]);

        return response()->json($product);
    }

    public function index() {
        $products = Product::all();
        return response()->json($products);
    }

    public function show($id) {
        $product = Product::find($id);
        return response()->json($product);
    }

    public function update($id, Request $request)
    {
      $product = Product::find($id);
      $product->update($request->all());
      return response()->json($product);
    }

    public function delete($id)
    {
      $product = Product::find($id);
      $product->delete();
      return response()->json('deleted');
    }
}
