<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:master,manager,accountant,camp']);
    }

    public function getUsers()
    {
        if (auth()->user()->hasAnyRole([Role::MASTER, Role::MANAGER])) {
            $users = User::with('profile')
                ->where('status', '!=', UserStatus::RESIGNED)
                ->get();
        } else {
            $users = User::with('profile')
                ->where('status', '!=', UserStatus::RESIGNED)
                ->get()
                ->makeHidden(['email'])
                ->each(function ($user) {
                    $user->profile->makeHidden(['birthday']);
                });
        }

        return response()->json($users);
    }
}
