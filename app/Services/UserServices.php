<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserServices {

    private function updateProfile(Request $request) 
    {
        if($request->hasFile('profile_image')) {

            $image = $request->file('profile_image');

            $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());

            $imagePath = $image->storeAs('images/profiles', $imageName, 'public');

            return $imagePath;

        }

        return null;

    }

    public function createUser(object $userRequest) 
    {
        $attributes = $userRequest->validated();

        $userData = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'profile_image' => $this->updateProfile($userRequest),
            'role' => $attributes['role'],
            'password' => bcrypt($attributes['password'])
        ];

        $user = User::create($userData);

        return $user;

    }
    
    public function updateUser(object $user, object $userRequest) {

        $validate = $userRequest->validated();

        $userData = [
            'name' => $validate['name'],
            'email' => $validate['email'],
            'role' => $validate['role'],
            'profile_image' => $this->updateProfile($userRequest) ?? $user->profile_image
        ];

        if(!empty($userRequest['password'])) {
            $userData[] = bcrypt($userRequest['password']);
        }

        return $user->update($userData);
    }

    public function deleteUser(object $user)
    {
        return $user->delete();
    }

}