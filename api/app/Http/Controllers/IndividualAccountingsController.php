<?php

namespace App\Http\Controllers;

use App\Models\IndividualAccountingRecord;

/**
 * @OA\Get(
 *     path="/api/individual-accountings",
 *     summary="Get individual accountings of the authenticated user",
 *     tags={"Individual Accountings"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             oneOf={
 *                 @OA\Property(
 *                     type="array",
 *                     @OA\Items(
 *                          allOf={
 *                              @OA\Schema(ref="#/components/schemas/IndividualAccountingRecord"),
 *                              @OA\Schema(
 *                                  @OA\Property(
 *                                      property="individual_accounting_list",
 *                                      ref="#/components/schemas/IndividualAccountingList",
 *                                  ),
 *                              ),
 *                              @OA\Schema(
 *                                  @OA\Property(
 *                                      property="accounting_payment",
 *                                      allOf={
 *                                          @OA\Schema(ref="#/components/schemas/AccountingPayment"),
 *                                          @OA\Schema(
 *                                              @OA\Property(
 *                                                  property="accounting_record",
 *                                                  allOf={
 *                                                      @OA\Schema(ref="#/components/schemas/AccountingRecord"),
 *                                                      @OA\Schema(
 *                                                          @OA\Property(
 *                                                              property="accounting_list",
 *                                                              ref="#/components/schemas/AccountingList",
 *                                                          ),
 *                                                      ),
 *                                                  },
 *                                              ),
 *                                          ),
 *                                      },
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
class IndividualAccountingsController extends Controller
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

    public function getIndividualAccountings()
    {
        $individual_accountings = IndividualAccountingRecord::with('individual_accounting_list', 'accounting_payment.accounting_record.accounting_list')
            ->where('user_id', auth()->user()->id)
            ->get();

        return response()->json($individual_accountings);
    }
}
