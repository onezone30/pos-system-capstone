<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductPrices;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductOrderList extends Component
{
    use WithPagination;

    public ?string $sortCategory = null;
    public string $sortOrder = 'asc';
    public string $sortField = 'name';
    public string $search = "";
    
    #[On('sortProduct')]
    public function sort($sortData)
    {
        $this->sortCategory = (string) $sortData['category'] ?: null;
        $this->sortField = $sortData['field'] ?? 'name';
        $this->sortOrder = $sortData['order'] ?? 'asc';

        $this->resetPage();
    }

    #[On('searchUpdated')]
    public function productSearch($search)
    {
        $this->search = trim($search);
        $this->resetPage();
    }

    public function filteredProduct()
    {
        return Product::query()
                ->when($this->search, function (Builder $query) {
                    $search = "%{$this->search}%";
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', $search)
                            ->orWhereHas('category', fn($cat) => 
                                $cat->where('name', 'like', $search)
                        );
                    });
                })
                ->when($this->sortCategory, fn($query) =>
                    $query->where('category_id', $this->sortCategory)
                )
                ->when($this->sortField === 'price', function (Builder $query) {
                    $query->orderBy(
                        ProductPrices::select('price')
                            ->whereColumn('product_prices.product_id', 'products.id')
                            ->limit(1),
                        $this->sortOrder
                    );
                }, function (Builder $query) {
                    $query->orderBy($this->sortField, $this->sortOrder);
                })
                ->with(['category', 'prices'])
                ->paginate(10);
    }

    public function render()
    {
        return view('livewire.product-order-list', [
            'products' => $this->filteredProduct(),
        ]);
    }
}
