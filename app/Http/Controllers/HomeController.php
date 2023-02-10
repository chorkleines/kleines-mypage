<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get the payment information of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentInfo()
    {
        $arrears = auth()->user()->accountingRecords->where('datetime', null)->sum('price');
        $balance = auth()->user()->individualAccountingRecords->sum('price');

        return response()->json(['arrears' => $arrears, 'balance' => $balance]);
    }
}
