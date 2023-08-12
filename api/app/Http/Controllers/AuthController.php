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
     *
     * @OA\Get(
     *     path="/api/auth",
     *     summary="Checks if the user is authenticated",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="authenticated", type="boolean")
     *                 ),
     *             },
     *             @OA\Examples(example="authenticated", value={"authenticated": true}, summary="User is authenticated"),
     *             @OA\Examples(example="not authenticated", value={"authenticated": false}, summary="User is not authenticated"),
     *         )
     *     )
     * )
     */
    public function auth(Request $request)
    {
        if ($request->user('sanctum')) {
            return response()->json(['authenticated' => true]);
        } else {
            return response()->json(['authenticated' => false]);
        }
    }
}
