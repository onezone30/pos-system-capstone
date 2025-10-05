<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserList extends Component
{
    protected $listeners = [
        'deleteUser' => '$refresh',
    ];

    public function delete(int $id)
    {
        User::findOrFail($id)->delete();

        $this->dispatch('deleteUser');
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        $users = User::all();
        $user = Auth::user();

        return view('livewire.user.user-list', [
            'user' => $user,
            'users' => $users
        ]);
    }
}
