<?php

namespace App\Http\Controllers\auth;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthServices;
use Exception;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function __construct(
        protected AuthServices $authServices
    ){}

    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request) 
    {
        $validate = $request->validate([
            'email' => ['email', 'required']
        ]);

        try {
            $this->authServices->forgotPasswordSendEmail($validate['email']);

            return redirect()->route('login')->with('toast', [
                'message' => 'Reset email sent',
                'type' => 'success'
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('toast', [
                'message' => "Email address not found",
                'type' => 'error'
            ]);
        }
    }
}
