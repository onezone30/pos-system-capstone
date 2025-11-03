<?php


namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PriceRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\ProductPrices;

class CategoryServices {

    public function create(array $data)
    {
        $categoryData = [
            'name' => $data['name'],
            'description' => $data['description']
        ];

        $category = Category::create($categoryData);

        return $category;
    }
    
    public function update(Category $category, array $data)
    {
        $categoryData = [
            'name' => $data['name'],
            'description' => $data['description']
        ];

        $category->update($categoryData);

        return $category;
    }


    public function delete(object $category)
    {
        return $category->delete();
    }


}