<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductServices;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $productServices;

    public function __construct(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }

    public function index()
    {
        $user = Auth::user();
        $products = Product::with('prices')->get();
        $categories = Category::all();

        return view($user->role . '.products.index', [
            'user' => $user,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function store(ProductRequest $productRequest, PriceRequest $priceRequest)
    {
        $product = $this->productServices->saveProduct(null, $productRequest, $priceRequest);

        return back()->with('toast', [
            'message' => "Product $product->name has been created",
            'type' => 'success'
        ]);
    }

    public function update(Product $product, ProductRequest $productRequest, PriceRequest $priceRequest)
    {
        $product = $this->productServices->saveProduct($product, $productRequest, $priceRequest);

        return back()->with('toast', [
            'message' => "Product $product->name has been updated",
            'type' => 'success'
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('toast', [
            'message' => "Product $product->name has been deleted",
            'type' => 'success'
        ]);
    }
}
