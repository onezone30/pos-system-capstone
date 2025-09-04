<?php

namespace App\Http\Controllers\auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuthServices;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $authServices;
    protected $user;

    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoginRequest $request) {

        $role = $this->authServices->userLogin($request);

        return redirect()->route("{$role}.dashboard")->with('toast', [
                'message' => 'Successfully Logged In',
                'type' => 'success'
            ]);

    }

    public function destroy()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerate();

        return redirect()
                    ->route('login')
                    ->with('toast', [
                        'type' => 'success',
                        'message' => "User successfully logged out"
                    ]);

    }

}