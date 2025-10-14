<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserServices $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function index()
    {
        $users = User::whereIn('role', ['admin', 'cashier'])->get();

        return view(Auth::user()->role . '.users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(UserRequest $userRequest)
    {
        $user = $this->userServices->create($userRequest);

        return back()->with('toast', [
            'message' => "User {$user->name} has been registered successfully!",
            'type' => 'success'
        ]);
    }

    public function update(User $user, UserRequest $userRequest) 
    {
        $this->userServices->update($user, $userRequest);

        return back()->with('toast', [
            'message' => "User {$user->name} has been updated successfully!",
            'type' => 'success'
        ]);
    }

    public function destroy(User $user)
    {
        $this->userServices->delete($user);

        return back()->with('toast', [
            'message' => "User {$user->name} has been deleted successfully!",
            'type' => 'success'
        ]);
    }
}
