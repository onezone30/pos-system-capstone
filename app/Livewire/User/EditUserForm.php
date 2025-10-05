<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Services\UserServices;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUserForm extends Component
{
    use WithFileUploads;

    public User $user;
    public string $name;
    public string $email;
    public string $role;
    public $profile_image;
    public string $password;
    public string $password_confirmation;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'role' => ['required'],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                ]
            ];
    }

    #[On('open-edit-modal')]
    public function mouth($id)
    {
        $this->user = User::findOrFail($id);

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->role = $this->user->role;
        $this->profile_image = $this->user->profile_image ?? null;
    }

    public function update(UserServices $userServices)
    {
        $this->validate();

        $userServices->updateUser($this->user, [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'profile_image' => $this->profile_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile ? $this->profile_image : null,
            'password' => $this->password,
        ]);
    }

    public function render()
    {
        return view('livewire.user.edit-user-form');
    }
}
