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
        Schema::dropIfExists('bulletin_board_views');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bulletin_board_views', function (Blueprint $table) {
            $table->integer('bulletin_board_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->dateTime('datetime');
            $table->primary(['bulletin_board_id', 'datetime']);
        });
    }
};
