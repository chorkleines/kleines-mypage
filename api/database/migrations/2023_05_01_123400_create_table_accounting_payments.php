<?php

use App\Enums\PaymentMethod;
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
        Schema::create('accounting_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('accounting_record_id')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->string('method', 32)->nullable(false);
            $table->timestamps();
        });

        $records = DB::table('accounting_records')->where('is_paid', true)->get();
        foreach ($records as $record) {
            if ($record->paid_cash > 0) {
                DB::table('accounting_payments')->insert([
                    'accounting_record_id' => $record->id,
                    'price' => $record->paid_cash,
                    'method' => PaymentMethod::CASH,
                ]);
            }
            if ($record->price - $record->paid_cash > 0) {
                DB::table('accounting_payments')->insert([
                    'accounting_record_id' => $record->id,
                    'price' => $record->price - $record->paid_cash,
                    'method' => PaymentMethod::INDIVIDUAL_ACCOUNTING,
                ]);
            }
        }

        Schema::table('accounting_records', function (Blueprint $table) {
            $table->dropColumn('paid_cash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->integer('paid_cash')->nullable()->default(null)->after('price');
        });

        $payments = DB::table('accounting_payments')->get();
        foreach ($payments as $payment) {
            if (strcmp($payment->method, PaymentMethod::INDIVIDUAL_ACCOUNTING) == 0) {
                DB::table('accounting_records')
                    ->where('id', $payment->accounting_record_id)
                    ->update(['paid_cash' => 0]);
            }
        }
        foreach ($payments as $payment) {
            if (strcmp($payment->method, PaymentMethod::CASH) == 0) {
                DB::table('accounting_records')
                    ->where('id', $payment->accounting_record_id)
                    ->update(['paid_cash' => $payment->price]);
            }
        }
        Schema::dropIfExists('accounting_payments');
    }
};
