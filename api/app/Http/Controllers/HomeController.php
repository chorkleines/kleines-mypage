<?php

namespace App\Http\Controllers;

class HomeController extends Controller
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
     * Get the payment information of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/home/payment-info",
     *     summary="Get payment information of the authenticated user",
     *     tags={"Home"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="arrears",
     *                         type="number",
     *                     ),
     *                     @OA\Property(
     *                         property="balance",
     *                         type="number",
     *                     ),
     *                 ),
     *             },
     *         )
     *     ),
     * )
     */
    public function getPaymentInfo()
    {
        $payment_info = [
            'arrears' => auth()->user()->getArrears(),
            'balance' => auth()->user()->getBalance(),
        ];

        return response()->json($payment_info);
    }
}
