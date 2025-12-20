<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Services\UserServices;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'role' => ['required'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                ]
            ];

        if($this->profile_image instanceof TemporaryUploadedFile) {
            $rules['profile_image'] = ['image', 'nullable', 'max:2048'];
        }

        return $rules;
    }

    #[On('open-edit-modal')]
    public function load($id)
    {
        $this->user = User::findOrFail($id);

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->role = $this->user->role;
    }

    public function update(UserServices $userServices)
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'profile_image' => $this->profile_image instanceof TemporaryUploadedFile
                                ? $this->profile_image
                                : $this->user->profile_image,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ];

        if(! $userServices->update($this->user, $userData)) {
            $this->dispatch('toast.error', message: "Failed to update {$userData['name']}");
        }

        $this->dispatch('editUser');
        $this->reset();
        $this->dispatch('close-edit-modal');
        $this->dispatch('toast.success', message: "{$userData['name']} has been updated");
    }

    public function render()
    {
        return view('livewire.user.edit-user-form');
    }
}
