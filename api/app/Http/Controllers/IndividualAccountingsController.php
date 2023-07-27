<?php

namespace App\Http\Controllers;

use App\Models\IndividualAccountingRecord;

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
