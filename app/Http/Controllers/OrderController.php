<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        $orders = Order::with('items')->get();
        $categories = Category::all();
        $products = Product::all();

        return view($this->user->role . '.orders.index', [
            'orders' => $orders,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    public function create()
    {
        $products = Product::with(['prices', 'category'])->get();
        $categories = Category::all();

        return view($this->user->role . '.orders.create', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function print($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);

        return view('print', [
            'order' => $order,
        ]);
    }
}
