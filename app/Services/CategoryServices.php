<?php


namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\ProductPrices;

class CategoryServices {


    public function createCategory(array $data)
    {
        $categoryData = [
            'name' => $data['name'],
            'description' => $data['description']
        ];

        $category = Category::create($categoryData);

        return $category;
    }
    
    public function updateCategory(object $categoryRequest, object $category)
    {
        $validate = $categoryRequest->validated();

        $categoryData = [
            'name' => $validate['name'],
            'description' => $validate['description']
        ];

        $category->update($categoryData);

        return $category;
    }


    public function deleteCategory(object $category)
    {
        return $category->delete();
    }


}