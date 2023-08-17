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

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Property(
     *                     type="array",
     *                     @OA\Items(
     *                          allOf={
     *                              @OA\Schema(ref="#/components/schemas/User"),
     *                              @OA\Schema(
     *                                  @OA\Property(
     *                                      property="profile",
     *                                      ref="#/components/schemas/Profile",
     *                                  ),
     *                              ),
     *                          },
     *                     ),
     *                 ),
     *             },
     *         )
     *     ),
     * )
     */
    public function getUsers()
    {
        $users = User::with('profile')
            ->where('status', '!=', UserStatus::RESIGNED)
            ->get()
            ->makeHidden(['email'])
            ->each(function ($user) {
                $user->profile->makeHidden(['birthday']);
            });

        return response()->json($users);
    }
}
