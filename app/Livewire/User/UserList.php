<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use function PHPSTORM_META\map;

class UserList extends Component
{
    public string $search = "";
    public $users = [];

    protected $listeners = [
        'searchUpdated' => 'userSearch',
        'editUser' => 'load',
        'createUser' => 'load'
    ];

    public function mount()
    {
        $this->load();
    }

    public function load()
    {
        $this->users = $this->filteredUser();
    }

    public function userSearch($search)
    {
        $this->search = trim($search);
        $this->load();
    }

    public function filteredUser()
    {
        $users = User::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('role', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->latest()
            ->get();

        return $users;
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        
        if(! $user->delete()) {
            $this->dispatch('toast.success', message: "Failed to delete {$user->name}");
        }
        
        $this->load();
        $this->dispatch('toast.success', message: "{$user->name} has been deleted");
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        return view('livewire.user.user-list', [
            'users' => $this->users,
        ]);
    }
}
