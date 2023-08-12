<?php

namespace App\Http\Controllers;

use App\Models\AccountingRecord;

class AccountingsController extends Controller
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
     *     path="/api/accountings",
     *     summary="Get accountings of the authenticated user",
     *     tags={"Accountings"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Property(
     *                     type="array",
     *                     @OA\Items(
     *                          allOf={
     *                              @OA\Schema(ref="#/components/schemas/AccountingRecord"),
     *                              @OA\Schema(
     *                                  @OA\Property(
     *                                      property="accounting_list",
     *                                      ref="#/components/schemas/AccountingList",
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
    public function getAccountings()
    {
        $accountings = AccountingRecord::with('accounting_list')
            ->where('user_id', auth()->user()->id)
            ->get();

        return response()->json($accountings);
    }

    /**
     * @OA\Get(
     *     path="/api/accountings/{id}",
     *     summary="Get accountings of the authenticated user",
     *     tags={"Accountings"},
     *     @OA\Parameter(
     *         description="Accounting List ID",
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
     *                 @OA\Schema(ref="#/components/schemas/AccountingRecord"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="accounting_list",
     *                         ref="#/components/schemas/AccountingList",
     *                     ),
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="accounting_payments",
     *                         type="array",
     *                         @OA\Items(
     *                             allOf={
     *                                 @OA\Schema(ref="#/components/schemas/AccountingPayment"),
     *                             },
     *                         ),
     *                     ),
     *                 ),
     *             },
     *         )
     *     ),
     * )
     */
    public function getAccounting($id)
    {
        $accounting = AccountingRecord::with(['accounting_list', 'accounting_payments'])
            ->where('user_id', auth()->user()->id)
            ->where('accounting_list_id', $id)
            ->first();
        if ($accounting === null) {
            abort(404);
        }

        return response()->json($accounting);
    }
}
