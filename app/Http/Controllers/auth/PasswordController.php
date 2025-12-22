<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function create(string $token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'confirmed|required|min:8',
        ]);

        try {
            Password::reset(
                $request->only('email', 'token', 'password', 'password_confirmation'), 
                function(User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();
                }
            );

            return redirect()->route('login')->with('toast', [
                'type' => 'success',
                'message' => 'Password reset successfully'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to reset password. Please try again.']);
        }
    }
}
