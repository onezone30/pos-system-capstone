<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $category = Category::all();

        return view('admin.products.index', [
            'user' => Auth::user(),
            'products' => Product::with('prices')->get(),
            'categories' => $category
        ]);
    }
    public function store(ProductRequest $productRequest, PriceRequest $priceRequest)
    {

        $product = Product::create([
            'category_id' => $productRequest->category,
            'name' => $productRequest->name
        ]);

        foreach(request()->size as $index => $size){
            $price = ProductPrices::create([
                'product_id' => $product->id,
                'price' => $priceRequest->price[$index],
                'quantity_stock' => $priceRequest->quantity_stock[$index],
                'size' => $size
            ]);
        }

        if(!$product && !$price) {
            return back()->with([
                'error' => 'Product error'
            ]);
        }


        return redirect()->route('admin.dashboard');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products');
    }

    public function update(Product $product, ProductRequest $productRequest, PriceRequest $priceRequest)
    {
        $product->update([
            'name' => $productRequest->name, 
        ]);

        foreach(request()->size as $index => $size){
            $product->update([
                'price' => $priceRequest->price[$index],
                'quantity_stock' => $priceRequest->quantity_stock[$index],
                'size' => $size
            ]);
        }

        if(!$product) {
            return back()->with([
                'error' => 'Product error'
            ]);
        }

        return redirect()->route('admin.dashboard');

    }
}
