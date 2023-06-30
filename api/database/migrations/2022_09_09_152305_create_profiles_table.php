<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->primary();
            $table->string('last_name', 256)->nullable();
            $table->string('first_name', 256)->nullable();
            $table->string('name_kana', 256)->nullable();
            $table->integer('grade')->unsigned()->nullable();
            $table->string('part', 1)->nullable();
            $table->date('birthday')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles');
    }
}
