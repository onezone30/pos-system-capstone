<?php

namespace App\Http\Controllers\auth;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    public function create()
    {
    return view('auth.forgot-password');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $token = Password::createToken(User::where('email', $request->email)->first());

        $sendMail = Mail::to($request->email)->send(new PasswordResetMail($token, $request->email));

        return $this->redirectWithToast(
            $sendMail,
            'Reset Email Sent',
            'Failed to send reset email',
            'login'
        );
    }
}
