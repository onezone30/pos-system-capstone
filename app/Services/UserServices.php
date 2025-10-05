<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserServices {

    private function updateProfile(?TemporaryUploadedFile $file, ?string $oldPath = null) 
    {
        if($file) {

            $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $imagePath = $file->storeAs('images/profiles', $imageName, 'public');

            if($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            return $imagePath;
        }
        
        return $oldPath;
    }

    public function createUser(array $attributes) 
    {
        $userData = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'profile_image' => $this->updateProfile($attributes['profile_image'] ?? null),
            'role' => $attributes['role'],
            'password' => bcrypt($attributes['password'])
        ];

        return User::create($userData);
    }
    
    public function updateUser(User $user, array $attributes) {

        $userData = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'role' => $attributes['role'],
            'profile_image' => $this->updateProfile($attributes['profile_image'] ?? null),
        ];

        if(!empty($attributes['password'])) {
            $userData[] = bcrypt($attributes['password']);
        }

        return $user->update($userData);
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }

}