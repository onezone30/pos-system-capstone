<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryList extends Component
{
    public $category = [];
    public string $search = '';

    protected $listeners = [
        'createCategory' => 'load',
        'editCategory' => 'load',
        'searchUpdated' => 'categorySearch'
    ];

    public function mount()
    {
        $this->load();
    }

    public function load()
    {
        $this->category = $this->filteredCategory();
    }

    public function categorySearch($search)
    {
        $this->search = trim($search);
        $this->load();
    }

    public function filteredCategory()
    {
        $categories = Category::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like',"%{$this->search}%");
            })
            ->latest()
            ->get();

        return $categories;
    }

    public function delete(int $id)
    {
        $category = Category::findOrFail($id);
        
        if(! $category->delete()) {
            $this->dispatch('toast.success', message: "{$category->name}");
        }

        $this->dispatch('deleteCategory');
        $this->dispatch('close-delete-modal');
        $this->dispatch('toast.success', message: "{$category->name} has been deleted");
    }

    public function render()
    {
        $categories = $this->filteredCategory();

        return view('livewire.category.category-list', [
            'categories' => $categories
        ]);
    }
}
