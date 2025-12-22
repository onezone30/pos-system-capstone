<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Services\UserServices; // Import the service
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UserList extends Component
{
    #[On(['editUser', 'createUser'])]
    public function refreshPage()
    {
        $this->reset(); 
    }

    public function delete(int $id, UserServices $userServices)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('toast.error', message: "You cannot delete your own account.");
            $this->dispatch('close-delete-modal');
            return;
        }
        
        if(! $userServices->delete($user)) {
            $this->dispatch('toast.error', message: "Failed to delete {$user->name}");
            return;
        }
        
        $this->refreshPage();
        $this->dispatch('toast.success', message: "{$user->name} has been deleted and action logged.");
        $this->dispatch('close-delete-modal');
    }

    public function render()
    {
        $users = User::orderBy('name', 'asc')->get();

        return view('livewire.user.user-list', [
            'users' => $users,
        ]);
    }
}