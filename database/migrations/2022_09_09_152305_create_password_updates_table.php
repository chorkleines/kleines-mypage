<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_updates', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->dateTime('datetime');
            $table->string('IP', 32)->nullable();
            $table->primary(['user_id', 'datetime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_updates');
    }
}
