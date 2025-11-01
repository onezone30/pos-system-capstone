<?php

namespace App\Livewire\User;

use App\Services\UserServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;
use Livewire\Component;

class CreateUserForm extends Component
{
    use WithFileUploads;

    public string $name;
    public string $role;
    public string $email;
    public $profile_image;
    public string $password;
    public string $password_confirmation;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'role' => ['required'],
            'password' => [
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
            ],
            'profile_image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,jpg,png,webp'],
            ];
    }

    public function create(UserServices $userServices)
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'profile_image' => $this->profile_image,
            'password' => Hash::make($this->password),
        ];

        if(! $userServices->create($userData)) {
            $this->dispatch('toast.error', message: "Failed to create user");
            return;
        }

        $this->reset(['name', 'email', 'role', 'profile_image', 'password', 'password_confirmation']);
        $this->dispatch('close-create-modal');
        $this->dispatch('createUser');
        $this->dispatch('toast.success', message: "{$userData['name']} has been created");
    }

    public function render()
    {
        return view('livewire.user.create-user-form');
    }
}
