<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualAccountingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_accounting_records', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable();
            $table->dateTime('datetime')->nullable();
            $table->integer('price')->nullable();
            $table->integer('accounting_id')->unsigned()->nullable();
            $table->integer('list_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('individual_accounting_records');
    }
}
