<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserServices {

    private function handleImage(?TemporaryUploadedFile $file, ?string $oldPath = null) 
    {
        if(!$file) {
            return $oldPath;
        }

        $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $imagePath = $file->storeAs('images/profiles', $imageName, 'public');

        if($oldPath && $oldPath !== $imagePath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $imagePath;
    }

    public function create(array $data) 
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'profile_image' => $this->handleImage($data['profile_image'] ?? null),
            'role' => $data['role'],
            'password' => bcrypt($data['password'])
        ];

        return User::create($userData);
    }
    
    public function update(User $user, array $data) {
        
        if(
            isset($data['profile_image']) &&
            $data['profile_image'] instanceof TemporaryUploadedFile
            ) {
                $profileImage = $this->handleImage($data['profile_image'], $user->profile_image);
            }
        else  {
                $profileImage = $user->profile_image;
            }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'profile_image' => $profileImage,
        ];

        if(!empty($data['password'])) {
            $userData['password'] = bcrypt($data['password']);
        }

        return $user->update($userData);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

}