<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['auth']]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user()->toArray();
        $profile = auth()->user()->profile->toArray();
        $me = array_merge($user, $profile);

        return response()->json($me);
    }

    /**
     * Check if the user is authenticated.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request)
    {
        if ($request->user('sanctum')) {
            return response()->json('authenticated');
        } else {
            return response()->json('unauthenticated');
        }
    }
}
