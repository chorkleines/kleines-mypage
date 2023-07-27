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

    public function getAccountings()
    {
        $accountings = AccountingRecord::with('accounting_list')
            ->where('user_id', auth()->user()->id)
            ->get();

        return response()->json($accountings);
    }

    public function getAccounting($id)
    {
        $accounting = AccountingRecord::with(['accounting_list', 'accounting_payments'])
            ->where('user_id', auth()->user()->id)
            ->where('accounting_list_id', $id)
            ->first();

        return response()->json($accounting);
    }
}
