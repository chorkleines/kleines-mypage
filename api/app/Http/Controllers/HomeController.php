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
