<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUsers()
    {
        $users = DB::table('users')->join('profiles', 'users.user_id', '=', 'profiles.user_id')
            ->select('users.user_id', 'users.status', 'profiles.first_name', 'profiles.last_name', 'profiles.grade', 'profiles.part', 'profiles.name_kana')
            ->where('users.status', '!=', UserStatus::RESIGNED)
            ->get();

        return response()->json($users);
    }
}
