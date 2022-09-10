<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('login_histories');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->dateTime('datetime');
            $table->integer('success')->nullable();
            $table->string('IP', 32)->nullable();
            $table->primary(['user_id', 'datetime']);
        });
    }
}
