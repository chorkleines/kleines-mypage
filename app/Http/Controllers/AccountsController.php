<?php

namespace App\Http\Controllers;

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
        return view('accounts.list');
    }
}
