<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use function PHPSTORM_META\map;

class UserList extends Component
{
    public string $search = "";

    protected $listeners = [
        'deleteUser' => '$refresh',
        'createUser' => '$refresh',
        'editUser' => '$refresh',
        'searchUpdated' => 'userSearch',
    ];

    public function userSearch($search)
    {
        $this->search = trim($search);
    }

    public function delete(int $id)
    {
        User::findOrFail($id)->delete();

        $this->dispatch('deleteUser');
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('role', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->latest()
            ->get();

        return view('livewire.user.user-list', [
            'users' => $users
        ]);
    }
}
