<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->renameColumn('accounting_id', 'accounting_list_id');
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
            $table->renameColumn('accounting_list_id', 'accounting_id');
        });
    }
};
