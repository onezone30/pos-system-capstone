<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show($id)
    {   
        $user = User::findOrFail($id);

        if(!$user) {
            return redirect()->route('login');
        }

        return view('profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if(!$user || $user->id != $id) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update($validatedData);

        return redirect()->route("{$user->role}.dashboard")->with('success', 'Profile updated successfully.');
    }


}
