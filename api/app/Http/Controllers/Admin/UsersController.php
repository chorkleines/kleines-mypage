<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
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
        $this->middleware(['auth:sanctum', 'role:master,manager,accountant,camp']);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     summary="Get active users",
     *     description="Get active users with emails and birthdays for `MASTER` and `MANAGER`. Otherwise, emails and birthdays are hidden",
     *     tags={"Admin"},
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
     *                                      allOf={
     *                                          @OA\Schema(ref="#/components/schemas/Profile"),
     *                                          @OA\Schema(
     *                                              @OA\Property(
     *                                                  property="birthday",
     *                                                  type="string",
     *                                                  example="2000-01-01",
     *                                              ),
     *                                          ),
     *                                      },
     *                                  ),
     *                              ),
     *                              @OA\Schema(
     *                                  @OA\Property(
     *                                      property="email",
     *                                      type="string",
     *                                      example="admin@chorkleines.com",
     *                                  ),
     *                              ),
     *                          },
     *                     ),
     *                 ),
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
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="title", type="string", example="Forbidden"),
     *                     @OA\Property(property="status", type="integer", example=403),
     *                     @OA\Property(property="detail", type="string", example="Forbidden"),
     *                 ),
     *             },
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/admin/users/{id}",
     *     summary="Get user by ID",
     *     description="Get user by ID. For `MASTER` and `MANAGER` emails and birthdays are given. They can also access resigned users. For `ACCOUNTANT` and `CAMP`, emails and birthdays are hidden.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/User"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="profile",
     *                         allOf={
     *                             @OA\Schema(ref="#/components/schemas/Profile"),
     *                             @OA\Schema(
     *                                 @OA\Property(
     *                                     property="birthday",
     *                                     type="string",
     *                                     example="2000-01-01",
     *                                 ),
     *                             ),
     *                         },
     *                     ),
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="admin@chorkleines.com",
     *                     ),
     *                 ),
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
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="title", type="string", example="Forbidden"),
     *                     @OA\Property(property="status", type="integer", example=403),
     *                     @OA\Property(property="detail", type="string", example="Forbidden"),
     *                 ),
     *             },
     *         )
     *     )
     * )
     */
    public function getUser($id)
    {
        $user = User::with('profile')
            ->where('id', $id)
            ->firstOrFail();

        if (auth()->user()->hasAnyRole([Role::MASTER, Role::MANAGER])) {
            return response()->json($user);
        }

        if (strcmp($user->status, UserStatus::RESIGNED) == 0) {
            abort(403);
        }

        $user->makeHidden(['email']);
        $user->profile->makeHidden(['birthday']);

        return response()->json($user);
    }
}
