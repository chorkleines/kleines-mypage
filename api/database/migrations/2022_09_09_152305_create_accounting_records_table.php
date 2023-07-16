<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_records', function (Blueprint $table) {
            $table->integer('accounting_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('price')->nullable();
            $table->integer('paid_cash')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->primary(['accounting_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounting_records');
    }
}
