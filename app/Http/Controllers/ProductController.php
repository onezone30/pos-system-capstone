<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index', [
            'user' => Auth::user(),
            'products' => Product::with('prices')->get()
        ]);
    }
    public function store()
    {

    }
}
