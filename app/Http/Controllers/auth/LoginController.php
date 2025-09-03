<?php

namespace App\Http\Controllers\auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuthServices;
use App\Http\Controllers\Controller;

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

        return $this->authServices->userLogin($request);

    }

    public function destroy(User $user)
    {
        $this->authServices->userLogout();

        return $this->redirectWithToast(
            true,
            "User {$user->name} successfully logged out",
            "User {$user->name} failed to log out",
            'login'
        );
    }

}