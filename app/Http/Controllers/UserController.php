<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Request;
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
        $user = Auth::user();
        $users = User::whereIn('role', ['admin', 'cashier'])->get();

        return view($user->role . '.users.index', [
            'user' => $user,
            'users' => $users,
        ]);
    }
    public function create()
    {
        return view('auth.register');
    }

    public function store(User $user, UserRequest $userRequest)
    {
        $user = $this->userServices->createUser($userRequest);

        if(!$user){
            return back()->with('toast', [
                'message' => 'User registration failed',
                'type' => 'error'
            ]);
        }

        return redirect()->route('admin.users')->with('toast', [
                'message' => 'User Registration Success!',
                'type' => 'success'
        ]);
    }

    public function destroy(User $user)
    {
        $userDelete = $this->userServices->deleteUser($user);

        if(!$userDelete) {
            return back()->with('toast', [
                'message' => 'User deletion Failed!',
                'type' => 'error'
            ]);
        }

        return redirect()->route('admin.users')->with('toast', [
            'message' => 'User have been deleted',
            'type' => 'success'
        ]);
    }

    public function update(User $user, UserRequest $userRequest) 
    {
        $updatedUser = $this->userServices->updateUser($user, $userRequest);

        if(!$updatedUser) {
            return back()->with('toast', [
                'message' => 'User Update Failed!',
                'type' => 'error'
            ]);
        }

        return redirect()->route(Auth::user()->role . '.users')->with('toast', [
            'message' => 'User Registration Success!',
            'type' => 'success'
        ]);
    }

}
