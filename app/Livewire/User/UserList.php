<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

use function PHPSTORM_META\map;

class UserList extends Component
{
    #[On(['editUser', 'createUser'])]
    public function refreshPage()
    {
        $this->reset();
    }
    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        
        if(! $user->delete()) {
            $this->dispatch('toast.success', message: "Failed to delete {$user->name}");
        }
        
        $this->refreshPage();
        $this->dispatch('toast.success', message: "{$user->name} has been deleted");
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        $users = User::get();

        return view('livewire.user.user-list', [
            'users' => $users,
        ]);
    }
}
