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

    public function getAccounting($id)
    {
        $accounting = DB::table('accounting_records')
            ->join('accounting_lists', 'accounting_records.accounting_id', '=', 'accounting_lists.accounting_id')
            ->where('accounting_records.user_id', auth()->user()->user_id)
            ->where('accounting_records.accounting_id', $id)
            ->first();
        if ($accounting->is_paid == true) {
            $payments = DB::table('accounting_payments')
                ->where('accounting_record_id', $accounting->id)
                ->get()->toArray();
            $response = [
                'accounting' => $accounting,
                'payments' => $payments,
            ];
        } else {
            $response = [
                'accounting' => $accounting,
            ];
        }

        return response()->json($response);
    }
}
