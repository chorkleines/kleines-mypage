<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Part;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:master,manager']);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/profiles/{id}",
     *     summary="Update profile by user ID",
     *     description="Update profile by user ID. Only `MASTER` and `MANAGER` are allowed to update profiles.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\RequestBody(
     *         description="Profile object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/Profile"),
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
     *                                     property="part",
     *                                     type="array",
     *                                     @OA\Items(type="string", example="The selected part is invalid.")
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
    public function editProfile(Request $request, $id)
    {
        $profile = Profile::where('user_id', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'last_name' => ['string', 'max:255'],
            'first_name' => ['string', 'max:255'],
            'name_kana' => ['string', 'max:255', 'nullable'],
            'grade' => ['int'],
            'part' => ['string', Rule::in(Part::SOPRANO, Part::ALTO, Part::TENOR, Part::BASS)],
            'birthday' => ['string', 'date', 'nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Bad Request',
                'status' => 400,
                'detail' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validated();
        $profile->update($validated);

        return response()->json(null, 204);
    }
}
