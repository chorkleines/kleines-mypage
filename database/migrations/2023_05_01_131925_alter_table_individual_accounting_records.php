<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $accounting_payments = DB::select(
            DB::raw('select `individual_accounting_records`.`user_id`, `individual_accounting_records`.`accounting_id`, `accounting_payments`.`id` from `individual_accounting_records` inner join `accounting_records` on `individual_accounting_records`.`accounting_id` = `accounting_records`.`accounting_id` and `individual_accounting_records`.`user_id` = `accounting_records`.`user_id` inner join `accounting_payments` on `accounting_records`.`id` = `accounting_payments`.`accounting_record_id`;')
        );
        foreach ($accounting_payments as $accounting_payment) {
            DB::table('individual_accounting_records')
                ->where('user_id', $accounting_payment->user_id)
                ->where('accounting_id', $accounting_payment->accounting_id)
                ->update(['accounting_id' => $accounting_payment->id]);
        }

        Schema::table('individual_accounting_records', function (Blueprint $table) {
            $table->renameColumn('accounting_id', 'accounting_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $accounting_records = DB::select(
            DB::raw('select `individual_accounting_records`.`user_id`, `individual_accounting_records`.`accounting_payment_id`, `accounting_records`.`accounting_id` from `individual_accounting_records` inner join `accounting_payments` on `individual_accounting_records`.`accounting_payment_id` = `accounting_payments`.`id` inner join `accounting_records` on `individual_accounting_records`.`user_id` = `accounting_records`.`user_id` and `accounting_payments`.`accounting_record_id` = `accounting_records`.`id`;')
        );
        foreach ($accounting_records as $accounting_record) {
            DB::table('individual_accounting_records')
                ->where('user_id', $accounting_record->user_id)
                ->where('accounting_payment_id', $accounting_record->accounting_payment_id)
                ->update(['accounting_payment_id' => $accounting_record->accounting_id]);
        }
        Schema::table('individual_accounting_records', function (Blueprint $table) {
            $table->renameColumn('accounting_payment_id', 'accounting_id');
        });
    }
};
