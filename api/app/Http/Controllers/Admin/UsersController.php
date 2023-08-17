<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Part;
use App\Enums\Role;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    /**
     * @OA\Put(
     *     path="/api/admin/users/{id}",
     *     summary="Update user by ID",
     *     description="Update user by ID. Only `MASTER` and `MANAGER` are allowed to update users.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\RequestBody(
     *         description="User object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="email", type="string", example="example@chorkleines.com"),
     *                     @OA\Property(property="status", type="string", example="PRESENT"),
     *                     @OA\Property(
     *                         property="roles",
     *                         type="array",
     *                         @OA\Items(
     *                             type="string",
     *                             example="MASTER",
     *                         ),
     *                     ),
     *                 )
     *             }
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="title", type="string", example="Bad Request"),
     *                     @OA\Property(property="status", type="integer", example=400),
     *                     @OA\Property(
     *                         property="detail",
     *                         allOf={
     *                             @OA\Schema(
     *                                 @OA\Property(
     *                                     property="roles.0",
     *                                     type="array",
     *                                     @OA\Items(type="string", example="The selected roles.0 is invalid."),
     *                                 ),
     *                             ),
     *                         },
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
    public function editUser(Request $request, $id)
    {
        if (! auth()->user()->hasAnyRole([Role::MASTER, Role::MANAGER])) {
            abort(403);
        }

        $user = User::where('id', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'status' => ['string', Rule::in(UserStatus::PRESENT, UserStatus::ABSENT, UserStatus::RESIGNED)],
            'roles.*' => ['string', Rule::in(Role::MASTER, Role::MANAGER, Role::ACCOUNTANT, Role::CAMP)],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Bad Request',
                'status' => 400,
                'detail' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validated();
        $user->update($validated);

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/users",
     *     summary="Create user",
     *     description="Create user. Only `MASTER` and `MANAGER` are allowed to create users.",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         description="User object",
     *         required=true,
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
     *             }
     *         ),
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
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="title", type="string", example="Bad Request"),
     *                     @OA\Property(property="status", type="integer", example=400),
     *                     @OA\Property(
     *                         property="detail",
     *                         allOf={
     *                             @OA\Schema(
     *                                 @OA\Property(
     *                                     property="roles.0",
     *                                     type="array",
     *                                     @OA\Items(type="string", example="The selected roles.0 is invalid."),
     *                                 ),
     *                             ),
     *                         },
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
    public function createUser(Request $request)
    {
        if (! auth()->user()->hasAnyRole([Role::MASTER, Role::MANAGER])) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'email' => ['string', 'email', 'max:255', 'unique:users', 'required'],
            'status' => ['string', Rule::in(UserStatus::PRESENT, UserStatus::ABSENT, UserStatus::RESIGNED), 'required'],
            'roles.*' => ['string', Rule::in(Role::MASTER, Role::MANAGER, Role::ACCOUNTANT, Role::CAMP)],
            'profile.last_name' => ['string', 'max:255', 'required'],
            'profile.first_name' => ['string', 'max:255', 'required'],
            'profile.name_kana' => ['string', 'max:255', 'nullable'],
            'profile.grade' => ['int', 'required'],
            'profile.part' => ['string', Rule::in(Part::SOPRANO, Part::ALTO, Part::TENOR, Part::BASS), 'required'],
            'profile.birthday' => ['date', 'nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Bad Request',
                'status' => 400,
                'detail' => $validator->errors(),
            ], 400);
        }
        $validated = $validator->validated();

        $user = User::create($validated);
        $user->profile()->create($validated['profile']);

        $newUser = User::where('id', $user->id)
            ->with('profile')
            ->firstOrFail();

        return response()->json($newUser, 200);
    }
}
