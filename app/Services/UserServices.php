<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserServices {

    private function updateProfile(Request $request) 
    {
        if(request()->hasFile('profile_image')) {
            return $request->file('profile_image')->store('images/users/profiles', 'public');

        }

        return null;

    }

    public function createUser(UserRequest $userRequest) 
    {
        $attributes = $userRequest->validated();

        $userData = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'role' => $attributes['role'],
            'password' => $attributes['password'],
            'profile_image' => $this->updateProfile($userRequest)
        ];

        $user = User::create($userData);

        if(!$user){
            return back()->with('toast', [
                'message' => 'User Registration Failed!',
                'type' => 'error'
            ]);
        }

        return redirect()->route($user->role . '.dashboard')->with('toast', [
            'message' => 'User Registration Success!',
            'type' => 'success'
        ]);
    }
    
    public function updateUser(User $user, UserRequest $userRequest) {

        $userRequest = $userRequest->validated();

        $userData = [
            'name' => $userRequest['name'],
            'email' => $userRequest['email'],
            'role' => $userRequest['role'],
            'profile_image' => $userRequest['profile_image'] ?? $user->profile_image
        ];

        if(!empty($userRequest['password'])) {
            $userData[] = $userRequest['password'];
        }

        return $user->update($userData);
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }

}