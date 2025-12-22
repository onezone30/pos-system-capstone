<?php

namespace App\Services;

use App\Models\Category;
use App\Models\InventoryLogs;
use Illuminate\Support\Facades\DB;

class CategoryServices {

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $category = Category::create([
                'name' => $data['name'],
                'description' => $data['description']
            ]);

            InventoryLogs::create([
                'product_id' => null, 
                'user_id'    => auth()->id(),
                'type'       => 'adjustment',
                'quantity'   => 0,
                'note'       => "Category Created: '{$category->name}'"
            ]);

            return $category;
        });
    }
    
    public function update(Category $category, array $data)
    {
        return DB::transaction(function () use ($category, $data) {
            $oldName = $category->name;

            $category->update([
                'name' => $data['name'],
                'description' => $data['description']
            ]);

            $note = ($oldName !== $data['name']) 
                ? "Category Renamed: '{$oldName}' to '{$data['name']}'"
                : "Category Details Updated: '{$data['name']}'";

            InventoryLogs::create([
                'product_id' => null,
                'user_id'    => auth()->id(),
                'type'       => 'adjustment',
                'quantity'   => 0,
                'note'       => $note
            ]);

            return $category;
        });
    }

    public function delete(object $category)
    {
        return DB::transaction(function () use ($category) {
            $categoryName = $category->name;
            
            InventoryLogs::create([
                'product_id' => null,
                'user_id'    => auth()->id(),
                'type'       => 'adjustment',
                'quantity'   => 0,
                'note'       => "Category Deleted: '{$categoryName}'"
            ]);

            return $category->delete();
        });
    }
}