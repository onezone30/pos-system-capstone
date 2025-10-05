<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request) {
        $users = User::when($request->query, function($query) {
            return $query->whereAny([
                'name',
                'role',
                'email'
            ]);
        });

        return view('admin.users.index', [
            'users' => $users
        ]);
    }
}
