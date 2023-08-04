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
        Schema::dropIfExists('bulletin_board_hashtags');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bulletin_board_hashtags', function (Blueprint $table) {
            $table->integer('bulletin_board_id')->unsigned();
            $table->string('hashtag', 32);
            $table->primary(['bulletin_board_id', 'hashtag']);
        });
    }
};
