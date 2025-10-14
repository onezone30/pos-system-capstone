<?php

namespace App\Livewire;

use Livewire\Component;

class Search extends Component
{
    public string $search;
    public string $placeholder = "Search...";

    public function updatedSearch()
    {
        $this->dispatch('searchUpdated', $this->search);
    }    

    public function render()
    {
        return view('livewire.search');
    }
}
