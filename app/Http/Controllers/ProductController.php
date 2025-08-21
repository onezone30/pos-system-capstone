<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Services\ProductServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::with('prices')->get();
        $category = Category::all();

        return view('admin.products.index', [
            'user' => $user,
            'products' => $products,
            'categories' => $category
        ]);
    }
        public function store(ProductServices $productServices, ProductRequest $productRequest, PriceRequest $priceRequest) {

        $product = $productServices->saveProduct(null, $productRequest, $priceRequest);

        return redirect()
                ->route('admin.products')
                ->with('toast', [
                    'message' => "Product $product has been created",
                    'type' => 'success'
                ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products');
    }

    public function update(ProductServices $productServices, Product $product, ProductRequest $productRequest, PriceRequest $priceRequest)
    {
        $productServices->saveProduct($product, $productRequest, $priceRequest);

        return redirect()
            ->route('admin.products')
            ->with('toast', [
                'message' => "Product $product has been created",
                'type' => 'success'
            ]);

    }
}
