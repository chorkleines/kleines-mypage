<?php

namespace App\Http\Controllers;

use App\Models\AccountingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingsController extends Controller
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

    public function getAccountings()
    {
        $accountings = DB::table('accounting_records')
            ->join('accounting_lists', 'accounting_records.accounting_id', '=', 'accounting_lists.accounting_id')
            ->where('accounting_records.user_id', auth()->user()->user_id)
            ->get();

        return response()->json($accountings);
    }
}
