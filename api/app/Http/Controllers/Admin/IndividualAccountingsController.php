<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndividualAccountingList;
use App\Models\IndividualAccountingRecord;
use Illuminate\Http\Request;

class IndividualAccountingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:master,accountant']);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/individual-accountings/list",
     *     summary="Get individual accounting lists",
     *     description="Get individual accounting lists. This API is available only for `MASTER` and `ACCOUNTANT`.",
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
     *                              @OA\Schema(ref="#/components/schemas/IndividualAccountingList"),
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
    public function getIndividualAccountingLists()
    {
        $individualAccountingList = IndividualAccountingList::all();
        return response()->json($individualAccountingList);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/individual-accountings/list/{id}",
     *     summary="Get individual accounting records by list id",
     *     description="Get individual accounting records by list id. This API is available only for `MASTER` and `ACCOUNTANT`.",
     *     tags={"Admin"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Individual accounting list id",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/IndividualAccountingList"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="individual_accounting_records",
     *                         type="array",
     *                         @OA\Items(
     *                             allOf={
     *                                 @OA\Schema(ref="#/components/schemas/IndividualAccountingRecord"),
     *                                 @OA\Schema(
     *                                     @OA\Property(
     *                                         property="user",
     *                                         type="object",
     *                                         @OA\Property(property="id", type="integer", example=1),
     *                                         @OA\Property(property="status", type="string", example="PRESENT"),
     *                                         @OA\Property(
     *                                             property="profile",
     *                                             allOf={
     *                                                 @OA\Schema(ref="#/components/schemas/Profile"),
     *                                             },
     *                                         ),
     *                                     ),
     *                                 ),
     *                             },
     *                         ),
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
    public function getIndividualAccountingList($id)
    {
        $list = IndividualAccountingList::with(['individual_accounting_records', 'individual_accounting_records.user', 'individual_accounting_records.user.profile'])
            ->where('id', $id)
            ->firstOrFail();
        $records = $list->individual_accounting_records->each(function ($individual_accounting_record) {
            $individual_accounting_record->user->makeHidden(['email', 'roles']);
            $individual_accounting_record->user->profile->makeHidden(['birthday']);
        });
        $data = collect($list)->only(['id', 'name', 'datetime'])->union(['individual_accounting_records' => $records]);
        return response()->json($data->all());
    }
}
