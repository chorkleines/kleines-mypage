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
     *     path="/api/admin/individual-accountings",
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
    public function getIndividualAccountings()
    {
        $individualAccountingList = IndividualAccountingList::all();
        return response()->json($individualAccountingList);
    }
}
