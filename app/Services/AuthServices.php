<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthServices {


    public function userLogin(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return Auth::user()->role;        
    }


    public function forgotPasswordSendEmail(string $email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $token = Password::createToken($user);

        try {
            Mail::to($email)->send(new PasswordResetMail($token, $email));
        } catch (Exception $e) {
            Log::error('Password reset failed', [
                'email' => $email,
                'message' => $e->getMessage()
            ]);
            
            throw new Exception('Failed to send password reset email');
        }
    }

}