<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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
        $individual_accountings = DB::table('individual_accounting_records')
            ->select(
                'individual_accounting_records.price',
                'individual_accounting_lists.name',
                'individual_accounting_records.created_at',
                'individual_accounting_records.updated_at',
                'individual_accounting_records.datetime',
            )
            ->join('individual_accounting_lists', 'individual_accounting_records.list_id', '=', 'individual_accounting_lists.list_id')
            ->where('individual_accounting_records.user_id', auth()->user()->user_id)
            ->get();

        $individual_accountings_payment = DB::table('individual_accounting_records')
            ->select(
                'individual_accounting_records.price',
                'individual_accounting_records.created_at',
                'individual_accounting_records.updated_at',
                'accounting_lists.name',
                'accounting_records.datetime',
                'accounting_records.accounting_id',
            )
            ->join('accounting_payments', 'individual_accounting_records.accounting_payment_id', '=', 'accounting_payments.id')
            ->join('accounting_records', 'accounting_payments.accounting_record_id', '=', 'accounting_records.id')
            ->join('accounting_lists', 'accounting_records.accounting_id', '=', 'accounting_lists.accounting_id')
            ->where('individual_accounting_records.user_id', auth()->user()->user_id)
            ->get();

        $individual_accountings = $individual_accountings->merge($individual_accountings_payment);

        return response()->json($individual_accountings);
    }
}
