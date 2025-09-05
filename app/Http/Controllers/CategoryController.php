<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryServices;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private $categoryServices;

    public function __construct(CategoryServices $categoryServices)
    {
        $this->categoryServices = $categoryServices;
    }

    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();

        return view($user->role . '.categories.index', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    public function store(CategoryRequest $categoryRequest)
    {
        $category = $this->categoryServices->createCategory($categoryRequest);

        return back()->with('toast', [
            'type' => 'success',
            'message' => "Category $category->name has been created"
        ]);
    }

    public function update(CategoryRequest $categoryRequest, Category $category)
    {
        $this->categoryServices->updateCategory($categoryRequest, $category);

        return back()->with('toast', [
            'type' => 'success',
            'message' => "Category $category->name has been updated"
        ]);
    }

    public function destroy(Category $category)
    {
        $this->categoryServices->deleteCategory($category);  

        return back()->with('toast', [
            'type' => 'success',
            'message' => "Category {$category->name} has been deleted"
        ]);

    }
}
