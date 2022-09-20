<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Require authentication
        $this->middleware('auth');
    }

    /**
     * Show the account list
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function list()
    {
        $profiles = Profile::sortable(['grade', 'part', 'name_kana'])->paginate(20);

        return view('accounts.list')->with('profiles', $profiles);
    }
}
