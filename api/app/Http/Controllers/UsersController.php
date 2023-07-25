<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function getUsers()
    {
        $users = User::with('profile')
            ->where('status', '!=', UserStatus::RESIGNED)
            ->get()
            ->makeHidden(['email'])
            ->each(function ($user) {
                $user->profile->makeHidden(['birthday', 'user_id']);
            });

        return response()->json($users);
    }
}
