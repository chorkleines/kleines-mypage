<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIdentityVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('identity_verifications');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->primary();
            $table->dateTime('datetime')->nullable();
            $table->string('token', 64)->nullable();
        });
    }
}
