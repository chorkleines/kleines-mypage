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
            $table->dropPrimary();
        });
        Schema::table('accounting_records', function (Blueprint $table) {
            $table->id()->first();
            $table->unique(['accounting_id', 'user_id']);
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
            $table->dropColumn('id');
            $table->dropUnique(['accounting_id', 'user_id']);
            $table->primary(['accounting_id', 'user_id']);
        });
    }
};
