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
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get the authenticated user",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/User"),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="last_name",
     *                             type="string",
     *                             example="山田",
     *                         ),
     *                     ),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="first_name",
     *                             type="string",
     *                             example="太郎",
     *                         ),
     *                     ),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="name_kana",
     *                             type="string",
     *                             example="ヤマダタロウ",
     *                         ),
     *                     ),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="grade",
     *                             type="integer",
     *                             example=18,
     *                         ),
     *                     ),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="part",
     *                             type="string",
     *                             example="T",
     *                         ),
     *                     ),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="email",
     *                             type="string",
     *                             example="admin@chorkleines.com",
     *                         ),
     *                     ),
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             property="birthday",
     *                             type="string",
     *                             example="2000-01-01",
     *                         ),
     *                     ),
     *             },
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="title", type="string", example="Unauthorized"),
     *                     @OA\Property(property="status", type="integer", example=401),
     *                     @OA\Property(property="detail", type="string", example="Unauthorized"),
     *                 ),
     *             },
     *         )
     *     )
     * )
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
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="authenticated", type="boolean", example=true)
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
