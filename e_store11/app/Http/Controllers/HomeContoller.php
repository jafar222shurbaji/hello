<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class HomeContoller extends Controller
{
    //
    public function store(StoreProductRequest $request)
    {
        $ValidatedData = $request->validated();
        $product = Product::create($ValidatedData);
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function addToCart(Request $request)
    {
        $ValidatedData = $request->validate([
            "product_id" => "required|integer",
            "quantity" => "required|integer",
            //useri id معي ياه من ال auht
        ]);

    }
}
