<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual_accounting_records', function (Blueprint $table) {
            $table->renameColumn('list_id', 'individual_accounting_list_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual_accounting_records', function (Blueprint $table) {
            $table->renameColumn('individual_accounting_list_id', 'list_id');
        });
    }
};
